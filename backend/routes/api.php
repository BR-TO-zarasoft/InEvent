<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;

Route::prefix('weather')->group(function () {
    Route::get('geocode', [WeatherController::class, 'geocode']);
    Route::get('current', [WeatherController::class, 'current']);
    Route::get('forecast', [WeatherController::class, 'forecast']);
});

Route::apiResource('events', EventController::class);
