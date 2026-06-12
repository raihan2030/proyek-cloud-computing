<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IaasServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('iaas_services')->insert([
            [
                'service_name'     => 'MiniStack Object Storage (S3)',
                'service_category' => 'Storage',
            ],
            [
                'service_name'     => 'MiniStack Compute (EC2)',
                'service_category' => 'Compute',
            ],
        ]);
    }
}
