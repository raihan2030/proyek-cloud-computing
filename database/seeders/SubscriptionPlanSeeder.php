<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('subscription_plans')->insert([
            [
                'plan_name' => 'Basic Storage',
                'service_type' => 'iaas',
                'storage_quota_gb' => 5,
                'compute_quota_vcpu' => 0,
                'network_quota_vpc' => 0,
                'monthly_price' => 5.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_name' => 'Pro Storage',
                'service_type' => 'iaas',
                'storage_quota_gb' => 50,
                'compute_quota_vcpu' => 0,
                'network_quota_vpc' => 0,
                'monthly_price' => 15.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_name' => 'Premium Storage',
                'service_type' => 'iaas',
                'storage_quota_gb' => 250,
                'compute_quota_vcpu' => 0,
                'network_quota_vpc' => 0,
                'monthly_price' => 45.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // EC2 Compute Plans
            [
                'plan_name' => 'Basic Compute (t2.micro)',
                'service_type' => 'iaas',
                'storage_quota_gb' => 0,
                'compute_quota_vcpu' => 1,
                'network_quota_vpc' => 0,
                'monthly_price' => 8.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_name' => 'Pro Compute (t2.small)',
                'service_type' => 'iaas',
                'storage_quota_gb' => 0,
                'compute_quota_vcpu' => 2,
                'network_quota_vpc' => 0,
                'monthly_price' => 20.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_name' => 'Premium Compute (t2.medium)',
                'service_type' => 'iaas',
                'storage_quota_gb' => 0,
                'compute_quota_vcpu' => 4,
                'network_quota_vpc' => 0,
                'monthly_price' => 50.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}