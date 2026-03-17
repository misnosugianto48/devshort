<?php

namespace Database\Factories;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription>
 */
class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'plan_id' => Plan::factory(),
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
            'trial_ends_at' => null,
        ];
    }

    /**
     * Set subscription as canceled.
     */
    public function canceled(): static
    {
        return $this->state(fn () => [
            'status' => 'canceled',
        ]);
    }

    /**
     * Set subscription as expired.
     */
    public function expired(): static
    {
        return $this->state(fn () => [
            'status' => 'expired',
            'ends_at' => now()->subDay(),
        ]);
    }

    /**
     * Set subscription on trial.
     */
    public function onTrial(): static
    {
        return $this->state(fn () => [
            'trial_ends_at' => now()->addDays(14),
        ]);
    }
}
