<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use Spatie\Permission\Models\Role;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class initUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // role設定を取得
        // $ownerRole   = Role::findByName('owner');
        // $managerRole = Role::findByName('manager');

        $ownerRole   = Role::firstOrCreate(['name' => 'owner']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $wokerRole = Role::firstOrCreate(['name' => 'worker']); // 一般作業者ロールも合わせて用意

        // オーナーroleのユーザー
        $owner = User::create([
            'name' => 'メインオーナー',
            'login_id' => 'owner0000',
            'email' => 'owner@example.org',
            'email_verified_at' => now(),
            'password' => Hash::make('owner12345'),
            'created_at' => now(),
            'updated_at' => now(),
            'status' => 'active',
            'remember_token' => Str::random(10),
        ]);
        $owner->assignRole($ownerRole);

        // Manager roleのユーザー２人
        $manager01 = User::create([
            'name' => '田中 耕作',
            'login_id' => 'kosaku1010',
            'email' => 'kokosaku0000@example.org',
            'email_verified_at' => now(),
            'password' => Hash::make('kosaku0000'),
            'created_at' => now(),
            'updated_at' => now(),
            'status' => 'active',
            'remember_token' => Str::random(10),
        ]);
        $manager01->assignRole($managerRole);

        $manager02 = User::create([
            'name' => '田中 植子',
            'login_id' => 'ueko0808',
            'email' => 'ueko0808@example.org',
            'email_verified_at' => now(),
            'password' => Hash::make('ueko012345'),
            'created_at' => now(),
            'updated_at' => now(),
            'status' => 'active',
            'remember_token' => Str::random(10),
        ]);
        $manager02->assignRole($managerRole);

        $worker = User::create([
            'name' => '一般 ユーザー',
            'login_id' => 'worker12345',
            'email' => 'worker12345@example.org',
            'email_verified_at' => now(),
            'password' => Hash::make('worker12345'),
            'created_at' => now(),
            'updated_at' => now(),
            'status' => 'active',
            'remember_token' => Str::random(10),
        ]);
        $manager02->assignRole($managerRole);
    }
}
