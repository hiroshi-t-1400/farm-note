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

<<<<<<< Updated upstream
    public function cropSeasons (): BelongsTo
=======
    public function cropSeason(): HasMany
>>>>>>> Stashed changes
    {
        return $this->hasMany(CropSeason::class);
    }
}
