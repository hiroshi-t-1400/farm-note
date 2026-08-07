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

<<<<<<< Updated upstream
    public function crops(): HasOne
=======
    public function crop(): BelongsTo
>>>>>>> Stashed changes
    {
        return $this->belongsTo(Crop::class);
    }

<<<<<<< Updated upstream
    public function fields(): HasOne
=======
    public function field(): BelongsTo
>>>>>>> Stashed changes
    {
        return $this->belongsTo(Field::class);
    }

    public function workLogs(): HasMany
    {
        return $this->HasMany(WorkLog::class, 'crop_season_id');
    }
}

