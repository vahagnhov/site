<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;


class JsonRpcClient
{
    const JSON_RPC_VERSION = '2.0';

    const METHOD_URI = 'api/v1/weather';

    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'headers' => ['Content-Type' => 'application/json'],
            'base_uri' => config('services.data.base_uri')
        ]);
    }

    public function getLasts($method, array $params): array
    {
        try {
            $response = $this->client
                ->get(self::METHOD_URI, [
                    RequestOptions::JSON => [
                        'jsonrpc' => self::JSON_RPC_VERSION,
                        'id' => 1,
                        'method' => $method,
                        'params' => $params
                    ]
                ])->getBody()->getContents();

        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            $response = json_encode([
                'jsonrpc' => self::JSON_RPC_VERSION,
                'error' => [
                    'message' => $e->getMessage(),
                ],
                'id' => null,
            ]);

        }
        return json_decode($response, true);
    }

    public function getByDate(string $method, array $params): array
    {
        try {
            $response = $this->client
                ->post(self::METHOD_URI, [
                    RequestOptions::JSON => [
                        'jsonrpc' => self::JSON_RPC_VERSION,
                        'id' => 1,
                        'method' => $method,
                        'params' => $params
                    ]
                ])->getBody()->getContents();
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            $response = json_encode([
                'jsonrpc' => self::JSON_RPC_VERSION,
                'error' => [
                    'message' => $e->getMessage(),
                ],
                'id' => null,
            ]);

        }
        return json_decode($response, true);
    }
}