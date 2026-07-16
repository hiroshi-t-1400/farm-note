<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WorkLog extends Model
{
    //
    protected $fillable = [
        'crop_season_id',
        'created_by',
        'performed_by',
        'work_date',
        'status',
        'title',
        'content',
        'updated_by',
    ];

    public function materials(): BelongsToMany
    {
        return $this->belongsToMany(Material::class)
                    ->withPivot('quantity', 'dilution_rate', 'meterial_amount')
                    ->withTimestamps()
                    ->using(MaterialWorkLog::class);
    }

    public function users_created (): HasOne
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function users_performed (): HasOne
    {
        return $this->hasOne(User::class, 'id', 'performed_by');
    }

    public function users_updated (): HasOne
    {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }
}
