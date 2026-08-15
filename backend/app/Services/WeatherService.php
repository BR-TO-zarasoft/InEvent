<?php

namespace App\Services;

class WeatherService
{
    public function __construct(private readonly OpenWeatherClient $openWeather) {}

    public function geocode(string $query, int $limit): array
    {
        $places = $this->openWeather->geocode($query, $limit);

        return array_map(static fn (array $place): array => [
            'name' => $place['name'] ?? null,
            'country' => $place['country'] ?? null,
            'state' => $place['state'] ?? null,
            'lat' => (float) ($place['lat'] ?? 0),
            'lon' => (float) ($place['lon'] ?? 0),
        ], $places);
    }

    public function current(float $lat, float $lon, string $units): array
    {
        return $this->openWeather->current($lat, $lon, $units);
    }

    public function dailyForecast(float $lat, float $lon, int $days, string $units): array
    {
        return $this->openWeather->dailyForecast($lat, $lon, $days, $units);
    }
}
