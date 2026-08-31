<?php

namespace Database\Factories\Admin\UserChange;

use App\Models\Admin\UserChange\UserChangeApplication;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserChangeApplication>
 */
class UserChangeApplicationFactory extends Factory
{
    protected static ?array $managerIds = null;
    protected static ?array $userIds = null;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'action_type' => fake()->randomElement(['create', 'update', 'disable']),
            'target_user_id' => function () {
                if (is_null(static::$userIds)) {
                    static::$userIds = User::all()->pluck('id')->toArray();
                }
                return !empty(static::$userIds)
                    ? fake()->randomElement(static::$userIds)
                    : null;
            },
            'payload' => [
                'name' => fake()->name(),
                'login_id' => fake()->unique()->userName(),
                'email' => fake()->unique()->safeEmail(),
                'password' => bcrypt('password'),
                'role' => 'worker',
            ],
            'status' => fake()->randomElement(['pending']),
            'requested_by' => function () {
                if (is_null(static::$managerIds)) {
                    static::$managerIds = User::role('manager')->pluck('id')->toArray();
                }
                return !empty(static::$managerIds)
                    ? fake()->randomElement(static::$managerIds)
                    : null;
            },
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
            'status' => UserChangeApplication::STATUS_PENDING,
        ]);
    }

    public function actionUpdate(): static
    {
        return $this->state(fn (array $attributes) => [
            'action_type' => 'update',
            'status' => UserChangeApplication::STATUS_PENDING,
        ]);
    }

    public function actionDisable(): static
    {
        return $this->state(fn (array $attributes) => [
            'action_type' => 'disable',
            'status' => UserChangeApplication::STATUS_PENDING,
        ]);
    }
}
