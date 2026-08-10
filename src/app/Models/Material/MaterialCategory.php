<?php

namespace App\Models\Material;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaterialCategory extends Model
{
    //
    protected $fillable = [
        'type',
        'label',
    ];

    public function material(): HasMany
    {
        return $this->hasMany(Material::class, 'id','type_id');
    }
}

