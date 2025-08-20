<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\RequestLogs;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class GuzzleLoggingMiddleware
{
    public static function log(): callable
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                $start = microtime(true);
                return $handler($request, $options)->then(
                    function (ResponseInterface $response) use ($request, $start) {
                        $duration = round((microtime(true) - $start) * 1000, 2);
                        $responseBody = (string) $response->getBody();
                        $response->getBody()->rewind();
                        RequestLogs::create([
                            'method'           => $request->getMethod(),
                            'url'              => (string) $request->getUri(),
                            'request_headers'  => $request->getHeaders(),
                            'request_body'     => (string) $request->getBody(),
                            'status_code'      => $response->getStatusCode(),
                            'response_headers' => $response->getHeaders(),
                            'response_body'    => $responseBody,
                            'cookies'          => $request->getHeaderLine('Cookie'),
                            'duration_ms'      => $duration,
                        ]);
                        return $response;
                    }
                );
            };
        };
    }
}
