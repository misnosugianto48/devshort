<?php

namespace Database\Seeders;

use App\Models\Click;
use App\Models\Link;
use App\Models\User;
use Illuminate\Database\Seeder;

class LinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, check if a default user exists, or create one
        $user = User::firstOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        // Create 20 links for this user
        $links = Link::factory()->count(20)->create([
            'user_id' => $user->id,
        ]);

        // Generate clicks for these links
        foreach ($links as $link) {
            $clicksPerLink = rand(0, 50);

            if ($clicksPerLink > 0) {
                Click::factory()->count($clicksPerLink)->create([
                    'link_id' => $link->id,
                ]);

                // Update the denormalized clicks_count column
                $link->update([
                    'clicks_count' => $clicksPerLink,
                ]);
            }
        }
    }
}
