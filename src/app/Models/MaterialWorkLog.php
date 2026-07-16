<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class MaterialWorkLog extends Pivot
{
    //
    protected $fillable = [
        'work_log_id',
        'material_id',
        'quantity',
        'dilution_rate',
        'material_amount',
    ];
}
