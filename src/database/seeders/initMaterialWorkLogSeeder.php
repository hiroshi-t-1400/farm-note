<?php

namespace Database\Seeders;

use App\Models\MaterialWorkLog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class initMaterialWorkLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('material_work_log')->delete();

        DB::table('material_work_log')->insert([
            'work_log_id' => 1,
            'material_id' => 1,
            'quantity' => '5本',
            'dilution_rate' => null,
            'material_amount' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
