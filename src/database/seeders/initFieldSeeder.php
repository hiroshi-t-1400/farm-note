<?php

namespace Database\Seeders;

use App\Models\Field;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class initFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('fields')->insert([
            'name' => 'ハウス全面',
            'address' => null,
            'area' => 150,
            'notes' => '墓の横のハウス',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
