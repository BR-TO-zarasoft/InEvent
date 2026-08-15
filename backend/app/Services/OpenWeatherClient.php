<?php

namespace App\Services;

use App\Exceptions\UpstreamException;
use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class OpenWeatherClient
{
    public function geocode(string $query, int $limit): array
    {
        $places = $this->cached("geocode.{$query}.{$limit}", fn (): array => $this->get(
            config('openweather.geo_url').'/direct',
            ['q' => $query, 'limit' => $limit],
        ));

        return array_values(array_filter($places, 'is_array'));
    }

    public function current(float $lat, float $lon, string $units): array
    {
        return $this->cached("current.{$lat}.{$lon}.{$units}", fn (): array => $this->get(
            config('openweather.base_url').'/onecall/current',
            [
                'lat' => $lat,
                'lon' => $lon,
                'units' => $units,
                'lang' => config('openweather.lang'),
            ],
        ));
    }

    public function dailyForecast(float $lat, float $lon, int $days, string $units): array
    {
        $days = max(1, min(10, $days));

        return $this->cached("daily.{$lat}.{$lon}.{$days}.{$units}", fn (): array => $this->get(
            config('openweather.base_url').'/onecall/timeline/1day',
            [
                'lat' => $lat,
                'lon' => $lon,
                'cnt' => $days,
                'units' => $units,
                'lang' => config('openweather.lang'),
            ],
        ));
    }

    private function get(string $url, array $query): array
    {
        $response = Http::timeout(config('openweather.timeout'))
            ->acceptJson()
            ->get($url, [...$query, 'appid' => config('openweather.key')]);

        if ($response->failed()) {
            throw UpstreamException::fromStatus('OpenWeather', $response->status());
        }

        return $response->json() ?? [];
    }

    private function cached(string $key, Closure $fetch): array
    {
        return Cache::remember('openweather.'.$key, config('openweather.cache_ttl'), $fetch);
    }
}
