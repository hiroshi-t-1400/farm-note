<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WorkLog\WorkLog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class initWorkLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('work_logs')->insert([
            'crop_season_id' => '1',
            'created_by' => '1',
            // 'performed_by' => '1',
            'work_date' => now(),
            'status' => 'completed',
            'title' => '草刈り',
            'content' => '圃場周りの草刈り、今年１回目',
            'updated_by' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $users = User::factory()->count(10)->create();

        // 50件の WorkLog を作成し、上記ユーザーから割り当てる
        for ($i = 0; $i < 50; $i++) {
            $workLog = WorkLog::factory()->create([
                // 作成者をランダムに1名割り当て
                'created_by' => $users->random()->id, // ※実際の外部キーカラム名に合わせてください
                'updated_by' => $users->random()->id, // ※実際の外部キーカラム名に合わせてください
            ]);

            // 作業実施者をランダムに2名割り当て（重複しないよう random(2)）
            $workLog->performedBy()->attach($users->random(2));
        }
            // WorkLog::factory()
        //     ->count(50)
        //     ->afterMaking(function (WorkLog $workLog) {
        //         // 保存前に「作成者(createdBy)」となる User を1名生成してセット
        //         $creator = User::factory()->create();
        //         $workLog->created_by = $creator->id; // ※実際の外部キーカラム名に合わせて変更してください
        //     })
        //     ->afterMaking(function (WorkLog $workLog) {
        //         $updater = User::factory()->create();
        //         $workLog->updated_by = $updater->id; // ※実際の外部キーカラム名に合わせて変更してください
        //     })
        //     ->afterCreating(function (WorkLog $workLog) {
        //         // 保存後に「作業実施者(performedBy)」となる User を2名生成してアタッチ
        //         $performers = User::factory()->count(2)->create();
        //         $workLog->performedBy()->attach($performers);
        //     })
        //     ->create();



    }
}
