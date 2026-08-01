<?php

namespace App\Models\WorkLog;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    //
    /**
     *
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'address',
        'area',
        'notes',
    ];
}
