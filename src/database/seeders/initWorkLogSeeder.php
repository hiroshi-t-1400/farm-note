<?php

namespace Database\Seeders;

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
            'performed_by' => '1',
            'work_date' => now(),
            'status' => 'completed',
            'title' => '草刈り',
            'content' => '圃場周りの草刈り、今年１回目',
            'updated_by' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
