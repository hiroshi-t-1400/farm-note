<?php

namespace Database\Seeders;

use App\Models\Crop;
use App\Models\CropSeason;
use App\Models\Field;
use App\Models\Material;
use App\Models\User;
use App\Models\WorkLog;
use App\Models\MaterialWorkLog;

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
        // MaterialWorkLog::truncate();

        $this->call([
            initCropSeeder::class,
            initCropSeasonSeeder::class,
            initFieldSeeder::class,
            initMaterialSeeder::class,
            initUserSeeder::class,
            initWorkLogSeeder::class,
            initMaterialWorkLogSeeder::class,
        ]);

        // 外部キー制約を有効に戻す
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }
}
