<?php

namespace Database\Seeders\Admin\UserChange;

use App\Models\Admin\UserChange\UserChangeApplication;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserChangeApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserChangeApplication::factory()->actionCreate()->count(20)->create();
        UserChangeApplication::factory()->actionCreate()->count(3)->create([
            'status' => UserChangeApplication::STATUS_REJECTED,
            'rejection_reason' => fake()->realTextBetween(20, 100, 2),
            'approved_by' => 1,
            'approved_at' => fake()->dateTimeBetween('-1 year', '+1 year'),
        ]);

        UserChangeApplication::factory()->actionCreate()->count(10)->create([
            'status' => UserChangeApplication::STATUS_APPROVED,
            'approved_by' => 1,
            'approved_at' => fake()->dateTimeBetween('-1 year', '+1 year'),
        ]);

        UserChangeApplication::factory()->actionUpdate()->count(10)->create([
            'status' => UserChangeApplication::STATUS_REJECTED,
            'rejection_reason' => fake()->realTextBetween(20, 100, 2),
            'approved_by' => 1,
            'approved_at' => fake()->dateTimeBetween('-1 year', '+1 year'),
        ]);

        UserChangeApplication::factory()->actionDisable()->count(10)->create([
            'status' => UserChangeApplication::STATUS_APPROVED,
            'approved_by' => 1,
            'approved_at' => fake()->dateTimeBetween('-1 year', '+1 year'),
        ]);
    }
}
