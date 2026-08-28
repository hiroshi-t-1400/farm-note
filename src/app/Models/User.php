<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Admin\UserChange\UserChangeApplication;
use App\Models\WorkLog\WorkLog;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Spatie\Permission\Traits\HasRoles; // Spatie Laravel-permission

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;
    use HasRoles; //

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'login_id',
        'email',
        'password',
        'status' // active, pending, disabled
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function performedBy(): BelongsToMany
    {
        return $this->belongsToMany(WorkLog::class, 'performed_by_work_log')
                    ->withTimestamps();
    }

    public function createdBy(): HasMany
    {
        return $this->hasMany(WorkLog::class, 'id', 'created_by');
    }

    public function updatedBy(): HasMany
    {
        return $this->hasMany(WorkLog::class, 'id', 'updated_by');
    }

    public function isOwner(): bool
    {
        return $this->hasRole('owner');
    }

    public function isManager(): bool
    {
        return $this->hasRole('manager');
    }

    // 自分が出した申請の一覧
    public function changeRequests(): HasMany
    {
        return $this->hasMany(UserChangeApplication::class, 'requested_by');
    }

    public function scopeDefaultSort(Builder $query): Builder
    {
        $statusOrder = ['active', 'disabled'];

        // Laravel TESTコントローラーSQliteへの対応
        $driver = $query->getConnection()->getDriverName(); // データベースの種類を取得

        if ($driver === 'sqlite') {
            // SQLite用の CASE 式
            $cases = [];
            foreach ($statusOrder as $index => $status) {
                $order = $index + 1;
                $cases[] = "WHEN '{$status}' THEN {$order}";
            }
            $caseSql = "CASE status " . implode(' ', $cases) . " ELSE " . (count($statusOrder) + 1) . " END";

            $query->orderByRaw($caseSql);
        } else {
            // MySql MariaDb用のFILED関数
            $query->orderByRaw("FIELD(status, '" . implode("','", $statusOrder) . "')");
        }

        return $query->latest('created_at')->orderby('id', 'desc');
    }
}
