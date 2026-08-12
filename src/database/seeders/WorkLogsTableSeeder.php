<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkLogsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        // DB::table('work_logs')->delete();

        DB::table('work_logs')->insert(array (
            0 =>
            array (
                'id' => 1,
                'crop_season_id' => 1,
                'created_by' => 1,
                'work_date' => '2026-07-30 07:04:05',
                'status' => 'completed',
                'title' => '草刈り',
                'content' => '圃場周りの草刈り、今年１回目',
                'updated_by' => 1,
                'created_at' => '2026-07-30 07:04:05',
                'updated_at' => '2026-07-30 07:04:05',
            ),
            1 =>
            array (
                'id' => 2,
                'crop_season_id' => 1,
                'created_by' => 1,
                'work_date' => '2026-08-02 00:00:00',
                'status' => 'completed',
                'title' => '防除２回目',
                'content' => 'トマトへ２回目の防除',
                'updated_by' => NULL,
                'created_at' => '2026-08-01 23:50:06',
                'updated_at' => '2026-08-01 23:50:06',
            ),
            2 =>
            array (
                'id' => 3,
                'crop_season_id' => 1,
                'created_by' => 1,
                'work_date' => '2026-08-05 00:00:00',
                'status' => 'plan',
                'title' => '収穫',
                'content' => 'トマトの収穫を始める。以後毎朝確認して収穫する',
                'updated_by' => NULL,
                'created_at' => '2026-08-02 03:29:10',
                'updated_at' => '2026-08-02 03:29:10',
            ),
            3 =>
            array (
                'id' => 4,
                'crop_season_id' => 1,
                'created_by' => 1,
                'work_date' => '2026-08-03 00:00:00',
                'status' => 'plan',
                'title' => '草刈り',
                'content' => '草が伸びているので草刈りします。',
                'updated_by' => NULL,
                'created_at' => '2026-08-02 03:29:38',
                'updated_at' => '2026-08-02 03:29:38',
            ),
            4 =>
            array (
                'id' => 5,
                'crop_season_id' => 1,
                'created_by' => 1,
                'work_date' => '2026-03-24 00:00:00',
                'status' => 'completed',
                'title' => '種まき',
                'content' => '種まきポットを使って一袋巻きました',
                'updated_by' => NULL,
                'created_at' => '2026-08-02 03:30:51',
                'updated_at' => '2026-08-02 03:30:51',
            ),
            5 =>
            array (
                'id' => 6,
                'crop_season_id' => 1,
                'created_by' => 1,
                'work_date' => '2026-03-31 00:00:00',
                'status' => 'completed',
                'title' => '苗の確認',
                'content' => '芽が出ていた。もう少し大きくなったら育苗ポットに移植します',
                'updated_by' => NULL,
                'created_at' => '2026-08-02 03:32:53',
                'updated_at' => '2026-08-02 03:32:53',
            ),
            6 =>
            array (
                'id' => 7,
                'crop_season_id' => 1,
                'created_by' => 1,
                'work_date' => '2026-04-15 00:00:00',
                'status' => 'completed',
                'title' => '育苗ポットに移植',
                'content' => '育苗ポットに移植しました。',
                'updated_by' => NULL,
                'created_at' => '2026-08-02 03:33:30',
                'updated_at' => '2026-08-02 03:33:30',
            ),
            7 =>
            array (
                'id' => 8,
                'crop_season_id' => 1,
                'created_by' => 1,
                'work_date' => '2026-05-05 00:00:00',
                'status' => 'completed',
                'title' => '圃場整備',
                'content' => '圃場へ元肥をすきこみました。草刈りもしています。',
                'updated_by' => NULL,
                'created_at' => '2026-08-02 03:34:17',
                'updated_at' => '2026-08-02 03:34:17',
            ),
            8 =>
            array (
                'id' => 9,
                'crop_season_id' => 1,
                'created_by' => 1,
                'work_date' => '2026-05-29 00:00:00',
                'status' => 'completed',
                'title' => '囲いをしました',
                'content' => 'サル被害あったそうなので囲いを直しました',
                'updated_by' => NULL,
                'created_at' => '2026-08-02 03:35:03',
                'updated_at' => '2026-08-02 03:35:03',
            ),
            9 =>
            array (
                'id' => 10,
                'crop_season_id' => 1,
                'created_by' => 1,
                'work_date' => '2026-06-16 00:00:00',
                'status' => 'completed',
                'title' => '草刈り',
                'content' => '草刈りをしています。',
                'updated_by' => NULL,
                'created_at' => '2026-08-02 03:35:22',
                'updated_at' => '2026-08-02 03:35:22',
            ),
            10 =>
            array (
                'id' => 11,
                'crop_season_id' => 1,
                'created_by' => 1,
                'work_date' => '2026-06-30 00:00:00',
                'status' => 'completed',
                'title' => '防除１回目',
                'content' => '１回目の防除をしました。',
                'updated_by' => NULL,
                'created_at' => '2026-08-02 03:36:17',
                'updated_at' => '2026-08-02 03:36:17',
            ),
            11 =>
            array (
                'id' => 12,
                'crop_season_id' => 1,
                'created_by' => 1,
                'work_date' => '2026-07-03 00:00:00',
                'status' => 'completed',
                'title' => '草刈り',
                'content' => '草刈りをしました。熱中症に気を付けます',
                'updated_by' => NULL,
                'created_at' => '2026-08-02 03:36:42',
                'updated_at' => '2026-08-02 03:36:42',
            ),
            12 =>
            array (
                'id' => 13,
                'crop_season_id' => 1,
                'created_by' => 1,
                'work_date' => '2026-07-24 00:00:00',
                'status' => 'completed',
                'title' => '支柱建て',
                'content' => 'つるの勢いがすごいので鉄筋で支柱たてました',
                'updated_by' => NULL,
                'created_at' => '2026-08-02 03:37:25',
                'updated_at' => '2026-08-02 03:37:25',
            ),
        ));


    }
}
