<?php

namespace Database\Factories\WorkLog; // ★名前空間もディレクトリに合わせる

use App\Models\Model;
use App\Models\WorkLog\WorkLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Model>
 */
class WorkLogFactory extends Factory
{
    protected $model = WorkLog::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'crop_season_id' => fake()->numberBetween(1,2),
            'created_by' => fake()->numberBetween(1,2),
            'work_date' => fake()->dateTimeBetween('-1 year', '+1 year'),
            'status' => fake()->randomElement(['plan', 'complete']),
            'title' => fake()->realText(10),
            'content' => fake()->realTextBetween(50, 190, 2),
            'updated_by' => fake()->dateTimeBetween('-1 year', '+1 year')
        ];
    }
}
