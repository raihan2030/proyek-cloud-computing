<x-app-layout>
    @php
        $tab = request()->query('tab', 'overview');
    @endphp

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-md">
            <div>
                <h2 class="font-bold text-2xl text-primary tracking-tight">
                    @if ($tab === 'overview')
                        Overview
                    @elseif ($tab === 'resources')
                        Resource Cloud
                    @elseif ($tab === 'plans')
                        Paket Langganan
                    @elseif ($tab === 'credentials')
                        Kredensial API
                    @elseif ($tab === 'logs')
                        Audit Logs
                    @endif
                </h2>
                <p class="text-sm text-on-surface-variant mt-1">
                    @if ($tab === 'overview')
                        Sistem status, alokasi resourcet, dan log aktivitas terbaru.
                    @elseif ($tab === 'resources')
                        Kelola dan awasi instance compute (EC2) serta penyimpanan object (S3).
                    @elseif ($tab === 'plans')
                        Daftar paket komputasi dan penyimpanan cloud yang tersedia.
                    @elseif ($tab === 'credentials')
                        Akses kunci API privat per layanan untuk integrasi API.
                    @elseif ($tab === 'logs')
                        Riwayat lengkap audit log dan operasi infrastruktur cloud Anda.
                    @endif
                </p>
            </div>
            
            @if ($tab === 'resources')
                <div class="flex items-center gap-sm shrink-0">
                    <button id="ec2-refresh-btn" class="btn-secondary px-4 py-2 text-xs font-semibold uppercase tracking-wider rounded flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">refresh</span>
                        Sync
                    </button>
                    <a href="#launch-instance" class="btn-primary px-4 py-2 text-xs font-semibold uppercase tracking-wider rounded flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">add</span>
                        Deploy Resource
                    </a>
                </div>
            @endif
        </div>
    </x-slot>

    @if (session('success'))
        <div class="mb-6 p-4 bg-primary text-on-primary-fixed border border-outline-variant rounded flex items-center gap-md font-semibold">
            <span class="material-symbols-outlined">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="mb-6 p-4 bg-error/15 text-error border border-error/30 rounded flex items-center gap-md font-semibold">
            <span class="material-symbols-outlined">error</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- ============================================================ --}}
    {{-- OVERVIEW TAB --}}
    {{-- ============================================================ --}}
    @if ($tab === 'overview')
        <!-- Statistics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter mb-lg">
            <div class="stat-card">
                <p class="font-mono text-xs text-on-surface-variant uppercase tracking-widest mb-sm">Total Resources</p>
                <div class="flex items-baseline gap-sm">
                    <h3 class="text-3xl font-bold text-primary">{{ $totalResources }}</h3>
                    <span class="font-mono text-xs text-on-surface-variant">active</span>
                </div>
            </div>
            <div class="stat-card">
                <p class="font-mono text-xs text-on-surface-variant uppercase tracking-widest mb-sm">Active Services</p>
                <div class="flex items-baseline gap-sm">
                    <h3 class="text-3xl font-bold text-primary">{{ $activeServices }}</h3>
                    <span class="font-mono text-xs text-secondary">running</span>
                </div>
            </div>
            <div class="stat-card">
                <p class="font-mono text-xs text-on-surface-variant uppercase tracking-widest mb-sm">Monthly Spend</p>
                <div class="flex items-baseline gap-sm">
                    <h3 class="text-3xl font-bold text-primary">${{ number_format($monthlyBill, 2) }}</h3>
                    <span class="font-mono text-xs text-on-surface-variant">run rate</span>
                </div>
            </div>
        </div>

        <!-- Bento Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">
            <!-- Resource Allocation Chart -->
            <div class="lg:col-span-2 stat-card flex flex-col justify-between h-[380px]">
                <div class="border-b border-outline-variant pb-sm mb-md flex justify-between items-center">
                    <h3 class="font-semibold text-primary">Resource Allocation</h3>
                    <span class="font-mono text-[10px] text-on-surface-variant">MiniStack Engine</span>
                </div>
                
                <div class="flex-1 flex items-end gap-md relative px-md">
                    <!-- Chart Background Grid lines -->
                    <div class="absolute inset-x-lg top-lg bottom-lg flex flex-col justify-between pointer-events-none opacity-10">
                        <div class="border-t border-outline"></div>
                        <div class="border-t border-outline"></div>
                        <div class="border-t border-outline"></div>
                        <div class="border-t border-outline"></div>
                    </div>
                    
                    <!-- Simulated Bars -->
                    <div class="w-full flex justify-around items-end h-full z-10">
                        @php
                            $s3Count = count($bucketsData);
                            $ec2Count = count($instancesData);
                            $totalCount = max($s3Count + $ec2Count, 1);
                            $s3Percent = ($s3Count / $totalCount) * 100;
                            $ec2Percent = ($ec2Count / $totalCount) * 100;
                        @endphp
                        <div class="flex flex-col items-center gap-2 w-20">
                            <div class="w-12 bg-outline-variant rounded-t transition-all hover:bg-primary" style="height: {{ max($s3Percent * 2.5, 20) }}px"></div>
                            <span class="font-mono text-[10px] text-on-surface-variant">S3 Storage</span>
                        </div>
                        <div class="flex flex-col items-center gap-2 w-20">
                            <div class="w-12 bg-primary rounded-t transition-all hover:bg-primary-container" style="height: {{ max($ec2Percent * 2.5, 20) }}px"></div>
                            <span class="font-mono text-[10px] text-on-surface-variant">EC2 Compute</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Status -->
            <div class="stat-card flex flex-col justify-between h-[380px]">
                <div class="border-b border-outline-variant pb-sm mb-md">
                    <h3 class="font-semibold text-primary">System Status</h3>
                </div>
                
                <div class="flex-1 flex flex-col justify-around">
                    <div>
                        <div class="flex justify-between mb-sm font-mono text-xs">
                            <span class="text-on-surface-variant">Compute Load</span>
                            <span class="text-primary font-bold">{{ $ec2Count > 0 ? '45%' : '0%' }}</span>
                        </div>
                        <div class="w-full bg-surface-container-highest h-1.5 rounded-full overflow-hidden">
                            <div class="bg-primary h-full transition-all duration-500" style="width: {{ $ec2Count > 0 ? '45' : '0' }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-sm font-mono text-xs">
                            <span class="text-on-surface-variant">Storage Utilized</span>
                            <span class="text-primary font-bold">{{ $s3Count > 0 ? '32%' : '0%' }}</span>
                        </div>
                        <div class="w-full bg-surface-container-highest h-1.5 rounded-full overflow-hidden">
                            <div class="bg-outline h-full transition-all duration-500" style="width: {{ $s3Count > 0 ? '32' : '0' }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-sm font-mono text-xs">
                            <span class="text-on-surface-variant">Network Bandwidth</span>
                            <span class="text-primary font-bold">nominal</span>
                        </div>
                        <div class="w-full bg-surface-container-highest h-1.5 rounded-full overflow-hidden">
                            <div class="bg-outline-variant w-1/4 h-full"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Audit Logs -->
        <div class="stat-card mt-lg">
            <div class="border-b border-outline-variant pb-sm mb-lg flex justify-between items-center">
                <h3 class="font-semibold text-primary">Log Aktivitas Terbaru</h3>
                <a href="{{ route('dashboard', ['tab' => 'logs']) }}" class="text-xs text-on-surface-variant hover:text-primary transition-colors flex items-center gap-xs">
                    View All <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>

            <div class="space-y-4">
                @forelse ($recentActivities as $log)
                    <div class="flex items-start gap-4 pb-4 border-b border-outline-variant/30 last:border-0 last:pb-0">
                        <div class="flex-shrink-0 mt-0.5">
                            @if($log->action_type == 'create')
                                <div class="flex items-center justify-center h-8 w-8 rounded bg-primary text-on-primary-fixed">
                                    <span class="material-symbols-outlined text-sm">add</span>
                                </div>
                            @elseif($log->action_type == 'delete')
                                <div class="flex items-center justify-center h-8 w-8 rounded bg-error/15 text-error">
                                    <span class="material-symbols-outlined text-sm">delete</span>
                                </div>
                            @else
                                <div class="flex items-center justify-center h-8 w-8 rounded bg-surface-container-highest text-primary">
                                    <span class="material-symbols-outlined text-sm">info</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-center">
                                <p class="text-sm font-semibold text-primary capitalize">
                                    {{ $log->action_type }} Resource
                                </p>
                                <span class="font-mono text-[10px] text-on-surface-variant">
                                    {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                                </span>
                            </div>
                            <p class="text-xs text-on-surface-variant mt-1">
                                {{ $log->description }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-on-surface-variant text-sm border border-dashed border-outline-variant rounded">
                        Belum ada riwayat aktivitas penyewaan.
                    </div>
                @endforelse
            </div>
        </div>
    @endif

    {{-- ============================================================ --}}
    {{-- RESOURCES TAB --}}
    {{-- ============================================================ --}}
    @if ($tab === 'resources')
        <!-- Resource Quotas -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter mb-lg">
            <!-- S3 Quotas -->
            <div class="stat-card flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start mb-md">
                        <h3 class="font-semibold text-primary">S3 Storage Quotas</h3>
                        <span class="bg-primary/10 text-primary border border-outline-variant px-2.5 py-0.5 rounded-full font-mono text-[10px] font-bold">
                            {{ count($bucketsData) }} Active
                        </span>
                    </div>

                    @if (count($bucketsData) > 0)
                        <div class="mb-md">
                            <label class="block font-mono text-[10px] text-on-surface-variant mb-2 uppercase">Select Bucket to View</label>
                            <select id="bucket-selector" class="w-full bg-surface-container-lowest border border-outline-variant rounded p-2 text-sm">
                                @foreach ($bucketsData as $index => $bucket)
                                    <option value="{{ $index }}">{{ $bucket['name'] }} ({{ $bucket['totalGB'] }}GB Plan)</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-md">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs text-on-surface-variant font-medium">Capacity Used</span>
                                <span id="display-size-text" class="text-xs font-bold text-primary font-mono">
                                    {{ $bucketsData[0]['displaySize'] }} / {{ $bucketsData[0]['totalGB'] }} GB ({{ number_format($bucketsData[0]['percentage'], 0) }}%)
                                </span>
                            </div>
                            <div class="w-full bg-surface-container-highest rounded-full h-1.5 overflow-hidden">
                                <div id="capacity-progress-bar" class="bg-primary h-full transition-all duration-500" style="width: {{ $bucketsData[0]['percentage'] }}%"></div>
                            </div>
                        </div>

                        <div class="flex justify-between text-xs font-mono">
                            <span class="text-on-surface-variant">Available Storage</span>
                            <span id="available-storage-text" class="font-bold text-primary">{{ $bucketsData[0]['available'] }}</span>
                        </div>
                    @else
                        <div class="text-center py-6 text-on-surface-variant text-xs border border-dashed border-outline-variant rounded font-mono">
                            No active S3 buckets found.
                        </div>
                    @endif
                </div>
            </div>

            <!-- EC2 Quotas -->
            <div class="stat-card flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start mb-md">
                        <h3 class="font-semibold text-primary">EC2 Compute Quotas</h3>
                        <span class="bg-primary/10 text-primary border border-outline-variant px-2.5 py-0.5 rounded-full font-mono text-[10px] font-bold">
                            {{ count($instancesData) }} Active
                        </span>
                    </div>

                    @if (count($instancesData) > 0)
                        @php
                            $totalVcpus = 0;
                            $runningCount = 0;
                            $stoppedCount = 0;
                            foreach ($instancesData as $inst) {
                                $vcpu = match ($inst['instance_type']) {
                                    't2.micro' => 1,
                                    't2.small' => 2,
                                    't2.medium' => 4,
                                    default => 1,
                                };
                                $totalVcpus += $vcpu;
                                if ($inst['status'] === 'running') {
                                    $runningCount++;
                                } else {
                                    $stoppedCount++;
                                }
                            }
                        @endphp
                        <div class="grid grid-cols-3 gap-md font-mono text-center">
                            <div class="bg-surface-container-lowest p-2 border border-outline-variant rounded">
                                <span class="block text-[10px] text-on-surface-variant uppercase">Total vCPUs</span>
                                <span class="text-lg font-bold text-primary mt-1 block">{{ $totalVcpus }}</span>
                            </div>
                            <div class="bg-surface-container-lowest p-2 border border-outline-variant rounded">
                                <span class="block text-[10px] text-on-surface-variant uppercase text-secondary">Running</span>
                                <span class="text-lg font-bold text-primary mt-1 block">{{ $runningCount }}</span>
                            </div>
                            <div class="bg-surface-container-lowest p-2 border border-outline-variant rounded">
                                <span class="block text-[10px] text-on-surface-variant uppercase">Stopped</span>
                                <span class="text-lg font-bold text-primary mt-1 block">{{ $stoppedCount }}</span>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-6 text-on-surface-variant text-xs border border-dashed border-outline-variant rounded font-mono">
                            No active EC2 compute instances found.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <script>
            window.userBucketsData = @json($bucketsData);
        </script>

        <!-- S3 and EC2 Management Canvas -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-lg">
            <!-- EC2 Compute Section -->
            <div class="space-y-lg">
                <!-- Launch Instance Form -->
                <div id="launch-instance" class="stat-card">
                    <div class="border-b border-outline-variant pb-sm mb-md flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">dns</span>
                        <h3 class="font-semibold text-primary">Launch New EC2 Compute</h3>
                    </div>
                    <form action="{{ route('ec2.launch') }}" method="POST" class="space-y-md">
                        @csrf
                        <div>
                            <label class="block font-mono text-[10px] text-on-surface-variant mb-1.5 uppercase">Instance Name</label>
                            <input type="text" name="instance_name" class="w-full text-sm" placeholder="e.g., prod-web-server" required>
                            <span class="text-[10px] text-on-surface-variant mt-1 block">Supported format: letters, numbers, dot, hyphens, underscores.</span>
                        </div>
                        <div>
                            <label class="block font-mono text-[10px] text-on-surface-variant mb-1.5 uppercase">Compute Plan</label>
                            <select name="plan_id" class="w-full text-sm" required>
                                <option value="" disabled selected>-- Select Compute Plan --</option>
                                @foreach ($computePlans as $plan)
                                    <option value="{{ $plan->id }}">{{ $plan->plan_name }} ({{ $plan->compute_quota_vcpu }} vCPU) - ${{ $plan->monthly_price }}/mo</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn-primary w-full py-2 text-sm rounded">
                            Launch in MiniStack
                        </button>
                    </form>
                </div>

                <!-- Instance Explorer -->
                <div class="stat-card">
                    <div class="border-b border-outline-variant pb-sm mb-md flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">explore</span>
                            <h3 class="font-semibold text-primary">Instance Explorer</h3>
                        </div>
                    </div>

                    <div class="border border-outline-variant rounded overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                                <thead class="bg-surface border-b border-outline-variant">
                                    <tr class="font-mono text-on-surface-variant uppercase">
                                        <th class="px-3 py-3 w-1/3">Name</th>
                                        <th class="px-3 py-3">Instance ID</th>
                                        <th class="px-3 py-3">State</th>
                                        <th class="px-3 py-3 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="ec2-instance-body" class="divide-y divide-outline-variant">
                                    @forelse ($instancesData as $inst)
                                        <tr class="hover:bg-surface-container-highest transition-colors">
                                            <td class="px-3 py-3">
                                                <span class="font-semibold text-primary block">{{ $inst['instance_name'] }}</span>
                                                <span class="font-mono text-[10px] text-on-surface-variant">{{ $inst['instance_type'] }}</span>
                                            </td>
                                            <td class="px-3 py-3 font-mono">{{ $inst['instance_id'] }}</td>
                                            <td class="px-3 py-3">
                                                @if ($inst['status'] === 'running')
                                                    <span class="inline-flex items-center gap-1 bg-primary text-on-primary-fixed rounded-full px-2.5 py-0.5 font-mono text-[9px] font-bold uppercase">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-on-primary-fixed animate-pulse"></span>
                                                        Running
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 border border-outline-variant text-on-surface-variant rounded-full px-2.5 py-0.5 font-mono text-[9px] font-bold uppercase">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-on-surface-variant"></span>
                                                        Stopped
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-3 text-right">
                                                <div class="flex justify-end gap-1.5">
                                                    @if ($inst['status'] === 'running')
                                                        <form class="ec2-action-form" action="{{ route('ec2.stop') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="instance_id" value="{{ $inst['instance_id'] }}">
                                                            <button type="submit" class="p-1 text-on-surface-variant hover:text-primary hover:bg-surface-container rounded transition-colors" title="Stop Instance">
                                                                <span class="material-symbols-outlined text-sm">stop</span>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form class="ec2-action-form" action="{{ route('ec2.start') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="instance_id" value="{{ $inst['instance_id'] }}">
                                                            <button type="submit" class="p-1 text-on-surface-variant hover:text-primary hover:bg-surface-container rounded transition-colors" title="Start Instance">
                                                                <span class="material-symbols-outlined text-sm">play_arrow</span>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <form class="ec2-action-form" action="{{ route('ec2.terminate') }}" method="POST" onsubmit="return confirm('WARNING: Terminate instance {{ $inst['instance_id'] }}? Billing will stop and this cannot be undone.');">
                                                        @csrf
                                                        <input type="hidden" name="instance_id" value="{{ $inst['instance_id'] }}">
                                                        <button type="submit" class="p-1 text-on-surface-variant hover:text-error hover:bg-error/10 rounded transition-colors" title="Terminate Instance">
                                                            <span class="material-symbols-outlined text-sm">delete</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-8 text-center text-on-surface-variant font-mono">
                                                No compute instances found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- S3 Storage Section -->
            <div class="space-y-lg">
                <!-- Create Bucket Form -->
                <div class="stat-card">
                    <div class="border-b border-outline-variant pb-sm mb-md flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">hard_drive</span>
                        <h3 class="font-semibold text-primary">Provision Isolated S3 Storage</h3>
                    </div>
                    <form action="{{ route('s3.createBucket') }}" method="POST" class="space-y-md">
                        @csrf
                        <div>
                            <label class="block font-mono text-[10px] text-on-surface-variant mb-1.5 uppercase">Bucket Name</label>
                            <input type="text" name="bucket_name" class="w-full text-sm" placeholder="e.g., iaas-object-bucket" required>
                            <span class="text-[10px] text-on-surface-variant mt-1 block">Must be lowercase, alphanumeric, without spaces.</span>
                        </div>
                        <div>
                            <label class="block font-mono text-[10px] text-on-surface-variant mb-1.5 uppercase">Storage Plan</label>
                            <select name="plan_id" class="w-full text-sm" required>
                                <option value="" disabled selected>-- Select Storage Plan --</option>
                                @foreach ($storagePlans as $plan)
                                    <option value="{{ $plan->id }}">{{ $plan->plan_name }} ({{ $plan->storage_quota_gb }}GB) - ${{ $plan->monthly_price }}/mo</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn-primary w-full py-2 text-sm rounded">
                            Provision in MiniStack
                        </button>
                    </form>
                </div>

                <!-- Upload File Form -->
                <div id="upload-form" class="stat-card">
                    <div class="border-b border-outline-variant pb-sm mb-md flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">upload</span>
                        <h3 class="font-semibold text-primary">Upload File to S3 Bucket</h3>
                    </div>
                    <form action="{{ route('s3.uploadObject') }}" method="POST" enctype="multipart/form-data" class="space-y-md">
                        @csrf
                        <div>
                            <label class="block font-mono text-[10px] text-on-surface-variant mb-1.5 uppercase">Target Bucket</label>
                            @if (count($bucketsData) > 0)
                                <select name="bucket_name" class="w-full text-sm" required>
                                    <option value="" disabled selected>-- Select Target Bucket --</option>
                                    @foreach ($bucketsData as $bucket)
                                        <option value="{{ $bucket['name'] }}">{{ $bucket['name'] }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input type="text" disabled class="w-full text-sm bg-surface-container-lowest text-on-surface-variant cursor-not-allowed" placeholder="No active buckets available.">
                            @endif
                        </div>
                        <div>
                            <label class="block font-mono text-[10px] text-on-surface-variant mb-1.5 uppercase">Select Files</label>
                            <input type="file" name="files[]" multiple class="w-full text-xs bg-surface-container-lowest border border-outline-variant rounded p-2 text-on-surface file:mr-4 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-semibold file:bg-surface-container-highest file:text-primary hover:file:opacity-90" required>
                        </div>
                        <button type="submit" class="btn-primary w-full py-2 text-sm rounded" {{ count($bucketsData) === 0 ? 'disabled' : '' }}>
                            Upload to MiniStack
                        </button>
                    </form>
                </div>

                <!-- Storage Explorer & Terminate -->
                <div class="stat-card">
                    <div class="border-b border-outline-variant pb-sm mb-md flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">folder_open</span>
                        <h3 class="font-semibold text-primary">Storage Explorer</h3>
                    </div>
                    
                    <form id="explorer-form" action="{{ route('s3.viewFiles') }}" method="POST" class="flex gap-2 mb-md">
                        @csrf
                        @if (count($bucketsData) > 0)
                            <select name="bucket_name" class="flex-1 text-xs" required>
                                <option value="" disabled {{ !session('current_bucket') ? 'selected' : '' }}>Select bucket to explore...</option>
                                @foreach ($bucketsData as $bucket)
                                    <option value="{{ $bucket['name'] }}" {{ session('current_bucket') === $bucket['name'] ? 'selected' : '' }}>
                                        {{ $bucket['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" id="explorer-btn" class="btn-secondary px-3 py-1.5 text-xs rounded font-semibold whitespace-nowrap">Open Explorer</button>
                        @else
                            <input type="text" disabled class="flex-1 text-xs bg-surface-container-lowest text-on-surface-variant cursor-not-allowed" placeholder="No active buckets.">
                            <button type="button" disabled class="btn-secondary opacity-50 cursor-not-allowed px-3 py-1.5 text-xs rounded font-semibold whitespace-nowrap">Open Explorer</button>
                        @endif
                    </form>

                    <!-- File Explorer Table -->
                    <div id="file-explorer-container" class="border border-outline-variant rounded overflow-hidden mb-md {{ session('files') ? '' : 'hidden' }}">
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                                <thead class="bg-surface border-b border-outline-variant">
                                    <tr class="font-mono text-on-surface-variant uppercase">
                                        <th class="px-3 py-2">File Name</th>
                                        <th class="px-3 py-2">Size</th>
                                        <th class="px-3 py-2 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="file-explorer-body" class="divide-y divide-outline-variant">
                                    @if (session('files'))
                                        @forelse(session('files') as $file)
                                            <tr class="hover:bg-surface-container-highest transition-colors">
                                                <td class="px-3 py-2 font-semibold text-primary">{{ $file['Key'] }}</td>
                                                <td class="px-3 py-2 font-mono text-[10px]">{{ number_format($file['Size']) }} B</td>
                                                <td class="px-3 py-2 text-right">
                                                    <div class="flex justify-end gap-2">
                                                        <a href="{{ route('s3.downloadFile', ['bucket' => session('current_bucket'), 'key' => $file['Key']]) }}" class="text-primary hover:underline font-semibold">Download</a>
                                                        <form class="ajax-delete-form" action="{{ route('s3.deleteFile') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="bucket_name" value="{{ session('current_bucket') }}">
                                                            <input type="hidden" name="file_key" value="{{ $file['Key'] }}">
                                                            <button type="submit" class="text-error hover:underline font-semibold">Delete</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="px-3 py-4 text-center text-on-surface-variant font-mono">Bucket is empty.</td>
                                            </tr>
                                        @endforelse
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Danger Zone Terminate Bucket -->
                    <div id="danger-zone-container" class="border-t border-outline-variant pt-md {{ session('current_bucket') ? '' : 'hidden' }}">
                        <form action="{{ route('s3.deleteBucket') }}" method="POST" onsubmit="return confirm('WARNING: Terminate this storage bucket? Billing will stop and all stored files will be permanently deleted.');">
                            @csrf
                            <input type="hidden" name="bucket_name" id="danger-zone-bucket" value="{{ session('current_bucket') }}">
                            <button type="submit" class="text-xs text-error hover:bg-error/10 border border-error/30 hover:border-error px-3 py-1.5 rounded transition-all font-semibold flex items-center gap-xs">
                                <span class="material-symbols-outlined text-sm">delete_forever</span>
                                <span id="danger-zone-text">Terminate Service ({{ session('current_bucket') }})</span>
                            </button>
                        </form>
                    </div>

                    @if (session('current_bucket') && !session('files'))
                        <div class="border-t border-outline-variant pt-md mt-md">
                            <form action="{{ route('s3.deleteBucket') }}" method="POST" onsubmit="return confirm('WARNING: Terminate this storage bucket? Billing will stop and all stored files will be permanently deleted.');">
                                @csrf
                                <input type="hidden" name="bucket_name" value="{{ session('current_bucket') }}">
                                <button type="submit" class="text-xs text-error hover:bg-error/10 border border-error/30 hover:border-error px-3 py-1.5 rounded transition-all font-semibold flex items-center gap-xs">
                                    <span class="material-symbols-outlined text-sm">delete_forever</span>
                                    <span>Terminate Service ({{ session('current_bucket') }})</span>
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- ============================================================ --}}
    {{-- PAKET LANGGANAN TAB --}}
    {{-- ============================================================ --}}
    @if ($tab === 'plans')
        <div class="space-y-xl">
            <!-- Compute Plans -->
            <div>
                <h3 class="font-bold text-lg text-primary mb-md flex items-center gap-sm">
                    <span class="material-symbols-outlined">dns</span>
                    <span>EC2 Compute Tiers</span>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-lg">
                    @foreach ($computePlans as $plan)
                        <div class="bg-surface border border-outline-variant rounded-lg p-lg flex flex-col hover:border-primary transition-colors duration-300 relative overflow-hidden group">
                            <div class="absolute inset-0 bg-gradient-to-b from-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                            <div class="mb-md">
                                <h4 class="font-bold text-lg text-primary">{{ $plan->plan_name }}</h4>
                                <p class="text-xs text-on-surface-variant mt-1">Dedicated instance with high availability SLA.</p>
                            </div>
                            <div class="flex items-baseline gap-1 border-b border-outline-variant pb-md mb-md">
                                <span class="text-3xl font-bold text-primary">${{ number_format($plan->monthly_price) }}</span>
                                <span class="font-mono text-xs text-on-surface-variant">/mo</span>
                            </div>
                            <div class="flex-1 flex flex-col gap-sm mb-lg">
                                <div class="flex justify-between py-2 border-b border-outline-variant/30 text-xs font-mono">
                                    <span class="text-on-surface-variant flex items-center gap-2">
                                        <span class="material-symbols-outlined text-sm">memory</span> vCPUs
                                    </span>
                                    <span class="text-primary font-bold">{{ $plan->compute_quota_vcpu }} Cores</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-outline-variant/30 text-xs font-mono">
                                    <span class="text-on-surface-variant flex items-center gap-2">
                                        <span class="material-symbols-outlined text-sm">memory_alt</span> RAM limit
                                    </span>
                                    <span class="text-primary font-bold">{{ $plan->compute_quota_vcpu * 2 }} GB ECC</span>
                                </div>
                                <div class="flex justify-between py-2 text-xs font-mono">
                                    <span class="text-on-surface-variant flex items-center gap-2">
                                        <span class="material-symbols-outlined text-sm">network_check</span> Bandwidth
                                    </span>
                                    <span class="text-primary font-bold">1 Gbps Shared</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Storage Plans -->
            <div>
                <h3 class="font-bold text-lg text-primary mb-md flex items-center gap-sm mt-lg">
                    <span class="material-symbols-outlined">hard_drive</span>
                    <span>S3 Object Storage Tiers</span>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-lg">
                    @foreach ($storagePlans as $plan)
                        <div class="bg-surface border border-outline-variant rounded-lg p-lg flex flex-col hover:border-primary transition-colors duration-300 relative overflow-hidden group">
                            <div class="absolute inset-0 bg-gradient-to-b from-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                            <div class="mb-md">
                                <h4 class="font-bold text-lg text-primary">{{ $plan->plan_name }}</h4>
                                <p class="text-xs text-on-surface-variant mt-1">Multi-region redundancy object storage bucket.</p>
                            </div>
                            <div class="flex items-baseline gap-1 border-b border-outline-variant pb-md mb-md">
                                <span class="text-3xl font-bold text-primary">${{ number_format($plan->monthly_price) }}</span>
                                <span class="font-mono text-xs text-on-surface-variant">/mo</span>
                            </div>
                            <div class="flex-1 flex flex-col gap-sm mb-lg">
                                <div class="flex justify-between py-2 border-b border-outline-variant/30 text-xs font-mono">
                                    <span class="text-on-surface-variant flex items-center gap-2">
                                        <span class="material-symbols-outlined text-sm">storage</span> Storage Cap
                                    </span>
                                    <span class="text-primary font-bold">{{ $plan->storage_quota_gb }} GB SSD</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-outline-variant/30 text-xs font-mono">
                                    <span class="text-on-surface-variant flex items-center gap-2">
                                        <span class="material-symbols-outlined text-sm">cloud_upload</span> Transfer limit
                                    </span>
                                    <span class="text-primary font-bold">Unlimited</span>
                                </div>
                                <div class="flex justify-between py-2 text-xs font-mono">
                                    <span class="text-on-surface-variant flex items-center gap-2">
                                        <span class="material-symbols-outlined text-sm">security</span> Redundancy
                                    </span>
                                    <span class="text-primary font-bold">99.99999%</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- ============================================================ --}}
    {{-- KREDENSIAL API TAB --}}
    {{-- ============================================================ --}}
    @if ($tab === 'credentials')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-lg">
            <!-- Credentials List -->
            <div class="lg:col-span-2 space-y-md">
                <div class="stat-card">
                    <div class="border-b border-outline-variant pb-sm mb-md flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">key</span>
                        <h3 class="font-semibold text-primary">Akses Kredensial API Aktif</h3>
                    </div>

                    @if ($userCredentials->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($userCredentials as $cred)
                                <div class="bg-surface-container-lowest p-4 rounded border border-outline-variant relative">
                                    <div class="absolute top-3 right-3 font-mono text-[9px] font-bold text-on-primary-fixed bg-primary px-2 py-0.5 rounded uppercase">
                                        {{ $cred->resource_type }}
                                    </div>
                                    <h4 class="font-bold text-primary mb-3 text-sm">{{ $cred->instance_name }}</h4>

                                    <div class="mb-3">
                                        <span class="block font-mono text-[10px] text-on-surface-variant uppercase mb-1">Access Key ID:</span>
                                        <code class="bg-surface-container border border-outline-variant px-2 py-1 rounded text-xs text-primary block w-full truncate font-mono select-all">
                                            {{ $cred->access_key }}
                                        </code>
                                    </div>
                                    <div>
                                        <span class="block font-mono text-[10px] text-on-surface-variant uppercase mb-1">Secret Access Key:</span>
                                        <code class="bg-surface-container border border-outline-variant px-2 py-1 rounded text-xs text-primary block w-full truncate font-mono select-all">
                                            {{ decrypt($cred->secret_key_encrypted) }}
                                        </code>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-on-surface-variant italic border-l-2 border-outline-variant pl-3 font-mono">
                            Anda belum memiliki kunci API untuk layanan apa pun.
                        </p>
                    @endif
                </div>
            </div>

            <!-- Generate New Credentials -->
            <div class="stat-card h-fit">
                <div class="border-b border-outline-variant pb-sm mb-md flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">lock_reset</span>
                    <h3 class="font-semibold text-primary">Generate Kredensial Baru</h3>
                </div>

                @if ($availableResourcesForKey->count() > 0)
                    <form action="{{ route('s3.generateCredentials') }}" method="POST" class="space-y-md">
                        @csrf
                        <div>
                            <label class="block font-mono text-[10px] text-on-surface-variant mb-1.5 uppercase">Pilih Layanan Aktif</label>
                            <select name="provisioned_id" class="w-full text-sm" required>
                                <option value="" disabled selected>-- Select Active Resource --</option>
                                @foreach ($availableResourcesForKey as $res)
                                    <option value="{{ $res->id }}">
                                        [{{ strtoupper($res->resource_type) }}] - {{ $res->instance_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn-primary w-full py-2 text-sm rounded">
                            Request Access Key
                        </button>
                    </form>
                @else
                    <div class="bg-surface-container-lowest text-on-surface-variant p-4 rounded text-xs border border-outline-variant font-mono">
                        Semua layanan Anda saat ini sudah memiliki Kredensial API, atau Anda belum memiliki layanan aktif.
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- ============================================================ --}}
    {{-- AUDIT LOGS TAB --}}
    {{-- ============================================================ --}}
    @if ($tab === 'logs')
        <div class="stat-card">
            <div class="border-b border-outline-variant pb-sm mb-lg">
                <h3 class="font-semibold text-primary">Riwayat Aktivitas Penyewaan</h3>
            </div>

            <div class="space-y-4">
                @forelse ($recentActivities as $log)
                    <div class="flex items-start gap-4 pb-4 border-b border-outline-variant/30 last:border-0 last:pb-0">
                        <div class="flex-shrink-0 mt-0.5">
                            @if($log->action_type == 'create')
                                <div class="flex items-center justify-center h-8 w-8 rounded bg-primary text-on-primary-fixed">
                                    <span class="material-symbols-outlined text-sm">add</span>
                                </div>
                            @elseif($log->action_type == 'delete')
                                <div class="flex items-center justify-center h-8 w-8 rounded bg-error/15 text-error">
                                    <span class="material-symbols-outlined text-sm">delete</span>
                                </div>
                            @else
                                <div class="flex items-center justify-center h-8 w-8 rounded bg-surface-container-highest text-primary">
                                    <span class="material-symbols-outlined text-sm">info</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-center">
                                <p class="text-sm font-semibold text-primary capitalize">
                                    {{ $log->action_type }} Resource
                                </p>
                                <span class="font-mono text-[10px] text-on-surface-variant">
                                    {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y H:i:s') }}
                                </span>
                            </div>
                            <p class="text-xs text-on-surface-variant mt-1">
                                {{ $log->description }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-on-surface-variant text-sm border border-dashed border-outline-variant rounded">
                        Belum ada riwayat aktivitas penyewaan.
                    </div>
                @endforelse
            </div>
        </div>
    @endif

    {{-- Script for AJAX Forms --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // FEATURE 1: SEAMLESS AJAX DELETE
            function bindAjaxDeleteForms() {
                const deleteForms = document.querySelectorAll('.ajax-delete-form');
                deleteForms.forEach(form => {
                    const newForm = form.cloneNode(true);
                    form.parentNode.replaceChild(newForm, form);

                    newForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        if (!confirm('Delete this file?')) return;

                        const formData = new FormData(newForm);
                        const tableRow = newForm.closest('tr');
                        tableRow.style.opacity = '0.5';

                        fetch(newForm.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'Accept': 'application/json'
                                }
                            })
                            .then(res => res.ok ? res.json() : Promise.reject('Server error'))
                            .then(data => {
                                if (data.success) tableRow.style.display = 'none';
                                else {
                                    alert('Failed to delete.');
                                    tableRow.style.opacity = '1';
                                }
                            })
                            .catch(() => {
                                alert('Error processing delete.');
                                tableRow.style.opacity = '1';
                            });
                    });
                });
            }

            bindAjaxDeleteForms();

            // FEATURE 2: AJAX STORAGE EXPLORER
            const explorerForm = document.getElementById('explorer-form');
            if (explorerForm) {
                explorerForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(explorerForm);
                    const bucketName = formData.get('bucket_name');
                    if (!bucketName) return;

                    const btn = document.getElementById('explorer-btn');
                    const originalText = btn.innerText;
                    btn.innerText = 'Loading...';
                    btn.disabled = true;

                    fetch(explorerForm.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            btn.innerText = originalText;
                            btn.disabled = false;

                            if (data.success) {
                                const container = document.getElementById('file-explorer-container');
                                const tbody = document.getElementById('file-explorer-body');

                                container.classList.remove('hidden');
                                tbody.innerHTML = ''; // Clear old table data

                                if (data.files.length === 0) {
                                    tbody.innerHTML =
                                        `<tr><td colspan="3" class="px-3 py-4 text-center text-on-surface-variant font-mono">Bucket is empty.</td></tr>`;
                                } else {
                                    data.files.forEach(file => {
                                        const sizeFmt = new Intl.NumberFormat().format(file.Size);
                                        const downloadUrl = `/s3/download/${bucketName}/${file.Key}`;
                                        const token = document.querySelector('input[name="_token"]').value;

                                        tbody.innerHTML += `
                                            <tr class="hover:bg-surface-container-highest transition-colors">
                                                <td class="px-3 py-2 font-semibold text-primary">${file.Key}</td>
                                                <td class="px-3 py-2 font-mono text-[10px]">${sizeFmt} B</td>
                                                <td class="px-3 py-2 text-right">
                                                    <div class="flex justify-end gap-2">
                                                        <a href="${downloadUrl}" class="text-primary hover:underline font-semibold">Download</a>
                                                        <form class="ajax-delete-form" action="/s3/delete-file" method="POST">
                                                            <input type="hidden" name="_token" value="${token}">
                                                            <input type="hidden" name="bucket_name" value="${bucketName}">
                                                            <input type="hidden" name="file_key" value="${file.Key}">
                                                            <button type="submit" class="text-error hover:underline font-semibold">Delete</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        `;
                                    });
                                    bindAjaxDeleteForms();
                                }

                                document.getElementById('danger-zone-container').classList.remove('hidden');
                                document.getElementById('danger-zone-bucket').value = bucketName;
                                document.getElementById('danger-zone-text').innerText = `Terminate Service (${bucketName})`;
                            }
                        })
                        .catch(() => {
                            btn.innerText = originalText;
                            btn.disabled = false;
                            alert('Network error while fetching files.');
                        });
                });
            }

            // FEATURE 3: DYNAMIC BUCKET QUOTA SELECTOR
            const bucketSelector = document.getElementById('bucket-selector');
            if (bucketSelector && window.userBucketsData) {
                bucketSelector.addEventListener('change', function() {
                    const selectedIndex = this.value;
                    const bucket = window.userBucketsData[selectedIndex];

                    document.getElementById('display-size-text').innerText =
                        `${bucket.displaySize} / ${bucket.totalGB} GB (${Math.round(bucket.percentage)}%)`;
                    document.getElementById('available-storage-text').innerText = bucket.available;

                    const progressBar = document.getElementById('capacity-progress-bar');
                    progressBar.style.width = bucket.percentage + '%';
                });
            }

            // FEATURE 4: EC2 INSTANCE EXPLORER REFRESH
            const ec2RefreshBtn = document.getElementById('ec2-refresh-btn');
            if (ec2RefreshBtn) {
                ec2RefreshBtn.addEventListener('click', function() {
                    const originalText = this.innerHTML;
                    this.innerHTML = '<span class="material-symbols-outlined text-sm animate-spin">sync</span> Syncing...';
                    this.disabled = true;

                    fetch('/ec2/list', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            this.innerHTML = originalText;
                            this.disabled = false;

                            if (data.success) {
                                const tbody = document.getElementById('ec2-instance-body');
                                tbody.innerHTML = '';

                                if (data.instances.length === 0) {
                                    tbody.innerHTML =
                                        `<tr><td colspan="4" class="px-4 py-8 text-center text-on-surface-variant font-mono">No compute instances found.</td></tr>`;
                                } else {
                                    data.instances.forEach(inst => {
                                        const stateBadge = inst.status === 'running' ?
                                            `<span class="inline-flex items-center gap-1 bg-primary text-on-primary-fixed rounded-full px-2.5 py-0.5 font-mono text-[9px] font-bold uppercase"><span class="w-1.5 h-1.5 rounded-full bg-on-primary-fixed animate-pulse"></span>Running</span>` :
                                            `<span class="inline-flex items-center gap-1 border border-outline-variant text-on-surface-variant rounded-full px-2.5 py-0.5 font-mono text-[9px] font-bold uppercase"><span class="w-1.5 h-1.5 rounded-full bg-on-surface-variant"></span>Stopped</span>`;

                                        const actionBtn = inst.status === 'running' ?
                                            `<form class="ec2-action-form" action="/ec2/stop" method="POST">
                                                <input type="hidden" name="_token" value="${document.querySelector('input[name="_token"]').value}">
                                                <input type="hidden" name="instance_id" value="${inst.instance_id}">
                                                <button type="submit" class="p-1 text-on-surface-variant hover:text-primary hover:bg-surface-container rounded transition-colors" title="Stop Instance"><span class="material-symbols-outlined text-sm">stop</span></button>
                                            </form>` :
                                            `<form class="ec2-action-form" action="/ec2/start" method="POST">
                                                <input type="hidden" name="_token" value="${document.querySelector('input[name="_token"]').value}">
                                                <input type="hidden" name="instance_id" value="${inst.instance_id}">
                                                <button type="submit" class="p-1 text-on-surface-variant hover:text-primary hover:bg-surface-container rounded transition-colors" title="Start Instance"><span class="material-symbols-outlined text-sm">play_arrow</span></button>
                                            </form>`;

                                        tbody.innerHTML += `
                                            <tr class="hover:bg-surface-container-highest transition-colors">
                                                <td class="px-3 py-3">
                                                    <span class="font-semibold text-primary block">${inst.instance_name}</span>
                                                    <span class="font-mono text-[10px] text-on-surface-variant">${inst.instance_type}</span>
                                                </td>
                                                <td class="px-3 py-3 font-mono">${inst.instance_id}</td>
                                                <td class="px-3 py-3">${stateBadge}</td>
                                                <td class="px-3 py-3 text-right">
                                                    <div class="flex justify-end gap-1.5">
                                                        ${actionBtn}
                                                        <form class="ec2-action-form" action="/ec2/terminate" method="POST" onsubmit="return confirm('WARNING: Terminate instance ${inst.instance_id}? Billing will stop and this cannot be undone.');">
                                                            <input type="hidden" name="_token" value="${document.querySelector('input[name="_token"]').value}">
                                                            <input type="hidden" name="instance_id" value="${inst.instance_id}">
                                                            <button type="submit" class="p-1 text-on-surface-variant hover:text-error hover:bg-error/10 rounded transition-colors" title="Terminate Instance"><span class="material-symbols-outlined text-sm">delete</span></button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        `;
                                    });
                                    bindEc2ActionForms();
                                }
                            }
                        })
                        .catch(() => {
                            this.innerHTML = originalText;
                            this.disabled = false;
                            alert('Network error while refreshing instances.');
                        });
                });
            }

            // FEATURE 5: EC2 AJAX ACTIONS
            function bindEc2ActionForms() {
                const actionForms = document.querySelectorAll('.ec2-action-form');
                actionForms.forEach(form => {
                    const newForm = form.cloneNode(true);
                    form.parentNode.replaceChild(newForm, form);

                    newForm.addEventListener('submit', function(e) {
                        if (this.onsubmit && !this.onsubmit()) {
                            e.preventDefault();
                            return;
                        }
                        e.preventDefault();

                        const formData = new FormData(newForm);
                        const btn = newForm.querySelector('button');
                        const originalHtml = btn.innerHTML;
                        btn.innerHTML = '<span class="material-symbols-outlined text-sm animate-spin">sync</span>';
                        btn.disabled = true;

                        fetch(newForm.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'Accept': 'application/json'
                                }
                            })
                            .then(res => res.ok ? res.json() : Promise.reject('Server error'))
                            .then(data => {
                                if (data.success) {
                                    if (ec2RefreshBtn) ec2RefreshBtn.click();
                                } else {
                                    alert('Action failed.');
                                    btn.innerHTML = originalHtml;
                                    btn.disabled = false;
                                }
                            })
                            .catch(() => {
                                alert('Error processing action.');
                                btn.innerHTML = originalHtml;
                                btn.disabled = false;
                            });
                    });
                });
            }

            bindEc2ActionForms();
        });
    </script>
</x-app-layout>
