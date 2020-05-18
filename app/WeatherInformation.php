<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WeatherInformation extends Model
{
    protected $table = 'weather_informations';
    protected $fillable = [
        'location','time','weather_status','temperature','wind_direction','wind_speed','wave_height','wave_direction','wave_period','humidity'
    ];
}
