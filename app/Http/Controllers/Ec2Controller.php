<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Aws\Ec2\Ec2Client;

class Ec2Controller extends Controller
{
    /**
     * Create a shared EC2 client pointing to MiniStack/LocalStack.
     */
    private function getEc2Client(): Ec2Client
    {
        $config = [
            'region'  => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'version' => 'latest',
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID', 'test'),
                'secret' => env('AWS_SECRET_ACCESS_KEY', 'test'),
            ],
        ];

        // Point to MiniStack/LocalStack endpoint if configured
        if ($endpoint = env('AWS_ENDPOINT')) {
            $config['endpoint'] = $endpoint;
        }

        return new Ec2Client($config);
    }

    /**
     * Map compute plan vCPU count to an AWS instance type string.
     */
    private function mapInstanceType(int $vcpus): string
    {
        return match ($vcpus) {
            1       => 't2.micro',
            2       => 't2.small',
            4       => 't2.medium',
            default => 't2.micro',
        };
    }

    // =========================================================================
    // Feature 1: Launch Instance with Dynamic Plan Choice
    // =========================================================================
    public function launchInstance(Request $request)
    {
        $request->validate([
            'instance_name' => 'required|string|min:3|max:63|regex:/^[a-zA-Z0-9._-]+$/',
            'plan_id'       => 'required|exists:subscription_plans,id',
        ]);

        $instanceName = $request->instance_name;
        $planId       = $request->plan_id;
        $userId       = Auth::id();

        // Fetch the chosen plan
        $plan = DB::table('subscription_plans')->where('id', $planId)->first();
        $hourlyCost = $plan ? ($plan->monthly_price / 720) : 0.011;
        $instanceType = $this->mapInstanceType($plan->compute_quota_vcpu ?? 1);

        try {
            $ec2 = $this->getEc2Client();

            // Launch one instance in MiniStack
            $result = $ec2->runInstances([
                'ImageId'      => 'ami-0abcdef1234567890', // Dummy AMI for LocalStack
                'InstanceType' => $instanceType,
                'MinCount'     => 1,
                'MaxCount'     => 1,
                'TagSpecifications' => [
                    [
                        'ResourceType' => 'instance',
                        'Tags' => [
                            ['Key' => 'Name', 'Value' => $instanceName],
                        ],
                    ],
                ],
            ]);

            $instanceId = $result['Instances'][0]['InstanceId'] ?? 'i-unknown';

            // 1. Save to Provisioned Resources (The Infrastructure)
            DB::table('provisioned_resources')->insert([
                'user_id'               => $userId,
                'plan_id'               => $planId,
                'resource_type'         => 'compute',
                'instance_name'         => $instanceName,
                'ministack_resource_id' => $instanceId,
                'configuration'         => json_encode([
                    'region'        => env('AWS_DEFAULT_REGION', 'us-east-1'),
                    'instance_type' => $instanceType,
                    'ami_id'        => 'ami-0abcdef1234567890',
                ]),
                'status'          => 'running',
                'hourly_cost'     => round($hourlyCost, 5),
                'rent_start_date' => now(),
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            // 2. Save to User Subscriptions (The Billing/Quota)
            DB::table('user_subscriptions')->insert([
                'user_id'                    => $userId,
                'plan_id'                    => $planId,
                'ministack_instance_id'      => $instanceId,
                'remaining_storage_quota_gb' => 0,
                'remaining_compute_quota'    => $plan->compute_quota_vcpu ?? 1,
                'remaining_vpc_quota'        => 0,
                'subscription_status'        => 'active',
                'start_date'                 => now(),
                'created_at'                 => now(),
                'updated_at'                 => now(),
            ]);

            return back()->with('success', "EC2 Instance '{$instanceName}' launched as {$instanceId}!");

        } catch (\Exception $e) {
            return back()->with('error', 'EC2 Launch Error: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // Feature 2: List Instances (AJAX-Ready)
    // =========================================================================
    public function listInstances(Request $request)
    {
        $userId = Auth::id();

        try {
            // Fetch all non-terminated compute resources for this user
            $resources = DB::table('provisioned_resources')
                ->where('user_id', $userId)
                ->where('resource_type', 'compute')
                ->whereIn('status', ['running', 'stopped'])
                ->orderBy('created_at', 'desc')
                ->get();

            $instances = [];

            foreach ($resources as $resource) {
                $config = json_decode($resource->configuration, true);
                $instances[] = [
                    'instance_id'   => $resource->ministack_resource_id,
                    'instance_name' => $resource->instance_name,
                    'instance_type' => $config['instance_type'] ?? 'unknown',
                    'status'        => $resource->status,
                    'hourly_cost'   => $resource->hourly_cost,
                    'launched_at'   => $resource->rent_start_date,
                ];
            }

            if ($request->wantsJson()) {
                return response()->json([
                    'success'   => true,
                    'instances' => $instances,
                ]);
            }

            return back()->with('instances', $instances);

        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', 'Failed to list instances: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // Feature 3: Stop Instance
    // =========================================================================
    public function stopInstance(Request $request)
    {
        $request->validate(['instance_id' => 'required|string']);
        $instanceId = $request->instance_id;

        try {
            $ec2 = $this->getEc2Client();
            $ec2->stopInstances(['InstanceIds' => [$instanceId]]);

            DB::table('provisioned_resources')
                ->where('ministack_resource_id', $instanceId)
                ->where('user_id', Auth::id())
                ->update([
                    'status'     => 'stopped',
                    'updated_at' => now(),
                ]);

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => "Instance {$instanceId} stopped."]);
            }
            return back()->with('success', "Instance '{$instanceId}' stopped.");

        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', 'Stop failed: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // Feature 4: Start Instance
    // =========================================================================
    public function startInstance(Request $request)
    {
        $request->validate(['instance_id' => 'required|string']);
        $instanceId = $request->instance_id;

        try {
            $ec2 = $this->getEc2Client();
            $ec2->startInstances(['InstanceIds' => [$instanceId]]);

            DB::table('provisioned_resources')
                ->where('ministack_resource_id', $instanceId)
                ->where('user_id', Auth::id())
                ->update([
                    'status'     => 'running',
                    'updated_at' => now(),
                ]);

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => "Instance {$instanceId} started."]);
            }
            return back()->with('success', "Instance '{$instanceId}' started.");

        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', 'Start failed: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // Feature 5: Terminate Instance & Stop Billing
    // =========================================================================
    public function terminateInstance(Request $request)
    {
        $request->validate(['instance_id' => 'required|string']);
        $instanceId = $request->instance_id;

        try {
            // 1. Terminate in MiniStack
            $ec2 = $this->getEc2Client();
            $ec2->terminateInstances(['InstanceIds' => [$instanceId]]);

            // 2. Update provisioned resource (Stop billing)
            DB::table('provisioned_resources')
                ->where('ministack_resource_id', $instanceId)
                ->where('user_id', Auth::id())
                ->update([
                    'status'        => 'terminated',
                    'rent_end_date' => now(),
                    'updated_at'    => now(),
                ]);

            // 3. Cancel the User Subscription
            DB::table('user_subscriptions')
                ->where('ministack_instance_id', $instanceId)
                ->where('user_id', Auth::id())
                ->where('subscription_status', 'active')
                ->update([
                    'subscription_status' => 'cancelled',
                    'end_date'            => now(),
                    'updated_at'          => now(),
                ]);

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => "Instance {$instanceId} terminated. Billing stopped."]);
            }
            return back()->with('success', "Instance '{$instanceId}' terminated. Billing has been stopped.");

        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', 'Termination failed: ' . $e->getMessage());
        }
    }
}
