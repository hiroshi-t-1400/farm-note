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
        // //
        // DB::table('work_logs')->insert([
        //     'crop_season_id' => '1',
        //     'created_by' => '1',
        //     // 'performed_by' => '1',
        //     'work_date' => now(),
        //     'status' => 'completed',
        //     'title' => '草刈り',
        //     'content' => '圃場周りの草刈り、今年１回目',
        //     'updated_by' => '1',
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        $users = User::factory()->count(20)->create();
        foreach($users as $user) {
            $user->assignRole('worker');
        };

        // 100件の WorkLog を作成し、上記ユーザーから割り当てる
        for ($i = 0; $i < 100; $i++) {
            $workLog = WorkLog::factory()->create([
                // 作成者をランダムに1名割り当て
                'created_by' => $users->random()->id,
                'updated_by' => $users->random()->id,
            ]);

            // 作業実施者をランダムに2名割り当て（重複しないよう random(2)）
            $workLog->performedBy()->attach($users->random(2));
        }
    }
}
