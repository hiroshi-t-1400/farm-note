<?php

namespace App\Models\WorkLog;

use App\Models\Crop\CropSeason;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function cropSeason(): BelongsTo
    {
        return $this->belongsTo(CropSeason::class);
    }
}

