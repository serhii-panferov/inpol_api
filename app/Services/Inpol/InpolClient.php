<?php

declare(strict_types=1);

namespace App\Services\Inpol;

use App\Models\InpolAccount;
use App\Models\InpolToken;
use App\Models\PeopleCase;
use App\Models\ReservationQueues;
use App\Models\ReservationSlots;
use App\Models\TypesPeopleCase;
use GuzzleHttp\Client;

class InpolClient
{
    protected Client $client;

    protected InpolAccount $account;

    protected ?string $token = null;

    private const INPOL_API_DOMAIN = 'https://inpol.mazowieckie.pl/';

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => self::INPOL_API_DOMAIN,
            'timeout' => 20,
            'cookies' => true,
            'headers' => [
                'Accept-Language' => 'ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7,uk;q=0.6,da;q=0.5',
                'Priority' => 'u=1, i',
                'Referer' => self::INPOL_API_DOMAIN . '/login',
                'Dnt' => '1',
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36',
                'Content-Type' => 'application/json',
                'Accept-encoding' => 'gzip, deflate, br',
                'Accept' => '*/*',
            ],
        ]);
        $this->account = InpolAccount::where('email', getenv('INPOL_EMAIL'))->first();
        $this->token = $this->getOrCreateToken();
    }

    protected function getOrCreateToken(): ?string
    {
        $existing = InpolToken::where('expires_at', '>', now())
            ->where('inpol_account_id', $this->account->getKey())
            ->latest('created_at')
            ->first();
        if ($existing) {
            logger()->info('Using existing token: ' . $existing->token);
            return $existing->token;
        }
        $newToken = $this->login();
        logger()->info('New token: ' . $newToken);
        if ($newToken) {
            $this->account->tokens()
                ->create([
                'token' => $newToken,
                'expires_at' => now()->addMinutes(15),
            ]);
        }
        return $newToken;
    }

    public function login(): ?string
    {
        $email = env('INPOL_EMAIL');
        $password = env('INPOL_PASSWORD');
        try {
            $response = $this->client->post('identity/sign-in', [
                'headers' => [
                    'origin' => self::INPOL_API_DOMAIN,
                    'Recaptchaactionname' => 'sign_in',
                ],
                'json' => [
                    'email'    => $email,
                    'password' => $password,
                    'expiryMinutes' => 0,
                ],
            ]);
            $data = json_decode((string) $response->getBody(), true);
            return $data['token'] ?? null;
        } catch (\Throwable $e) {
            logger()->error('Login failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Fetch cases from Inpol API.
     * This method retrieves cases with status 'new' for the current account.
     *
     * @param int $results The number of results to fetch, default is 10.
     * @return PeopleCase[]|null
     */
    public function fetchCases(int $results = 10): ?array
    {
        /** @var \App\Models\PeopleCase $peopleCases */
        $peopleCases = PeopleCase::with('account')
            ->where(['status' => PeopleCase::STATUS_NEW])
            ->where(['inpol_account_id' => $this->account->getKey()])
            ->get(['id', 'type_id', 'inpol_account_id']);
        if ($peopleCases->isNotEmpty()) {
            logger()->info('There are ' . $peopleCases->count() . ' cases already in the database.');
            return $peopleCases->toArray();
        }
        try {
            $casesPath = 'api/proceedings/list';
            $referer = self::INPOL_API_DOMAIN . 'home/cases';
            $response = $this->client->get($casesPath, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                   // 'Referer' => $referer,
                ],
                'query' => [
                    'page' => 1,
                    'results' => $results,
                    'filterBy' => '',
                    'orderBy' => '',
                ],
            ]);
            //logger()->info(var_export($response, true));
            $data = json_decode((string) $response->getBody(), true);
            logger()->info('TotalResults of received cases: ' . $data['totalResults']);
            PeopleCase::updateOrCreateMany($data['items'], $this->account);
            return $data['items'] ?? null;
        } catch (\Throwable $e) {
            logger()->error('Failed to fetch cases: ' . $e->getMessage());
            return null;
        }
    }

    public function fetchPersonalDate($caseId): ?array
    {
        /** @var \App\Models\PeopleCase $peopleCases */
//        $peopleCases = PeopleCase::with('account')
//            ->where(['status' => PeopleCase::STATUS_NEW])
//            ->where(['inpol_account_id' => $this->account->getKey()])
//            ->get(['id', 'type_id', 'inpol_account_id']);
//        if ($peopleCases->isNotEmpty()) {
//            logger()->info('There are ' . $peopleCases->count() . ' cases already in the database.');
//            return $peopleCases->toArray();
//        }
        try {
            $casesPath = 'api/proceedings/' . $caseId;
            $referer = self::INPOL_API_DOMAIN . 'home/cases/' . $caseId;
            $response = $this->client->get($casesPath, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                   // 'Referer' => $referer,
                ],
            ]);
            $data = json_decode((string) $response->getBody(), true);
            logger()->info('Personal data for case ID ' . $caseId . ' fetched successfully.');
            PeopleCase::updateOrCreate($data);
            return $data ?? null;
        } catch (\Throwable $e) {
            logger()->error(
                'Failed to fetch personal data for case Id ' . $caseId . ':' . $e->getMessage(),
            );
            return null;
        }
    }

    public function fetchReservationQueues($caseId, $typeId): ?array
    {
        $ReservationQueues = ReservationQueues::with('typePeopleCase')
            ->where(
                'type_people_case_id',
                TypesPeopleCase::where('type_id', $typeId)
                    ->first()
                    ->id,
            )
            ->get()
            ->toArray();
        if (!empty($ReservationQueues)) {
            return $ReservationQueues;
        }
        //TODO Consider using a static values instead of fetching from API.
        try {
            $reservationPath = '/api/proceedings/' . $caseId . '/reservationQueues';
            $referer = self::INPOL_API_DOMAIN . 'home/cases/'. $caseId;
            $response = $this->client->get(
                $reservationPath,
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->token,
                        'Referer' => $referer,
                    ],
                ]
            );
            $data = json_decode((string) $response->getBody(), true);
            $countReservationQueues = count($data);
            logger()->info('TotalResults of received reservation queues: ' . $countReservationQueues);
            return $data ?? null;
        } catch (\Throwable $e) {
            logger()->error('Failed to fetch reservation queues: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Fetch available dates for a given reservation queue.
     * Provides a list of dates 6 weeks in advance when a booking queue is available.
     *
     * @param string $caseId Case ID to fetch dates for.
     * @param string $queueId Reservation queue ID to fetch dates for.
     * @return array|null
     */
    public function fetchDates(string $caseId, string $queueId): ?array
    {
        try {
            $reservationPath = '/api/reservations/queue/' . $queueId . '/dates';
            $referer = self::INPOL_API_DOMAIN . 'home/cases/'. $caseId;
            $response = $this->client->post(
                $reservationPath,
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->token,
                        'Referer' => $referer,
                    ],
                ]
            );
            $data = json_decode((string) $response->getBody(), true);
            logger()->info('The reservation queue has ' . count($data) . ' available dates.');
            return $data ?? null;
        } catch (\Throwable $e) {
            logger()->error('Failed to fetch reservation queues dates: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get available slots for a given reservation queue.
     *
     * @param string $caseId Case ID to fetch slots for.
     * @param string $queueId Reservation queue ID to fetch slots for.
     * @param string $requestDate The date for which to fetch available slots, formatted as 'Y-m-d'.
     * @param string $peopleCaseType Type of the case.
     * @return array|null
     */
    public function fetchSlots(string $caseId, string $queueId, string $requestDate, string $peopleCaseType): ?array
    {
        $slots = ReservationSlots::with('typePeopleCase')
            ->where(
                'type_people_case_id',
                TypesPeopleCase::where('type_id', $peopleCaseType)
                    ->first()
                    ->id,
            )
            ->where('status', '=', 0)
            ->get()
            ->toArray();
        if (!empty($slots)) {
            logger()->info('There are ' . count($slots) . ' slots already in the database.');
            return $slots;
        }
        try {
            $reservationPath = '/api/reservations/queue/' . $queueId . '/' . $requestDate . '/slots';
            $referer = self::INPOL_API_DOMAIN . 'home/cases/' . $caseId;
            $response = $this->client->post(
                $reservationPath,
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->token,
                        'Referer' => $referer,
                    ],
                ]
            );
            $slots = json_decode((string)$response->getBody(), true);
            if (!empty($slots)) {
                ReservationSlots::updateOrCreateMany($slots, $peopleCaseType);
            }
            logger()->info('Available slots: ' . count($slots));
            return $slots ?? null;
        } catch (\Throwable $e) {
            logger()->error('Failed to fetch queues slots: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Reserve a room in a queue for a specific date.
     *
     * @param string $caseId Case ID to fetch slots for.
     * @param string $queueId Reservation queue ID to fetch slots for.
     * @param string $slotId Slot ID to reserve.
     * @return array|null
     */
    public function reserveRoomInQueue(string $caseId, string $queueId, string $slotId): ?array
    {
        try {
            $reservationPath = '/api/reservations/queue/' . $queueId . '/reserve';
            //$referer = self::INPOL_API_DOMAIN . 'home/cases/' . $caseId;
            $response = $this->client->post(
                $reservationPath,
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->token,
                      //  'Referer' => $referer,
                    ],
                    'json' => [
                        'proceedingId' => $caseId,
                        'slotId' => $slotId,
                        'name' => '',
                        'lastName' => '',
                        'dateOfBirth' => '',
                    ]
                ]
            );
            $slots = json_decode((string)$response->getBody(), true);
            logger()->info('Available slots: ' . count($slots));
            return $slots ?? null;
        } catch (\Throwable $e) {
            logger()->error('Failed to fetch queues slots: ' . $e->getMessage());
            return null;
        }
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * Get the date options based on the provided options.
     *
     * @param mixed[] $options Options for fetching slots, including date options.
     * @return string
     */
    public function getQueueDate(array $options): string
    {
        //TODO add condition to skip weekends and holidays
        $dateOptions = [
            'date' => $options['date'],
            '+2 days' => $options['date-next-2days'],
            '+6 weeks' => $options['date-next-6weeks'],
            'today' => $options['date-today'],
        ];
        return match (true) {
            $dateOptions['date'] => date('Y-m-d', strtotime($dateOptions['date'])),
            $dateOptions['+2 days'] => date('Y-m-d', strtotime('+2 days')),
            $dateOptions['+6 weeks'] => date('Y-m-d', strtotime('+6 weeks')),
            $dateOptions['today'] => date('Y-m-d'),
        };
    }
}
