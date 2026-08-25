<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserChangeRequest extends Model
{
    use HasFactory;

    // 状態定数
    public const STATUS_PENDING = 'pending';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_DISABLED = 'disabled';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'action_type',
        'target_user_id',
        'payload',
        'status',
        'requested_by',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];

    protected $casts = [
        'payload' => 'array',
        'approved_at' => 'datetime',
    ];

    protected $with = [
        'targetUser',
        'requester',
    ];

    public function targetUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }


    /**
     * OwnerがManagerからの申請を承認した際の反映ロジック
     */
    public function approve(User $approver): void
    {
        // 状態チェック、承認待ちではないときのフォールバック
        if ($this->status !== self::STATUS_PENDING) {
            throw new \LogicException('申請中以外のデータは承認できません。');
        }

        DB::transaction(function () use ($approver) {
            if ($this->action_type === 'create') {
                // 新規ユーザー作成
                $user = User::create([
                    'name' => $this->payload['name'],
                    'email' => $this->payload['email'],
                    'login_id' => $this->payload['login_id'],
                    'password' => $this->payload['password'] ?? 'default_password',
                    'status' => self::STATUS_ACTIVE,
                ]);

                // Spatieによるロール割り当て(owner, manager, worker)
                $user->assignRole($this->payload['role']);

            } elseif ($this->action_type === 'update') {
                $user = $this->targetUser;

                // ロール変更が含まれる場合は同期を行う
                if (isset($this->payload['role'])) {
                    $user->syncRoles([$this->payload['role']]);
                }

                // ロール以外の属性を更新
                $user->update(collect($this->payload)->except('role')->toArray());

            } elseif ($this->action_type === 'delete') {
                $user = $this->targetUser;
                $user->update(['status' => self::STATUS_DISABLED]);
            }

            // 申請ステータスを承認済みに更新
            $this->update([
                'status' => self::STATUS_APPROVED,
                'approved_by' => $approver->id,
                'approved_at' => now(),
            ]);
        });
    }

    public function reject(User $approver, ?string $reason = null): void
    {
        // 状態チェック、承認待ちではないときのフォールバック
        if ($this->status !== self::STATUS_PENDING) {
            throw new \LogicException('申請中以外のデータは承認できません。');
        }

        $this->update([
            'status' => self::STATUS_REJECTED,
            'approved_by' => $approver->id,
            'rejection_reason' => $reason,
        ]);
    }

    public function scopeDefaultSort(Builder $query): Builder
    {
        $statusOrder = ['rejected', 'pending', 'approved', 'active', 'disabled'];

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

        return $query->latest('updated_at')->orderby('id', 'desc');
    }
}
