<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PerformedByWorkLogTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('performed_by_work_log')->delete();

        DB::table('performed_by_work_log')->insert(array (
            0 =>
            array (
                'id' => 4,
                'work_log_id' => 1,
                'user_id' => 1,
                'created_at' => '2026-07-30 07:04:05',
                'updated_at' => '2026-07-30 07:04:05',
            ),
            1 =>
            array (
                'id' => 5,
                'work_log_id' => 2,
                'user_id' => 1,
                'created_at' => '2026-08-01 23:50:06',
                'updated_at' => '2026-08-01 23:50:06',
            ),
            2 =>
            array (
                'id' => 6,
                'work_log_id' => 3,
                'user_id' => 2,
                'created_at' => '2026-08-02 03:29:10',
                'updated_at' => '2026-08-02 03:29:10',
            ),
            3 =>
            array (
                'id' => 7,
                'work_log_id' => 4,
                'user_id' => 1,
                'created_at' => '2026-08-02 03:29:38',
                'updated_at' => '2026-08-02 03:29:38',
            ),
            4 =>
            array (
                'id' => 8,
                'work_log_id' => 5,
                'user_id' => 1,
                'created_at' => '2026-08-02 03:30:51',
                'updated_at' => '2026-08-02 03:30:51',
            ),
            5 =>
            array (
                'id' => 9,
                'work_log_id' => 6,
                'user_id' => 2,
                'created_at' => '2026-08-02 03:32:53',
                'updated_at' => '2026-08-02 03:32:53',
            ),
            6 =>
            array (
                'id' => 10,
                'work_log_id' => 7,
                'user_id' => 1,
                'created_at' => '2026-08-02 03:33:30',
                'updated_at' => '2026-08-02 03:33:30',
            ),
            7 =>
            array (
                'id' => 11,
                'work_log_id' => 8,
                'user_id' => 1,
                'created_at' => '2026-08-02 03:34:17',
                'updated_at' => '2026-08-02 03:34:17',
            ),
            8 =>
            array (
                'id' => 12,
                'work_log_id' => 9,
                'user_id' => 1,
                'created_at' => '2026-08-02 03:35:03',
                'updated_at' => '2026-08-02 03:35:03',
            ),
            9 =>
            array (
                'id' => 13,
                'work_log_id' => 10,
                'user_id' => 2,
                'created_at' => '2026-08-02 03:35:22',
                'updated_at' => '2026-08-02 03:35:22',
            ),
            10 =>
            array (
                'id' => 14,
                'work_log_id' => 11,
                'user_id' => 1,
                'created_at' => '2026-08-02 03:36:17',
                'updated_at' => '2026-08-02 03:36:17',
            ),
            11 =>
            array (
                'id' => 15,
                'work_log_id' => 12,
                'user_id' => 1,
                'created_at' => '2026-08-02 03:36:42',
                'updated_at' => '2026-08-02 03:36:42',
            ),
            12 =>
            array (
                'id' => 16,
                'work_log_id' => 13,
                'user_id' => 1,
                'created_at' => '2026-08-02 03:37:25',
                'updated_at' => '2026-08-02 03:37:25',
            ),
        ));


    }
}
