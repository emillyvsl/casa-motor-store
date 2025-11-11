<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class MelhorEnvioService
{
    protected string $baseUrl;
    protected string $token;
    protected string $userAgent;
    protected Client $client;

    public function __construct()
    {
        $this->baseUrl = config('services.melhorenvio.url', env('MELHORENVIO_URL'));
        $this->token = config('services.melhorenvio.token', env('MELHORENVIO_TOKEN'));
        $this->userAgent = config('services.melhorenvio.user_agent', env('MELHORENVIO_USER_AGENT'));
        $this->client = new Client(['timeout' => 15]);
    }

    public function calcularFrete(array $data): array
    {
        try {
            $response = $this->client->post($this->baseUrl, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->token,
                    'User-Agent' => $this->userAgent,
                ],
                'json' => $data,
            ]);

            $body = json_decode($response->getBody()->getContents(), true);
            return ['success' => true, 'data' => $body];
        } catch (\Throwable $e) {
            Log::error('Erro na cotação de frete Melhor Envio', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
