<?php

namespace Database\Seeders;

use App\Models\CropSeason;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class initCropSeasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('crop_seasons')->insert([
            'crop_id' => 1,
            'field_id' => 1,
            'variety' => '桃太郎ブライト',
            'supplier' => 'タキイ種苗',
            'planted_area' => 300,
            'plant_count' => 5,
            'total_yield' => null,
            'year' => '2026',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
