<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


use Illuminate\Support\Facades\DB;

class initUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('users')->insert([
            'name' => '田中 耕作',
            'login_id' => 'kosaku1010',
            // 'email' => fake()->unique()->safeEmail(),
            'email' => 'kokosaku0000@example.org',
            'email_verified_at' => now(),
            'password' => bcrypt('kosaku0000'),
            'created_at' => now(),
            'updated_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        DB::table('users')->insert([
            'name' => '田中 植子',
            'login_id' => 'ueko0808',
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('ueko0000'),
            'created_at' => now(),
            'updated_at' => now(),
            'remember_token' => Str::random(10),

        ]);
    }
}
