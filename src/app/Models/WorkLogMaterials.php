<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkLogMaterials extends Model
{
    //
    protected $fillable = [
        'work_log_id',
        'material_id',
        'quantity',
        'dilution_rate',
    ];
}
