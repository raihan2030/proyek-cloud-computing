<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::table('subscription_plans')->truncate();
        DB::table('iaas_services')->truncate();

        $computeServiceId = DB::table('iaas_services')->insertGetId([
            'service_name'     => 'MiniStack Compute (EC2)',
            'service_category' => 'Compute',
            'is_available'     => true,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        $storageServiceId = DB::table('iaas_services')->insertGetId([
            'service_name'     => 'MiniStack Object Storage (S3)',
            'service_category' => 'Storage',
            'is_available'     => true,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        DB::table('subscription_plans')->insert([
            // --- LAYANAN COMPUTE (EC2) ---
            [
                'service_id'          => $computeServiceId,
                'plan_name'           => 'Basic Compute (t2.micro)',
                'storage_quota_gb'    => 0,
                'compute_quota_vcpu'  => 1, // Di-map ke t2.micro di Ec2Controller
                'network_quota_vpc'   => 1,
                'monthly_price'       => 100000.00,
                'description'         => 'Cocok untuk uji coba dan aplikasi skala kecil.',
                'is_active'           => true,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'service_id'          => $computeServiceId,
                'plan_name'           => 'Standard Compute (t2.small)',
                'storage_quota_gb'    => 0,
                'compute_quota_vcpu'  => 2, // Di-map ke t2.small di Ec2Controller
                'network_quota_vpc'   => 2,
                'monthly_price'       => 250000.00,
                'description'         => 'Ideal untuk lingkungan pengembangan dan pengujian.',
                'is_active'           => true,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'service_id'          => $computeServiceId,
                'plan_name'           => 'Pro Compute (t2.medium)',
                'storage_quota_gb'    => 0,
                'compute_quota_vcpu'  => 4, // Di-map ke t2.medium di Ec2Controller
                'network_quota_vpc'   => 5,
                'monthly_price'       => 500000.00,
                'description'         => 'Performa tinggi untuk aplikasi produksi skala menengah.',
                'is_active'           => true,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],

            // --- LAYANAN STORAGE (S3) ---
            [
                'service_id'          => $storageServiceId,
                'plan_name'           => 'Basic Storage 5GB',
                'storage_quota_gb'    => 5, // Di-cek di S3Controller saat batch upload
                'compute_quota_vcpu'  => 0,
                'network_quota_vpc'   => 0,
                'monthly_price'       => 25000.00,
                'description'         => 'Penyimpanan dasar cloud storage hingga kapasitas 5GB.',
                'is_active'           => true,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'service_id'          => $storageServiceId,
                'plan_name'           => 'Pro Storage 50GB',
                'storage_quota_gb'    => 50,
                'compute_quota_vcpu'  => 0,
                'network_quota_vpc'   => 0,
                'monthly_price'       => 120000.00,
                'description'         => 'Penyimpanan handal untuk file multimedia hingga 50GB.',
                'is_active'           => true,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'service_id'          => $storageServiceId,
                'plan_name'           => 'Premium Storage 150GB',
                'storage_quota_gb'    => 150,
                'compute_quota_vcpu'  => 0,
                'network_quota_vpc'   => 0,
                'monthly_price'       => 300000.00,
                'description'         => 'Penyimpanan dengan kapasitas besar hingga 150GB.',
                'is_active'           => true,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
        ]);
    }
}