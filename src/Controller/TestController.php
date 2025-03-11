<?php

namespace App\Controller;

use App\Service\TestService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/weather', name: 'app_weather')]
    public function showWeather(Request $request, TestService $testService): Response
    {
        $city = $request->query->get('city', 'Toulouse');  // Valeur par défaut si aucune ville n'est renseignée

        $weatherData = $testService->getWeatherByCity($city);

        $temperature = $weatherData['main']['temp'];
        $temperatureCelsius = $temperature;
        $country = $weatherData['sys']['country'];
        $humidity = $weatherData['main']['humidity'];
        $description = $weatherData['weather'][0]['description'];
        $windSpeed = $weatherData['wind']['speed'];
        $iconNumber = $weatherData['weather'][0]['icon'];
        $iconURL = 'https://openweathermap.org/img/wn/' . $iconNumber . '@2x.png';

        return $this->render('weather.html.twig', [
            'city' => $city,
            'temperature' => $temperatureCelsius,
            'country' => $country,
            'humidity' => $humidity,
            'description' => $description,
            'windSpeed' => $windSpeed,
            'iconURL' => $iconURL
        ]);
    }
}
