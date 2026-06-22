<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class S3ModifyPlanTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_modify_s3_bucket_subscription_plan(): void
    {
        // 1. Create a user
        $user = User::factory()->create([
            'role' => 'user'
        ]);

        // 2. Setup IaaS Service and Subscription Plans
        $serviceId = DB::table('iaas_services')->insertGetId([
            'service_name' => 'MiniStack Object Storage (S3)',
            'service_category' => 'Storage',
            'is_available' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $basicPlanId = DB::table('subscription_plans')->insertGetId([
            'service_id' => $serviceId,
            'plan_name' => 'Basic Storage 5GB',
            'storage_quota_gb' => 5,
            'compute_quota_vcpu' => 0,
            'network_quota_vpc' => 0,
            'monthly_price' => 25000.00,
            'description' => 'Basic plan',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $proPlanId = DB::table('subscription_plans')->insertGetId([
            'service_id' => $serviceId,
            'plan_name' => 'Pro Storage 50GB',
            'storage_quota_gb' => 50,
            'compute_quota_vcpu' => 0,
            'network_quota_vpc' => 0,
            'monthly_price' => 120000.00,
            'description' => 'Pro plan',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Setup active provisioned S3 resource
        $bucketName = 'test-my-bucket';
        $resourceId = DB::table('provisioned_resources')->insertGetId([
            'user_id' => $user->id,
            'plan_id' => $basicPlanId,
            'resource_type' => 'storage',
            'instance_name' => $bucketName,
            'ministack_resource_id' => $bucketName,
            'configuration' => json_encode(['region' => 'us-east-1']),
            'status' => 'running',
            'hourly_cost' => round(25000.00 / 720, 5),
            'rent_start_date' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 4. Send modify plan POST request
        $response = $this->actingAs($user)->post(route('s3.modifyPlan'), [
            'bucket_name' => $bucketName,
            'target_plan_id' => $proPlanId
        ]);

        // 5. Assert redirection and session success message
        $response->assertStatus(302);
        $response->assertSessionHas('success');

        // 6. Assert DB provisioned_resources was updated
        $updatedResource = DB::table('provisioned_resources')->where('id', $resourceId)->first();
        $this->assertEquals($proPlanId, $updatedResource->plan_id);
        
        $expectedHourlyCost = round(120000.00 / 720, 5);
        $this->assertEquals($expectedHourlyCost, (float)$updatedResource->hourly_cost);

        // 7. Assert activity log entry exists
        $log = DB::table('activity_logs')->where('resource_id', $resourceId)->first();
        $this->assertNotNull($log);
        $this->assertEquals('update', $log->action_type);
        $this->assertStringContainsString($bucketName, $log->description);
        $this->assertStringContainsString('Pro Storage 50GB', $log->description);
    }

    public function test_modify_plan_requires_valid_target_plan(): void
    {
        $user = User::factory()->create();
        $bucketName = 'test-my-bucket';

        $response = $this->actingAs($user)->post(route('s3.modifyPlan'), [
            'bucket_name' => $bucketName,
            'target_plan_id' => 999999 // Invalid Plan ID
        ]);

        $response->assertSessionHasErrors(['target_plan_id']);
    }

    public function test_modify_plan_fails_if_storage_usage_exceeds_target_plan(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $serviceId = DB::table('iaas_services')->insertGetId([
            'service_name' => 'MiniStack Object Storage (S3)',
            'service_category' => 'Storage',
            'is_available' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $basicPlanId = DB::table('subscription_plans')->insertGetId([
            'service_id' => $serviceId,
            'plan_name' => 'Basic Storage 5GB',
            'storage_quota_gb' => 5,
            'compute_quota_vcpu' => 0,
            'network_quota_vpc' => 0,
            'monthly_price' => 25000.00,
            'description' => 'Basic plan',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $smallPlanId = DB::table('subscription_plans')->insertGetId([
            'service_id' => $serviceId,
            'plan_name' => 'Mini Storage 1GB',
            'storage_quota_gb' => 1,
            'compute_quota_vcpu' => 0,
            'network_quota_vpc' => 0,
            'monthly_price' => 10000.00,
            'description' => 'Mini plan',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $bucketName = 'test-my-bucket';
        DB::table('provisioned_resources')->insertGetId([
            'user_id' => $user->id,
            'plan_id' => $basicPlanId,
            'resource_type' => 'storage',
            'instance_name' => $bucketName,
            'ministack_resource_id' => $bucketName,
            'configuration' => json_encode(['region' => 'us-east-1']),
            'status' => 'running',
            'hourly_cost' => round(25000.00 / 720, 5),
            'rent_start_date' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Mock S3 client to return 2 GB of storage usage
        $s3ClientMock = \Mockery::mock(\Aws\S3\S3Client::class);
        $s3ClientMock->shouldReceive('listObjectsV2')
            ->andReturn([
                'Contents' => [
                    ['Size' => 2 * 1024 * 1024 * 1024]
                ]
            ]);

        $mockDisk = \Mockery::mock(\Illuminate\Filesystem\FilesystemAdapter::class);
        $mockDisk->shouldReceive('getClient')->andReturn($s3ClientMock);

        \Illuminate\Support\Facades\Storage::shouldReceive('disk')
            ->with('s3')
            ->andReturn($mockDisk);

        $response = $this->actingAs($user)->post(route('s3.modifyPlan'), [
            'bucket_name' => $bucketName,
            'target_plan_id' => $smallPlanId
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error');
        $this->assertStringContainsString('Anda tidak dapat menurunkan paket penyimpanan', session('error'));
    }
}
