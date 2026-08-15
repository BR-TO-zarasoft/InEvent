<?php

return [
    'key' => env('OPENWEATHER_API_KEY'),

    'base_url' => env('OPENWEATHER_BASE_URL', 'https://api.openweathermap.org/data/4.0'),

    'geo_url' => env('OPENWEATHER_GEO_URL', 'https://api.openweathermap.org/geo/1.0'),

    'units' => env('OPENWEATHER_UNITS', 'metric'),

    'lang' => env('OPENWEATHER_LANG', 'pt_br'),

    'cache_ttl' => (int) env('OPENWEATHER_CACHE_TTL', 600),

    'timeout' => (int) env('OPENWEATHER_TIMEOUT', 10),

];
