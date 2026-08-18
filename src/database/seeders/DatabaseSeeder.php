<?php

namespace Database\Seeders;


use App\Models\Crop\Crop;
use App\Models\Crop\CropSeason;
use App\Models\Material\Material;
use App\Models\Material\MaterialCategory;
use App\Models\User;
use App\Models\WorkLog\Field;
use App\Models\WorkLog\WorkLog;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *  ##### このファイルに書き込んであることが
     *
     *      php artisan db:seed     (--class= ~~ なし)
     *      ^^^^^^^^^^^^^^^^^^^
     *
     *      のコマンドで実行される。
     */
    public function run(): void
    {
        // 外部キー制約を一時的に無効化
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // テーブルのデータを初期化（truncate）
        Crop::truncate();
        CropSeason::truncate();
        Field::truncate();
        Material::truncate();
        User::truncate();
        WorkLog::truncate();
        MaterialCategory::truncate();

        $this->call([
            initCropSeeder::class,
            initCropSeasonSeeder::class,
            initFieldSeeder::class,
            initMaterialCategorySeeder::class,
            initMaterialSeeder::class,
            initUserSeeder::class,
            initPerformedByWorkLogSeeder::class,
            initWorkLogSeeder::class,
            initMaterialWorkLogSeeder::class,
        ]);

        // by iseed 既存のデータベースからseederを生成するライブラリから取得したseeder
        // $this->call(WorkLogsTableSeeder::class);
        // $this->call(MaterialWorkLogTableSeeder::class);
        // $this->call(PerformedByWorkLogTableSeeder::class);

        $this->call(RolesAndPermissionsSeeder::class);

        // 外部キー制約を有効に戻す
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
