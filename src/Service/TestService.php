<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class TestService
{
    public function __construct(
        private string $key,
        private readonly HttpClientInterface $httpClient
    ) {}

    public function test(): string
    {
        return $this->key;
    }

    public function getWeatherByCity(string $city): array
    {
        try {
        $response = $this->httpClient->request(
            'GET',
            'https://api.openweathermap.org/data/2.5/weather?q=' . urlencode($city) . '&appid=' . $this->key . '&units=metric&lang=fr'
        );
        $statusCode = $response->getStatusCode();
        if ($statusCode !== 200) {
            return ['error' => 'ville introuvable'];
        }
        return $response->toArray();
        } catch (\Exception $e) {
            return ['error' => 'Erreur lors de la récupération des données'];
        }
    }
}
