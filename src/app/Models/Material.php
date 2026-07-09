<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    //
    protected $fillable = [
        'name',
        'type',
        'default_dilution_rate',
        'standard_spray_volume',
        'unit',
        'manufacturer',
        'is_reusable',
    ];
}
