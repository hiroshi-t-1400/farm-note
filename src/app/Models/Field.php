<?php

namespace App\Models;

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
