<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class WorkLog extends Model
{
    //
    protected $fillable = [
        'crop_season_id',
        'created_by',
        // 'performed_by',
        'work_date',
        'status',
        'title',
        'content',
        'updated_by',
    ];

    public function materials(): BelongsToMany
    {
        return $this->belongsToMany(Material::class, 'material_work_log')
                    ->withPivot('quantity', 'dilution_rate', 'material_amount')
                    ->withTimestamps();
    }


    public function createdBy (): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'created_by');
    }

    public function performedBy (): BelongsToMany
    {
        return $this->BelongsToMany(User::class, 'performed_by_work_log')
                    ->withTimestamps();
    }

    public function updatedBy (): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'updated_by');
    }
}
