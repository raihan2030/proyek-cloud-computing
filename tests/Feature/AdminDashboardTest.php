<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Guest is redirected to login.
     */
    public function test_guest_is_redirected_from_admin_dashboard(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/login');
    }

    /**
     * Regular user receives 403 Forbidden.
     */
    public function test_regular_user_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertStatus(403);
    }

    /**
     * Admin user can access dashboard.
     */
    public function test_admin_user_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Ringkasan');
        $response->assertSee('Kelola Pengguna');
    }

    /**
     * Admin user can access other admin sub-routes.
     */
    public function test_admin_can_access_users_management_page(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('/admin/users');

        $response->assertStatus(200);
        $response->assertSee('Kelola Pengguna');
    }

    /**
     * Admin can adjust user virtual balance.
     */
    public function test_admin_can_adjust_user_balance(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $user = User::factory()->create([
            'role' => 'user',
            'virtual_balance' => 100.00
        ]);

        $response = $this->actingAs($admin)->post("/admin/users/{$user->id}/balance", [
            'amount' => 50.50
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertEquals(150.50, DB::table('users')->where('id', $user->id)->value('virtual_balance'));
    }

    /**
     * Admin can change user role.
     */
    public function test_admin_can_change_user_role(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $response = $this->actingAs($admin)->post("/admin/users/{$user->id}/role", [
            'role' => 'admin'
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertEquals('admin', DB::table('users')->where('id', $user->id)->value('role'));
    }

    /**
     * Admin cannot change their own role.
     */
    public function test_admin_cannot_change_own_role(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->post("/admin/users/{$admin->id}/role", [
            'role' => 'user'
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals('admin', DB::table('users')->where('id', $admin->id)->value('role'));
    }

    /**
     * Admin can create a subscription plan.
     */
    public function test_admin_can_create_subscription_plan(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $serviceId = DB::table('iaas_services')->insertGetId([
            'service_name' => 'MiniStack Compute (EC2) Test',
            'service_category' => 'Compute',
            'is_available' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($admin)->post('/admin/plans', [
            'service_id' => $serviceId,
            'plan_name' => 'Super Compute Plan',
            'storage_quota_gb' => 0,
            'compute_quota_vcpu' => 8,
            'network_quota_vpc' => 0,
            'monthly_price' => 750000.00,
            'description' => 'Super fast vCPU cores'
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $plan = DB::table('subscription_plans')->where('plan_name', 'Super Compute Plan')->first();
        $this->assertNotNull($plan);
        $this->assertEquals(8, $plan->compute_quota_vcpu);
        $this->assertEquals(0, $plan->network_quota_vpc);
        $this->assertEquals(0, $plan->storage_quota_gb);
    }
}
