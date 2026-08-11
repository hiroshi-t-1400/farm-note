<?php


namespace App\Models;
namespace App\Models\WorkLog;

use App\Models\Crop\CropSeason;
use App\Models\Material\Material;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkLog extends Model
{
    use SoftDeletes;
    //
    protected $fillable = [
        'crop_season_id',
        'created_by',
        'work_date',
        'status',
        'title',
        'content',
        'updated_by',
    ];

    public function material(): BelongsToMany
    {
        return $this->belongsToMany(Material::class, 'material_work_log')
                    ->withPivot('quantity', 'dilution_rate', 'material_amount')
                    ->withTimestamps();
    }

    public function cropSeason(): BelongsTo
    {
        return $this->belongsTo(CropSeason::class);
    }

    public function createdBy (): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function performedBy (): BelongsToMany
    {
        return $this->BelongsToMany(User::class, 'performed_by_work_log')
                    ->withTimestamps();
    }

    public function updatedBy (): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
