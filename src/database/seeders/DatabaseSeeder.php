<?php

namespace Database\Seeders;


use App\Models\Crop\Crop;
use App\Models\Crop\CropSeason;
use App\Models\Material\Material;
use App\Models\Material\MaterialCategory;
use App\Models\User;
use App\Models\UserChangeRequest;
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
        // 外部キー制約を一時的に無効化 // TESTクラスで用いられるSQliteで使用できないので廃止
        // 同時にseederの依存関係の順番を整理して正規の方法で解決しました。
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // seed == テーブルリセットであるならば php artisan migrate:fresh --seed を実行しましょう
        // テーブルのデータを初期化（truncate）
        // WorkLog::truncate();
        // User::truncate();
        // Material::truncate();
        // MaterialCategory::truncate();
        // CropSeason::truncate();
        // Field::truncate();
        // Crop::truncate();

        $this->call([
            initCropSeeder::class,
            initFieldSeeder::class,
            initCropSeasonSeeder::class,
            initMaterialCategorySeeder::class,
            initMaterialSeeder::class,
            initUserSeeder::class,
            initWorkLogSeeder::class,
            initPerformedByWorkLogSeeder::class,
            initMaterialWorkLogSeeder::class,
        ]);

        // by iseed 既存のデータベースからseederを生成するライブラリから取得したseeder
        // $this->call(WorkLogsTableSeeder::class);
        // $this->call(MaterialWorkLogTableSeeder::class);
        // $this->call(PerformedByWorkLogTableSeeder::class);

        $this->call(RolesAndPermissionsSeeder::class);
        UserChangeRequest::factory()->actionCreate()->create();

        // 外部キー制約を有効に戻す 廃止
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
