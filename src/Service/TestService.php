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
        $response = $this->httpClient->request(
            'GET',
            'https://api.openweathermap.org/data/2.5/weather?q=' . urlencode($city) . '&appid=' . $this->key . '&units=metric&lang=fr'
        );
        return $response->toArray();
    }
}
