<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\PermissionRegistrar; // Spatie Laravel-permissoin
use Spatie\Permission\Models\Permission; // Spatie Laravel-permissoin
use Spatie\Permission\Models\Role; // Spatie Laravel-permissoin


class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // キャッシュのクリア
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1.パーミッション（権限細目）の作成
        // ユーザー管理関連
        Permission::firstOrCreate(['name' => 'user-change.request']); // ユーザー情報の変更申請権限
        Permission::firstOrCreate(['name' => 'user-change.approve']); // ユーザー情報の変更承認権限

        // 圃場・作物・資材等の実務マスター関連
        Permission::firstOrCreate(['name' => 'master-data.manage']); // Manager以上がマスターを作成・編集・削除

        // 日誌関連
        Permission::firstOrCreate(['name' => 'work-logs.manage']); // 日誌の記録・編集・削除

        // 2.ロールの作成とパーミッションの割り当て
        // Worker: 日誌の記録のみ
        $roleWorker = Role::firstOrCreate(['name' => 'worker']);
        $roleWorker->givePermissionTo(['work-logs.manage']);

        // Manager: 日誌 + 実務マスター操作 + ユーザー操作申請
        $roleManager = Role::firstOrCreate(['name' => 'manager']);
        $roleManager->givePermissionTo([
            'work-logs.manage',
            'master-data.manage',
            'user-change.request',
        ]);

        // Owner: 全ての権限 + ユーザー変更承認
        $roleOwner = Role::firstOrCreate(['name' => 'owner']);
        $roleOwner->givePermissionTo([
            'work-logs.manage',
            'master-data.manage',
            'user-change.approve',
        ]);
        // $roleManager->givePermissionTo([Permission::all()]); // Ownerに全権を付与する場合
    }
}
