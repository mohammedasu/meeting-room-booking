<?php

namespace Database\Seeders;

use App\Models\Subscription;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            ['name' => 'Free Plan', 'booking_limit' => 3, 'price' => 0],
            ['name' => 'Basic Plan', 'booking_limit' => 5, 'price' => 10.00],
            ['name' => 'Advance Plan', 'booking_limit' => 7, 'price' => 20.00],
            ['name' => 'Premium Plan', 'booking_limit' => 10, 'price' => 30.00],
        ];

        foreach ($plans as $plan) {
            Subscription::create($plan);
        }
    }
}
