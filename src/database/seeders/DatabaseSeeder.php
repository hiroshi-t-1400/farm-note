<?php

namespace Database\Seeders;

use App\Models\Admin\UserChange\UserChangeApplication;
use App\Models\Crop\Crop;
use App\Models\Crop\CropSeason;
use App\Models\Material\Material;
use App\Models\Material\MaterialCategory;
use App\Models\User;
use App\Models\WorkLog\Field;
use App\Models\WorkLog\WorkLog;
use Database\Seeders\Admin\UserChange\UserChangeApplicationSeeder;
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
        $this->call(RolesAndPermissionsSeeder::class);

        $this->call([
            initCropSeeder::class,
            initFieldSeeder::class,
            initCropSeasonSeeder::class,
            initMaterialCategorySeeder::class,
            initMaterialSeeder::class,
            initUserSeeder::class,
            initWorkLogSeeder::class,
            // initPerformedByWorkLogSeeder::class,
            initMaterialWorkLogSeeder::class,
            UserChangeApplicationSeeder::class,
        ]);
    }
        // by iseed 既存のデータベースからseederを生成するライブラリから取得したseeder
        // $this->call(WorkLogsTableSeeder::class);
        // $this->call(MaterialWorkLogTableSeeder::class);
        // $this->call(PerformedByWorkLogTableSeeder::class);
}
