<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserChangeRequest;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<UserChangeRequest>
 */
class UserChangeRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'action_type' => fake()->randomElement(['create', 'update', 'delete']),
            'target_user_id' => null,
            'payload' => [
                'name' => fake()->name(),
                'login_id' => fake()->unique()->userName(),
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'role' => 'worker',
            ],
            'status' => fake()->randomElement(['pending', 'active', 'disabled', 'approved', 'rejected']),
            'requested_by' => User::factory(),
            'approved_by' => null,
            'approved_at' => null,
            'rejection_reason' => null,
        ];
    }

    //
    public function actionCreate(): static
    {
        return $this->state(fn (array $attributes) => [
            'target_user_id' => null,
            'action_type' => 'create',
            'status' => UserChangeRequest::STATUS_PENDING,
        ]);
    }
}
