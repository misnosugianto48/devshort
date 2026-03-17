<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Seed the default subscription plans.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'slug' => 'free',
                'description' => 'Mulai gratis dengan fitur dasar pemendek tautan.',
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
                'is_active' => true,
                'sort_order' => 0,
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'description' => 'Untuk profesional yang membutuhkan fitur lengkap dan analitik mendalam.',
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
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'description' => 'Solusi lengkap untuk tim dan bisnis skala besar dengan fitur tanpa batas.',
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
                'is_active' => true,
                'sort_order' => 2,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::query()->updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
