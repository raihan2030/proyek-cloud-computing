<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ApiKeyApiTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $s3Resource;
    private $ec2Resource;
    private $s3AccessKey = 's3-access-key-123';
    private $s3SecretKey = 's3-secret-key-123';
    private $ec2AccessKey = 'ec2-access-key-456';
    private $ec2SecretKey = 'ec2-secret-key-456';

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create a User
        $this->user = User::factory()->create([
            'role' => 'user'
        ]);

        // 2. Setup S3 & EC2 Services
        $s3ServiceId = DB::table('iaas_services')->insertGetId([
            'service_name' => 'MiniStack Object Storage (S3)',
            'service_category' => 'Storage',
            'is_available' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $ec2ServiceId = DB::table('iaas_services')->insertGetId([
            'service_name' => 'MiniStack Compute (EC2)',
            'service_category' => 'Compute',
            'is_available' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $s3PlanId = DB::table('subscription_plans')->insertGetId([
            'service_id' => $s3ServiceId,
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

        $ec2PlanId = DB::table('subscription_plans')->insertGetId([
            'service_id' => $ec2ServiceId,
            'plan_name' => 'Micro Compute',
            'storage_quota_gb' => 0,
            'compute_quota_vcpu' => 1,
            'network_quota_vpc' => 0,
            'monthly_price' => 50000.00,
            'description' => 'Compute plan',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Setup Provisioned Resources
        $this->s3Resource = DB::table('provisioned_resources')->insertGetId([
            'user_id' => $this->user->id,
            'plan_id' => $s3PlanId,
            'resource_type' => 'storage',
            'instance_name' => 'my-test-bucket',
            'ministack_resource_id' => 'my-test-bucket',
            'configuration' => json_encode(['region' => 'us-east-1']),
            'status' => 'running',
            'hourly_cost' => round(25000.00 / 720, 5),
            'rent_start_date' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $this->ec2Resource = DB::table('provisioned_resources')->insertGetId([
            'user_id' => $this->user->id,
            'plan_id' => $ec2PlanId,
            'resource_type' => 'compute',
            'instance_name' => 'my-test-instance',
            'ministack_resource_id' => 'i-testinstance123',
            'configuration' => json_encode(['instance_type' => 't2.micro']),
            'status' => 'running',
            'hourly_cost' => round(50000.00 / 720, 5),
            'rent_start_date' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 4. Setup API Keys
        DB::table('access_credentials')->insert([
            'user_id' => $this->user->id,
            'provisioned_id' => $this->s3Resource,
            'access_key' => $this->s3AccessKey,
            'secret_key_encrypted' => encrypt($this->s3SecretKey),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('access_credentials')->insert([
            'user_id' => $this->user->id,
            'provisioned_id' => $this->ec2Resource,
            'access_key' => $this->ec2AccessKey,
            'secret_key_encrypted' => encrypt($this->ec2SecretKey),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function test_api_requires_credentials(): void
    {
        $response = $this->getJson('/api/s3/files');
        $response->assertStatus(401)
                 ->assertJsonFragment(['success' => false, 'message' => 'Unauthorized: Missing Access Key or Secret Key.']);
    }

    public function test_api_rejects_invalid_access_key(): void
    {
        $response = $this->getJson('/api/s3/files', [
            'X-Access-Key' => 'invalid-access-key',
            'X-Secret-Key' => $this->s3SecretKey,
        ]);
        $response->assertStatus(401)
                 ->assertJsonFragment(['success' => false, 'message' => 'Unauthorized: Invalid Access Key.']);
    }

    public function test_api_rejects_invalid_secret_key(): void
    {
        $response = $this->getJson('/api/s3/files', [
            'X-Access-Key' => $this->s3AccessKey,
            'X-Secret-Key' => 'invalid-secret-key',
        ]);
        $response->assertStatus(401)
                 ->assertJsonFragment(['success' => false, 'message' => 'Unauthorized: Invalid Secret Key.']);
    }

    public function test_api_s3_files_success(): void
    {
        // Mock S3 listObjectsV2 response
        $s3ClientMock = \Mockery::mock(\Aws\S3\S3Client::class);
        $s3ClientMock->shouldReceive('listObjectsV2')
            ->with(['Bucket' => 'my-test-bucket'])
            ->andReturn([
                'Contents' => [
                    [
                        'Key' => 'file1.txt',
                        'Size' => 1024,
                        'LastModified' => new \Aws\Api\DateTimeResult('2026-06-30T12:00:00Z')
                    ]
                ]
            ]);

        $mockDisk = \Mockery::mock(\Illuminate\Filesystem\FilesystemAdapter::class);
        $mockDisk->shouldReceive('getClient')->andReturn($s3ClientMock);

        Storage::shouldReceive('disk')
            ->with('s3')
            ->andReturn($mockDisk);

        $response = $this->getJson('/api/s3/files', [
            'X-Access-Key' => $this->s3AccessKey,
            'X-Secret-Key' => $this->s3SecretKey,
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('success', true)
                 ->assertJsonPath('bucket', 'my-test-bucket')
                 ->assertJsonFragment([
                     'key' => 'file1.txt',
                     'size' => 1024
                 ]);
    }

    public function test_api_s3_files_blocks_ec2_key(): void
    {
        $response = $this->getJson('/api/s3/files', [
            'X-Access-Key' => $this->ec2AccessKey,
            'X-Secret-Key' => $this->ec2SecretKey,
        ]);

        $response->assertStatus(403)
                 ->assertJsonFragment(['success' => false, 'message' => 'Forbidden: This API key is not authorized for S3 Storage operations.']);
    }

    public function test_api_ec2_status_success(): void
    {
        $response = $this->getJson('/api/ec2/status', [
            'X-Access-Key' => $this->ec2AccessKey,
            'X-Secret-Key' => $this->ec2SecretKey,
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('success', true)
                 ->assertJsonPath('instance_id', 'i-testinstance123')
                 ->assertJsonPath('instance_name', 'my-test-instance')
                 ->assertJsonPath('status', 'running');
    }

    public function test_api_ec2_status_blocks_s3_key(): void
    {
        $response = $this->getJson('/api/ec2/status', [
            'X-Access-Key' => $this->s3AccessKey,
            'X-Secret-Key' => $this->s3SecretKey,
        ]);

        $response->assertStatus(403)
                 ->assertJsonFragment(['success' => false, 'message' => 'Forbidden: This API key is not authorized for EC2 Compute operations.']);
    }

    public function test_api_ec2_stop_success(): void
    {
        $ec2ClientMock = \Mockery::mock(\Aws\Ec2\Ec2Client::class);
        $ec2ClientMock->shouldReceive('stopInstances')
            ->with(['InstanceIds' => ['i-testinstance123']])
            ->once()
            ->andReturn(true);

        $this->app->instance(\Aws\Ec2\Ec2Client::class, $ec2ClientMock);

        $response = $this->postJson('/api/ec2/stop', [], [
            'X-Access-Key' => $this->ec2AccessKey,
            'X-Secret-Key' => $this->ec2SecretKey,
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('success', true)
                 ->assertJsonPath('message', "Instance 'i-testinstance123' stopped successfully.");

        $updatedResource = DB::table('provisioned_resources')->where('id', $this->ec2Resource)->first();
        $this->assertEquals('stopped', $updatedResource->status);
    }

    public function test_api_ec2_start_success(): void
    {
        // Change status to stopped first to test start
        DB::table('provisioned_resources')->where('id', $this->ec2Resource)->update(['status' => 'stopped']);

        $ec2ClientMock = \Mockery::mock(\Aws\Ec2\Ec2Client::class);
        $ec2ClientMock->shouldReceive('startInstances')
            ->with(['InstanceIds' => ['i-testinstance123']])
            ->once()
            ->andReturn(true);

        $this->app->instance(\Aws\Ec2\Ec2Client::class, $ec2ClientMock);

        $response = $this->postJson('/api/ec2/start', [], [
            'X-Access-Key' => $this->ec2AccessKey,
            'X-Secret-Key' => $this->ec2SecretKey,
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('success', true)
                 ->assertJsonPath('message', "Instance 'i-testinstance123' started successfully.");

        $updatedResource = DB::table('provisioned_resources')->where('id', $this->ec2Resource)->first();
        $this->assertEquals('running', $updatedResource->status);
    }
}
