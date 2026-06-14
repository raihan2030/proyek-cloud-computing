<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Dashboard Overview
     */
    public function index()
    {
        $totalUsers = DB::table('users')->count();
        
        $runningInstances = DB::table('provisioned_resources')
            ->where('resource_type', 'compute')
            ->where('status', 'running')
            ->count();
            
        $activeBuckets = DB::table('provisioned_resources')
            ->where('resource_type', 'storage')
            ->where('status', 'running')
            ->count();
            
        $totalRevenue = DB::table('payments')
            ->where('status', 'paid')
            ->sum('billing_amount');
            
        $totalBalance = DB::table('users')->sum('virtual_balance');
        $pendingPayments = DB::table('payments')
            ->where('status', 'pending')
            ->sum('billing_amount');

        $recentLogs = DB::table('activity_logs')
            ->leftJoin('users', 'activity_logs.user_id', '=', 'users.id')
            ->select('activity_logs.*', 'users.name as user_name')
            ->orderBy('activity_logs.created_at', 'desc')
            ->limit(8)
            ->get();

        return view('admin', [
            'tab' => 'overview',
            'stats' => [
                'total_users' => $totalUsers,
                'running_instances' => $runningInstances,
                'active_buckets' => $activeBuckets,
                'total_revenue' => $totalRevenue,
                'total_balance' => $totalBalance,
                'pending_payments' => $pendingPayments,
            ],
            'recentLogs' => $recentLogs
        ]);
    }

    /**
     * Users list
     */
    public function users()
    {
        $users = DB::table('users')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin', [
            'tab' => 'users',
            'users' => $users
        ]);
    }

    /**
     * Adjust user balance
     */
    public function updateBalance(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric'
        ]);

        $user = DB::table('users')->where('id', $id)->first();
        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        $newBalance = max(0, $user->virtual_balance + $request->amount);

        DB::table('users')
            ->where('id', $id)
            ->update([
                'virtual_balance' => $newBalance,
                'updated_at' => now()
            ]);

        DB::table('activity_logs')->insert([
            'user_id' => Auth::id(),
            'action_type' => 'update',
            'description' => "Adjusted user ID {$id} balance by {$request->amount} to {$newBalance}",
            'created_at' => now()
        ]);

        return back()->with('success', "Balance for {$user->name} adjusted successfully.");
    }

    /**
     * Update user role
     */
    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:admin,user'
        ]);

        if (Auth::id() == $id) {
            return back()->with('error', 'You cannot change your own role.');
        }

        $user = DB::table('users')->where('id', $id)->first();
        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        DB::table('users')
            ->where('id', $id)
            ->update([
                'role' => $request->role,
                'updated_at' => now()
            ]);

        DB::table('activity_logs')->insert([
            'user_id' => Auth::id(),
            'action_type' => 'update',
            'description' => "Changed user ID {$id} role from {$user->role} to {$request->role}",
            'created_at' => now()
        ]);

        return back()->with('success', "Role for {$user->name} updated to {$request->role}.");
    }

    /**
     * Delete a user
     */
    public function deleteUser($id)
    {
        if (Auth::id() == $id) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        $user = DB::table('users')->where('id', $id)->first();
        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        DB::table('users')->where('id', $id)->delete();

        DB::table('activity_logs')->insert([
            'user_id' => Auth::id(),
            'action_type' => 'delete',
            'description' => "Deleted user {$user->name} ({$user->email})",
            'created_at' => now()
        ]);

        return back()->with('success', "User {$user->name} deleted successfully.");
    }

    /**
     * Plans listing
     */
    public function plans()
    {
        $plans = DB::table('subscription_plans')
            ->join('iaas_services', 'subscription_plans.service_id', '=', 'iaas_services.id')
            ->select('subscription_plans.*', 'iaas_services.service_name', 'iaas_services.service_category')
            ->orderBy('subscription_plans.created_at', 'desc')
            ->get();

        $services = DB::table('iaas_services')->get();

        return view('admin', [
            'tab' => 'plans',
            'plans' => $plans,
            'services' => $services
        ]);
    }

    /**
     * Create subscription plan
     */
    public function createPlan(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:iaas_services,id',
            'plan_name' => 'required|string|max:255',
            'storage_quota_gb' => 'required|integer|min:0',
            'compute_quota_vcpu' => 'required|integer|min:0',
            'network_quota_vpc' => 'required|integer|min:0',
            'monthly_price' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        DB::table('subscription_plans')->insert([
            'service_id' => $request->service_id,
            'plan_name' => $request->plan_name,
            'storage_quota_gb' => $request->storage_quota_gb,
            'compute_quota_vcpu' => $request->compute_quota_vcpu,
            'network_quota_vpc' => $request->network_quota_vpc,
            'monthly_price' => $request->monthly_price,
            'description' => $request->description,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('activity_logs')->insert([
            'user_id' => Auth::id(),
            'action_type' => 'create',
            'description' => "Created new subscription plan '{$request->plan_name}'",
            'created_at' => now()
        ]);

        return back()->with('success', "Plan '{$request->plan_name}' created successfully.");
    }

    /**
     * Toggle plan active status
     */
    public function togglePlan($id)
    {
        $plan = DB::table('subscription_plans')->where('id', $id)->first();
        if (!$plan) {
            return back()->with('error', 'Plan not found.');
        }

        $newStatus = !$plan->is_active;

        DB::table('subscription_plans')
            ->where('id', $id)
            ->update([
                'is_active' => $newStatus,
                'updated_at' => now()
            ]);

        DB::table('activity_logs')->insert([
            'user_id' => Auth::id(),
            'action_type' => 'update',
            'description' => "Toggled plan '{$plan->plan_name}' status to " . ($newStatus ? 'active' : 'inactive'),
            'created_at' => now()
        ]);

        return back()->with('success', "Plan '{$plan->plan_name}' status updated.");
    }

    /**
     * Delete subscription plan
     */
    public function deletePlan($id)
    {
        $plan = DB::table('subscription_plans')->where('id', $id)->first();
        if (!$plan) {
            return back()->with('error', 'Plan not found.');
        }

        DB::table('subscription_plans')->where('id', $id)->delete();

        DB::table('activity_logs')->insert([
            'user_id' => Auth::id(),
            'action_type' => 'delete',
            'description' => "Deleted subscription plan '{$plan->plan_name}'",
            'created_at' => now()
        ]);

        return back()->with('success', "Plan '{$plan->plan_name}' deleted successfully.");
    }

    /**
     * Provisioned Resources
     */
    public function resources()
    {
        $resources = DB::table('provisioned_resources')
            ->join('users', 'provisioned_resources.user_id', '=', 'users.id')
            ->join('subscription_plans', 'provisioned_resources.plan_id', '=', 'subscription_plans.id')
            ->select('provisioned_resources.*', 'users.name as user_name', 'subscription_plans.plan_name')
            ->orderBy('provisioned_resources.created_at', 'desc')
            ->get();

        return view('admin', [
            'tab' => 'resources',
            'resources' => $resources
        ]);
    }

    /**
     * Payments / Invoices list
     */
    public function payments()
    {
        $payments = DB::table('payments')
            ->join('users', 'payments.user_id', '=', 'users.id')
            ->leftJoin('provisioned_resources', 'payments.resource_id', '=', 'provisioned_resources.id')
            ->select('payments.*', 'users.name as user_name', 'provisioned_resources.instance_name')
            ->orderBy('payments.created_at', 'desc')
            ->get();

        return view('admin', [
            'tab' => 'payments',
            'payments' => $payments
        ]);
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,failed'
        ]);

        $payment = DB::table('payments')->where('id', $id)->first();
        if (!$payment) {
            return back()->with('error', 'Payment not found.');
        }

        $updateData = [
            'status' => $request->status,
            'updated_at' => now()
        ];

        if ($request->status === 'paid') {
            $updateData['payment_date'] = now();
        }

        DB::table('payments')
            ->where('id', $id)
            ->update($updateData);

        DB::table('activity_logs')->insert([
            'user_id' => Auth::id(),
            'action_type' => 'update',
            'description' => "Updated status for payment/invoice {$payment->invoice_number} to {$request->status}",
            'created_at' => now()
        ]);

        return back()->with('success', "Invoice status updated to {$request->status}.");
    }

    /**
     * System Logs
     */
    public function logs()
    {
        $logs = DB::table('activity_logs')
            ->leftJoin('users', 'activity_logs.user_id', '=', 'users.id')
            ->select('activity_logs.*', 'users.name as user_name')
            ->orderBy('activity_logs.created_at', 'desc')
            ->paginate(20);

        return view('admin', [
            'tab' => 'logs',
            'logs' => $logs
        ]);
    }
}
