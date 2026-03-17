<?php

namespace Database\Factories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plan>
 */
class PlanFactory extends Factory
{
    protected $model = Plan::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement(['Starter', 'Growth', 'Agency', 'Enterprise']),
            'slug' => fn (array $attributes) => \Illuminate\Support\Str::slug($attributes['name']),
            'description' => fake()->sentence(),
            'price_monthly' => fake()->randomFloat(2, 0, 99),
            'price_yearly' => fake()->randomFloat(2, 0, 999),
            'max_links' => fake()->randomElement([25, 100, 500, -1]),
            'max_domains' => fake()->randomElement([0, 1, 5, -1]),
            'features' => [
                'custom_alias' => fake()->boolean(),
                'password_protection' => fake()->boolean(),
                'link_expiration' => fake()->boolean(),
                'custom_domain' => fake()->boolean(),
                'api_access' => fake()->boolean(),
                'advanced_analytics' => fake()->boolean(),
            ],
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }

    /**
     * Create a free plan.
     */
    public function free(): static
    {
        return $this->state(fn () => [
            'name' => 'Free',
            'slug' => 'free',
            'price_monthly' => 0,
            'price_yearly' => 0,
            'max_links' => 25,
            'max_domains' => 0,
            'features' => [
                'custom_alias' => false,
                'password_protection' => false,
                'link_expiration' => true,
                'custom_domain' => false,
                'api_access' => false,
                'advanced_analytics' => false,
            ],
            'sort_order' => 0,
        ]);
    }

    /**
     * Create a pro plan.
     */
    public function pro(): static
    {
        return $this->state(fn () => [
            'name' => 'Pro',
            'slug' => 'pro',
            'price_monthly' => 9.00,
            'price_yearly' => 90.00,
            'max_links' => 500,
            'max_domains' => 1,
            'features' => [
                'custom_alias' => true,
                'password_protection' => true,
                'link_expiration' => true,
                'custom_domain' => true,
                'api_access' => true,
                'advanced_analytics' => true,
            ],
            'sort_order' => 1,
        ]);
    }

    /**
     * Create a business plan.
     */
    public function business(): static
    {
        return $this->state(fn () => [
            'name' => 'Business',
            'slug' => 'business',
            'price_monthly' => 29.00,
            'price_yearly' => 290.00,
            'max_links' => -1,
            'max_domains' => -1,
            'features' => [
                'custom_alias' => true,
                'password_protection' => true,
                'link_expiration' => true,
                'custom_domain' => true,
                'api_access' => true,
                'advanced_analytics' => true,
            ],
            'sort_order' => 2,
        ]);
    }
}
