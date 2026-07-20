<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Material extends Model
{
    //
    protected $fillable = [
        'name',
        'type_id',
        'default_dilution_rate',
        'standard_spray_volume',
        'unit',
        'manufacturer',
        'is_reusable',
    ];

    public function workLogs(): BelongsToMany
    {
        return $this->belongsToMany(WorkLog::class, 'material_work_log')
                    ->withPivot('quantity', 'dilution_rate', 'material_amount')
                    ->withTimestamps();
                    // ->using(MaterialWorkLog::class);
    }

    public function materialCategories(): HasOne
    {
        return $this->hasOne(MaterialCategory::class, 'id', 'type_id');
    }
}
