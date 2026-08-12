<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialWorkLogTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        // DB::table('material_work_log')->delete();

        DB::table('material_work_log')->insert(array (
            0 =>
            array (
                'id' => 4,
                'work_log_id' => 1,
                'material_id' => 1,
                'quantity' => '5本',
                'dilution_rate' => NULL,
                'material_amount' => NULL,
                'created_at' => '2026-07-30 07:04:05',
                'updated_at' => '2026-07-30 07:04:05',
            ),
            1 =>
            array (
                'id' => 5,
                'work_log_id' => 2,
                'material_id' => 2,
                'quantity' => '150L',
                'dilution_rate' => '3000',
                'material_amount' => '500ml',
                'created_at' => '2026-08-01 23:50:06',
                'updated_at' => '2026-08-01 23:50:06',
            ),
            2 =>
            array (
                'id' => 6,
                'work_log_id' => 11,
                'material_id' => 2,
                'quantity' => '150L',
                'dilution_rate' => '3000',
                'material_amount' => '50ml',
                'created_at' => '2026-08-02 03:36:17',
                'updated_at' => '2026-08-02 03:36:17',
            ),
            3 =>
            array (
                'id' => 7,
                'work_log_id' => 13,
                'material_id' => 1,
                'quantity' => '10本',
                'dilution_rate' => NULL,
                'material_amount' => NULL,
                'created_at' => '2026-08-02 03:37:25',
                'updated_at' => '2026-08-02 03:37:25',
            ),
        ));


    }
}
