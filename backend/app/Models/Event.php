<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'name',
        'location',
        'lat',
        'lon',
        'start_date',
        'end_date',
        'description',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'lat' => 'float',
            'lon' => 'float',
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];
    }
}
