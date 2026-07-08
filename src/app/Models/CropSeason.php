<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CropSeason extends Model
{
    //
    protected $fillable = [
        'crop_id',
        'field_id',
        'variety',
        'supplier',
        'planted_area',
        'plant_count',
        'total_yield',
        'year',
    ];
}
