<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'title' => 'اشتراک ماهانه',
                'slug' => 'monthly',
                'duration_days' => 30,
                'price' => 99000,
                'description' => 'دسترسی کامل به بانک سؤال و سیستم آزمون به مدت یک ماه',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'اشتراک سالانه',
                'slug' => 'yearly',
                'duration_days' => 365,
                'price' => 799000,
                'description' => 'دسترسی کامل به بانک سؤال و سیستم آزمون به مدت یک سال',
                'is_active' => true,
                'sort_order' => 2,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::query()->updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
