<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\WorkLog\WorkLog;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'login_id',
        // 'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        // 'remember_token',
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

<<<<<<< Updated upstream
    public function workLogs (): BelongsToMany
=======
    public function performedBy(): BelongsToMany
>>>>>>> Stashed changes
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
}
