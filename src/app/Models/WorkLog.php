<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function work_logs()
    {
        return $this->belongsToMany(Material::class);
    }
}
