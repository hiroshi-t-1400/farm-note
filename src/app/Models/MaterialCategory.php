<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialCategory extends Model
{
    //
    protected $fillable = [
        'type',
        'label',
    ];

    public function materials (): BelongsTo
    {
        return $this->belongsTo(Material::class, 'type_id');
    }
}

