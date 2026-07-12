<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class initMaterialCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $category = [
            [
                'type' => 'pesticide',
                'label' => '農薬',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'fertilizer',
                'label' => '肥料',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'pot',
                'label' => 'ポット・鉢',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'mulch',
                'label' => 'マルチ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'prop',
                'label' => '支柱・鉄筋',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('material_categories')->insert($category);
    }
}
