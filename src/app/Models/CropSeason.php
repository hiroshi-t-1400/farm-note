<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function crops(): HasOne
    {
        return $this->hasOne(Crop::class, 'id', 'crop_id');
    }
}

