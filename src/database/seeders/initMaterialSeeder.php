<?php

namespace Database\Seeders;

use App\Models\Material;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class initMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 外部キー制約を一時的に無効化
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // テーブルのデータを初期化（truncate）
        Material::truncate();

        // 外部キー制約を有効に戻す
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $materials = [
            [
                'name' => '単管パイプ2m',
                'type' => 'prop',
                'default_dilution_rate' => null,
                'standard_spray_volume' => null,
                'unit' => '本',
                'manufacturer' => 'ジュンテンドー',
                'is_reusable' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ストロビーフロアブル',
                'type' => 'pesticide',
                'default_dilution_rate' => 3000,
                'standard_spray_volume' => 150,
                'unit' => 'ml',
                'manufacturer' => '日本曹達株式会社',
                'is_reusable' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Material::create($materials);
        DB::table('materials')->insert($materials);


        // DB::table('materials')->insert([
        //     'name' => 'ストロビーフロアブル',
        //     'type' => 'pesticide',
        //     'default_dilution_rate' => 3000,
        //     'standard_spray_volume' => 150,
        //     'unit' => 'ml',
        //     'manufacturer' => '日本曹達株式会社',
        //     'is_reusable' => 'false',
        //     'created_at' => new DateTime(),
        //     'updated_at' => new DateTime(),
        // ]);
        // DB::table('materials')->insert([
        //     'name' => '単管パイプ2m',
        //     'type' => 'prop',
        //     'default_dilution_rate' => null,
        //     'standard_spray_volume' => null,
        //     'unit' => '本',
        //     'manufacturer' => 'ジュンテンドー',
        //     'is_reusable' => true,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        // DB::table('materials')->insert([
        //     'name' => 'ストロビーフロアブル',
        //     'type' => 'pesticide',
        //     'default_dilution_rate' => 3000,
        //     'standard_spray_volume' => 150,
        //     'unit' => 'ml',
        //     'manufacturer' => '日本曹達株式会社',
        //     'is_reusable' => 'false',
        //     'created_at' => new DateTime(),
        //     'updated_at' => new DateTime(),
        // ]);
    }
}
