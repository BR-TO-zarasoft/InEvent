<?php

namespace App\Http\Controllers;

use App\Http\Requests\WeatherCurrentRequest;
use App\Http\Requests\WeatherForecastRequest;
use App\Http\Requests\WeatherGeocodeRequest;
use App\Services\WeatherService;
use Illuminate\Http\JsonResponse;

/**
 * Weather proxy endpoints. Each action is pure happy path: the Form
 * Request validates the input and UpstreamException renders upstream
 * failures as 502, so nothing else is handled here.
 */
class WeatherController
{
    public function __construct(private readonly WeatherService $weather) {}

    public function geocode(WeatherGeocodeRequest $request): JsonResponse
    {
        return response()->json(
            $this->weather->geocode($request->searchQuery(), $request->limit()),
        );
    }

    public function current(WeatherCurrentRequest $request): JsonResponse
    {
        return response()->json(
            $this->weather->current($request->latitude(), $request->longitude(), $request->units()),
        );
    }

    public function forecast(WeatherForecastRequest $request): JsonResponse
    {
        return response()->json(
            $this->weather->dailyForecast($request->latitude(), $request->longitude(), $request->days(), $request->units()),
        );
    }
}
