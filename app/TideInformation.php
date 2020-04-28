<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TideInformation extends Model
{
    protected $table = 'tide_informations';
    protected $fillable = [
        'id', 'location','date','hide_tide','created_at','updated_at'
    ];
}
