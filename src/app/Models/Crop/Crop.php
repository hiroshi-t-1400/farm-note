<?php

namespace App\Models\Crop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Crop extends Model
{
    //
    protected $fillable = [
        'name',
    ];

    public function cropSeason(): HasMany
    {
        return $this->hasMany(CropSeason::class);
    }
}
