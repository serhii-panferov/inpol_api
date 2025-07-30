<?php

declare(strict_types=1);

namespace App\Services\Inpol;

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
                ':authority' => 'inpol.mazowieckie.pl',
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
            echo "Response status code: " . $response->getStatusCode() . "\n";
            $data = json_decode((string) $response->getBody(), true);
            $this->token = $data['token'];
            echo "Login successful, token: " . $this->token . "\n";
            return $this->token;
        } catch (\Throwable $e) {
            logger()->error('Login failed: ' . $e->getMessage());
            return null;
        }
    }

    public function fetchSlots(): array
    {
        // TODO: Парсинг сторінки з календарем
        return [];
    }
}
