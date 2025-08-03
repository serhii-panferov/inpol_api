<?php

declare(strict_types=1);

namespace App\Services\Inpol;

use App\Models\InpolAccount;
use App\Models\InpolToken;
use App\Models\PeopleCase;
use App\Models\ReservationQueues;
use GuzzleHttp\Client;

class InpolClient
{
    protected Client $client;

    protected ?string $token = null;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://inpol.mazowieckie.pl/',
            'cookies' => true,
            'headers' => [
                'Accept-Language' => 'ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7,uk;q=0.6,da;q=0.5',
                'origin' => 'https://inpol.mazowieckie.pl',
                'Recaptchaactionname' => 'sign_in',
                'Priority' => 'u=1, i',
                'Referer' => 'https://inpol.mazowieckie.pl/login',
                'Dnt' => '1',
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36',
                'Content-Type' => 'application/json',
                'Accept-encoding' => 'gzip, deflate, br',
                'Accept' => '*/*',
            ],
        ]);
        $this->token = $this->getOrCreateToken();
    }

    protected function getOrCreateToken(): ?string
    {
        /** @var \App\Models\InpolAccount $account */
        $account = InpolAccount::where('email', getenv('INPOL_EMAIL'))->first();
        $existing = InpolToken::where('expires_at', '>', now())
            ->where('inpol_account_id', $account->id)
            ->latest('created_at')
            ->first();
        if ($existing) {
            logger()->info('Using existing token: ' . $existing->token);
            return $existing->token;
        }
        $newToken = $this->login();
        logger()->info('New token: ' . $newToken);
        if ($newToken) {
            $account->tokens()->create([
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

    public function fetchCases($results = 10): ?int
    {
        $peopleCases = PeopleCase::whereStatus(PeopleCase::STATUS_NEW)->get();
        if ($peopleCases->isNotEmpty()) {
            logger()->info('There are ' . count($peopleCases) . ' cases already in the database.');
            return count($peopleCases);
        }
        try {
            $response = $this->client->get('api/proceedings/list', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                ],
                'query' => [
                    'page' => 1,
                    'results' => $results,
                    'filterBy' => '',
                    'orderBy' => '',
                ],
            ]);
            $data = json_decode((string) $response->getBody(), true);
            logger()->info('TotalResults of received cases: ' . $data['totalResults']);
            PeopleCase::updateOrCreateMany($data['items']);
            return count($data['items']) ?? null;
        } catch (\Throwable $e) {
           // logger()->error('Failed to fetch cases: ' . $e->getMessage());
            return null;
        }
    }

    public function fetchReservationQueues($caseId): ?array
    {
        //TODO Considere using a static values instead of fetching from API.
        try {
            $response = $this->client->get(
                'api/proceedings/' . $caseId .'/reservationQueues',
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->token,
                    ],
                ]
            );
            $data = json_decode((string) $response->getBody(), true);
            logger()->info('TotalResults of received reservation queues: ' . $data['totalResults']);
            return $data ?? null;
        } catch (\Throwable $e) {
            logger()->error('Failed to fetch reservation queues: ' . $e->getMessage());
            return null;
        }
    }

    public function fetchSlots(): array
    {
        // TODO: Парсинг сторінки з календарем
        return [];
    }

    public function getToken(): ?string
    {
        return $this->token;
    }
}
