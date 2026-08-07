<?php

namespace App\Models\Crop;

use App\Models\WorkLog\WorkLog;
use App\Models\Crop\Crop;
use App\Models\WorkLog\Field;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CropSeason extends Model
{
    //
    protected $fillable = [
        'crop_id',
        'field_id',
        'variety',
        'supplier',
        'planted_area',
        'plant_count',
        'total_yield',
        'year',
    ];

    public function crop(): BelongsTo
    {
        return $this->belongsTo(Crop::class);
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class);
    }

    public function workLogs(): HasMany
    {
        return $this->HasMany(WorkLog::class, 'crop_season_id');
    }
}

