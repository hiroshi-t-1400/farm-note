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
        ]);

        // by iseed 既存のデータベースからseederを生成するライブラリから取得したseeder
        // $this->call(WorkLogsTableSeeder::class);
        // $this->call(MaterialWorkLogTableSeeder::class);
        // $this->call(PerformedByWorkLogTableSeeder::class);

        UserChangeRequest::factory()->actionCreate()->count(20)->create();

        UserChangeRequest::factory()->count(5)->create([
            'action_type' => 'create',
            'status' => 'rejected',
        ]);

        UserChangeRequest::factory()->count(5)->create([
            'action_type' => 'create',
            'status' => 'approved',
        ]);

        UserChangeRequest::factory()->count(5)->create([
            'action_type' => 'create',
            'status' => 'active',
        ]);

        UserChangeRequest::factory()->count(5)->create([
            'action_type' => 'create',
            'status' => 'disabled',
        ]);

        UserChangeRequest::factory()->count(5)->create([
                'action_type' => 'update',
                'status' => 'pending',
        ]);

        UserChangeRequest::factory()->count(5)->create([
            'action_type' => 'update',
            'status' => 'rejected',
        ]);
        UserChangeRequest::factory()->count(5)->create([
            'action_type' => 'delete',
            'status' => 'pending',
        ]);

        UserChangeRequest::factory()->count(5)->create([
            'action_type' => 'delete',
            'status' => 'rejected',
        ]);

        UserChangeRequest::factory()->count(5)->create([
            'action_type' => 'create',
            'status' => 'approved',
        ]);

        // 外部キー制約を有効に戻す 廃止
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
