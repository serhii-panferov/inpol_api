<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\Inpol\InpolClient;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class GuzzleTokenMiddleware
{
    protected $client;
    protected $inpolClient; // your service that can refresh token

    public function __construct(\GuzzleHttp\Client $client, \App\Services\Inpol\InpolClient $inpolClient)
    {
        $this->client = $client;
        $this->inpolClient = $inpolClient;
    }

    public function handle(): callable
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                return $handler($request, $options)->then(
                    function (ResponseInterface $response) use ($request, $options, $handler) {
                        // if response indicates expired/invalid token
                        if ($response->getStatusCode() === 401 || $this->isInvalidToken($response)) {
                            // refresh token
                            $newToken = $this->inpolClient->login();
                            logger()->info('New token: ' . $newToken);
                            if ($newToken) {
                                $this->inpolClient->tokenExpirationTime = now()->addMinutes(InpolClient::EXPIRED_TIME)->toDateTimeString();
                                $this->inpolClient->account->tokens()
                                    ->create([
                                        'token' => $newToken,
                                        'expires_at' => $this->inpolClient->tokenExpirationTime,
                                    ]);
                            }
                            // retry same request with new token
                            $newRequest = $request->withHeader('Authorization', "Bearer {$newToken}");

                            return $handler($newRequest, $options);
                        }

                        return $response;
                    },
                    function ($reason) {
                        if ($reason instanceof RequestException && $reason->getResponse() && $reason->getResponse()->getStatusCode() === 401) {
                            logger()->warning("Request failed due to invalid token.");
                        }
                        throw $reason;
                    }
                );
            };
        };
    }

    protected function isInvalidToken(ResponseInterface $response): bool
    {
        $header = jsone_encode($response->getHeaders()['WWW-Authenticate'] ?? []);
        return str_contains($header, 'invalid token') || str_contains($header, 'expired');
    }
}
