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

        $materials = [
            [
                'name' => '単管パイプ2m',
                'type_id' => 5,
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
                'type_id' => 1,
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


'materials.*.amount' => Rule::forEach(function ($value, $attribute) {
                // $attribute は 'materials.0.amount' のような文字列になるため、
                // インデックス部分を抜き出して対応する type_id を取得する
                // 例: 'materials.0.amount' -> 'materials.0.type_id'
                preg_match('/materials\.(\d+)\.amount/', $attribute, $matches);
                $index = $matches[1] ?? null;

                $typeId = $this->input("materials.{$index}.type_id");

                // type_id が 6, 7, 8 のいずれかである場合
                if (in_array($typeId, [6, 7, 8], true)) {
                    return ['required', 'numeric', 'max:10000'];
                }

                // それ以外の場合
                return ['nullable', 'numeric', 'max:10000'];
            }),
