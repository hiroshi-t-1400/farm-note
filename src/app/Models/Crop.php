<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Crop extends Model
{
    //
    protected $fillable = [
        'name',
    ];

    public function cropSeasons (): BelongsTo
    {
        return $this->belongsTo(CropSeason::class);
    }
}
