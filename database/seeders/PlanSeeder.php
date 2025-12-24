<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Silver',
                'slug' => 'silver',
                'description' => 'Basic plan for getting started',
                'price' => 999,
                'duration_days' => 30,
                'contact_views_limit' => 20,
                'chat_limit' => 50,
                'interest_limit' => 50,
                'can_see_contact' => true,
                'can_chat' => true,
                'profile_highlighter' => false,
                'priority_support' => false,
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Gold',
                'slug' => 'gold',
                'description' => 'Most popular plan with great features',
                'price' => 2499,
                'duration_days' => 90,
                'contact_views_limit' => 75,
                'chat_limit' => 0, // Unlimited
                'interest_limit' => 150,
                'can_see_contact' => true,
                'can_chat' => true,
                'profile_highlighter' => true,
                'priority_support' => true,
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Platinum',
                'slug' => 'platinum',
                'description' => 'Premium plan with unlimited features',
                'price' => 4999,
                'duration_days' => 180,
                'contact_views_limit' => 0, // Unlimited
                'chat_limit' => 0, // Unlimited
                'interest_limit' => 0, // Unlimited
                'can_see_contact' => true,
                'can_chat' => true,
                'profile_highlighter' => true,
                'priority_support' => true,
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}









