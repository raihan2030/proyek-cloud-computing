<x-app-layout>
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Global Alerts -->
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition
                     class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center justify-between shadow-sm">
                    <span>{{ session('success') }}</span>
                    <button @click="show = false" class="text-green-500 hover:text-green-700 ml-4 shrink-0 transition focus:outline-none" title="Tutup">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif
            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" x-transition
                     class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center justify-between shadow-sm">
                    <span>{{ session('error') }}</span>
                    <button @click="show = false" class="text-red-500 hover:text-red-700 ml-4 shrink-0 transition focus:outline-none" title="Tutup">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            <div x-data="{ activeTab: 'overview' }" class="flex flex-col lg:flex-row gap-8">
                <!-- Sidebar Navigation -->
                <aside class="w-full lg:w-64 shrink-0">
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-4 space-y-1">
                        <div class="px-3 py-2 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">
                            Layanan CloudOS
                        </div>
                        
                        <!-- Overview Tab Button -->
                        <button @click="activeTab = 'overview'" 
                           :class="activeTab === 'overview' ? 'bg-gray-800 text-white shadow-md shadow-slate-200' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                           class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all text-left">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                            </svg>
                            <span>Ringkasan</span>
                        </button>

                        <!-- S3 Storage Tab Button -->
                        <button @click="activeTab = 's3'" 
                           :class="activeTab === 's3' ? 'bg-gray-800 text-white shadow-md shadow-slate-200' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                           class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all text-left">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <span>S3 Object Storage</span>
                        </button>

                        <!-- EC2 Compute Tab Button -->
                        <button @click="activeTab = 'ec2'" 
                           :class="activeTab === 'ec2' ? 'bg-gray-800 text-white shadow-md shadow-slate-200' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                           class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all text-left">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 5h10a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2z" />
                            </svg>
                            <span>EC2 Compute</span>
                        </button>
                    </div>
                </aside>

                <!-- Content Area -->
                <main class="flex-1 space-y-6">
                    
                    <!-- TAB 1: OVERVIEW -->
                    <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                        
                        <!-- Metrics Cards Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-indigo-500 hover:shadow-md transition">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-black-500 bold uppercase tracking-wider">Total Resources</p>
                                        <h4 class="text-3xl font-bold text-gray-800 mt-1">{{ $totalResources }}</h4>
                                    </div>
                                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-full">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-400 mt-2">Active instances & storage volumes</p>
                            </div>

                            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500 hover:shadow-md transition">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-black-500 bold uppercase tracking-wider">Active Services</p>
                                        <h4 class="text-3xl font-bold text-gray-800 mt-1">{{ $activeServices }}</h4>
                                    </div>
                                    <div class="p-3 bg-green-50 text-green-600 rounded-full">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-400 mt-2">Running cloud subscriptions</p>
                            </div>

                            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-amber-500 hover:shadow-md transition">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-black-500 bold uppercase tracking-wider">Monthly Spend</p>
                                        <h4 class="text-3xl font-bold text-gray-800 mt-1">Rp {{ number_format($monthlyBill, 2, ',', '.') }}</h4>
                                    </div>
                                    <div class="p-3 bg-amber-50 text-amber-600 rounded-full">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-400 mt-2">Projected cost of active services</p>
                            </div>
                        </div>

                        <!-- API Credentials Manager -->
                        <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <h3 class="text-lg font-bold mb-4">Akses Kredensial API per Layanan</h3>

                                @if ($userCredentials->count() > 0)
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                        @foreach ($userCredentials as $cred)
                                            <div class="bg-gray-50 p-4 rounded border border-gray-200 shadow-sm relative">
                                                <div class="absolute top-3 right-3 text-xs font-bold text-white px-2 py-1 rounded {{ $cred->resource_type == 'storage' ? 'bg-blue-500' : 'bg-orange-500' }}">
                                                    {{ strtoupper($cred->resource_type) }}
                                                </div>
                                                <h4 class="font-bold text-gray-800 mb-3">{{ $cred->instance_name }}</h4>

                                                <div class="mb-2 text-sm">
                                                    <span class="block font-semibold text-gray-600">Access Key:</span>
                                                    <code class="bg-white px-2 py-1 rounded border text-blue-600 block w-full truncate">{{ $cred->access_key }}</code>
                                                </div>
                                                <div class="text-sm">
                                                    <span class="block font-semibold text-gray-600">Secret Key:</span>
                                                    <code class="bg-white px-2 py-1 rounded border text-red-600 block w-full truncate">{{ decrypt($cred->secret_key_encrypted) }}</code>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="mb-4 text-gray-600 italic border-l-4 border-gray-400 pl-4">Anda belum memiliki kunci API untuk layanan apa pun.</p>
                                @endif

                                <hr class="my-4 border-gray-200">

                                <h4 class="font-semibold text-gray-700 mb-2">Generate Kredensial Baru</h4>
                                @if ($availableResourcesForKey->count() > 0)
                                    <form action="{{ route('s3.generateCredentials') }}" method="POST" class="flex flex-col md:flex-row gap-3 items-end">
                                        @csrf
                                        <div class="w-full md:w-1/2">
                                            <label class="block text-sm font-medium text-gray-600 mb-1">Pilih Layanan Layak Kunci API</label>
                                            <select name="provisioned_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                                <option value="" disabled selected>-- Pilih Resource --</option>
                                                @foreach ($availableResourcesForKey as $res)
                                                    <option value="{{ $res->id }}">
                                                        [{{ strtoupper($res->resource_type) }}] - {{ $res->instance_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="submit" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded w-full md:w-auto shadow-sm transition">
                                            Request Access Key
                                        </button>
                                    </form>
                                @else
                                    <div class="bg-yellow-50 text-yellow-700 p-3 rounded text-sm border border-yellow-200">
                                        Semua layanan Anda saat ini sudah memiliki Kredensial API, atau Anda belum memiliki layanan aktif sama sekali.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- S3 & EC2 Resource Quotas (Read-Only Summaries) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- S3 Storage Quota summary card -->
                            <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-blue-500 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-lg font-semibold text-gray-800">S3 Storage Quotas</h3>
                                        <div class="bg-blue-50 text-blue-600 rounded-full px-3 py-1 text-xs font-bold">
                                            {{ count($bucketsData) }} Active
                                        </div>
                                    </div>

                                    @if (count($bucketsData) > 0)
                                        <div class="mb-5">
                                            <label class="block text-xs font-medium text-gray-500 mb-1">Select Bucket to View</label>
                                            <select id="bucket-selector" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm bg-gray-50">
                                                @foreach ($bucketsData as $index => $bucket)
                                                    <option value="{{ $index }}">{{ $bucket['name'] }} ({{ $bucket['totalGB'] }}GB Plan)</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-4">
                                            <div class="flex justify-between items-center mt-4 mb-2">
                                                <span class="text-sm font-medium text-gray-600">Capacity Used</span>
                                                <span id="display-size-text" class="text-sm font-bold text-gray-800">
                                                    {{ $bucketsData[0]['displaySize'] }} / {{ $bucketsData[0]['totalGB'] }} GB
                                                    ({{ number_format($bucketsData[0]['percentage'], 0) }}%)
                                                </span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div id="capacity-progress-bar" class="{{ $bucketsData[0]['colorClass'] }} h-2 rounded-full transition-all duration-500" style="width: {{ $bucketsData[0]['percentage'] }}%"></div>
                                            </div>
                                        </div>

                                        <div class="space-y-2 mb-4">
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-600">Available Storage</span>
                                                <span id="available-storage-text" class="font-semibold text-gray-800">{{ $bucketsData[0]['available'] }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-center py-6 text-gray-500 text-sm border-2 border-dashed border-gray-200 rounded-md">
                                            No active buckets.<br>Provision a new bucket to see quotas here.
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <script>
                                window.userBucketsData = @json($bucketsData);
                            </script>

                            <!-- EC2 Compute Quota summary card -->
                            <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-orange-500 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-lg font-semibold text-gray-800">EC2 Compute Quotas</h3>
                                        <div class="bg-orange-50 text-orange-600 rounded-full px-3 py-1 text-xs font-bold">
                                            {{ count($instancesData) }} Active
                                        </div>
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
                                        <div class="space-y-3 mb-4">
                                            <div class="bg-gray-50 rounded p-3">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-sm font-medium text-gray-600">Total vCPUs</span>
                                                    <span class="text-xl font-bold text-gray-800">{{ $totalVcpus }} vCPU</span>
                                                </div>
                                            </div>

                                            <div class="flex justify-between text-sm bg-gray-50 rounded p-3">
                                                <span class="text-gray-600">Running Instances</span>
                                                <span class="font-semibold text-green-600">{{ $runningCount }}</span>
                                            </div>

                                            <div class="flex justify-between text-sm bg-gray-50 rounded p-3">
                                                <span class="text-gray-600">Stopped Instances</span>
                                                <span class="font-semibold text-yellow-600">{{ $stoppedCount }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-center py-6 text-gray-500 text-sm border-2 border-dashed border-gray-200 rounded-md">
                                            No active instances.<br>Launch one to see compute quotas here.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- System Health Status -->
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Health Status</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg border border-slate-200/55">
                                    <div class="flex-shrink-0">
                                        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                                            <svg class="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">S3 Storage</p>
                                        <p class="text-xs text-green-600 font-bold">Fully Operational</p>
                                        <p class="text-xs text-gray-500 mt-1">All systems nominal</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg border border-slate-200/55">
                                    <div class="flex-shrink-0">
                                        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                                            <svg class="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">EC2 Compute</p>
                                        <p class="text-xs text-green-600 font-bold">Fully Operational</p>
                                        <p class="text-xs text-gray-500 mt-1">All systems nominal</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Rental Activity Logs -->
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Aktivitas Penyewaan</h3>
                            <div class="space-y-4">
                                @forelse ($recentActivities as $log)
                                    <div class="flex items-start gap-4 pb-4 border-b border-gray-200 last:border-0">
                                        <div class="flex-shrink-0 mt-1">
                                            @if($log->action_type == 'create')
                                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-green-100">
                                                    <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                </div>
                                            @elseif($log->action_type == 'delete')
                                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-red-100">
                                                    <svg class="h-4 w-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </div>
                                            @else
                                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-100">
                                                    <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-800 capitalize">
                                                {{ $log->action_type }} Resource
                                            </p>
                                            <p class="text-xs text-gray-600 mt-1">
                                                {{ $log->description }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-6 text-gray-500 text-sm border-2 border-dashed border-gray-200 rounded-md">
                                        Belum ada riwayat aktivitas penyewaan.
                                    </div>
                                @endforelse
                            </div>
                        </div>

                    </div> <!-- End of TAB 1 -->

                    <!-- TAB 2: S3 STORAGE -->
                    <div x-show="activeTab === 's3'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6" style="display: none;">
                        
                        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-800">S3 Storage Management (Test Workspace)</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Create bucket form --}}
                                <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 hover:shadow-md transition">
                                    <h4 class="font-medium text-gray-800 mb-4 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Create Isolated Bucket
                                    </h4>
                                    <form action="{{ route('s3.createBucket') }}" method="POST">
                                        @csrf
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Bucket Name</label>
                                        <p class="text-xs text-gray-500 mb-2">Must be lowercase, no spaces.</p>
                                        <input type="text" name="bucket_name"
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mb-4"
                                            placeholder="e.g., iaas-firas-123" required>

                                        <label class="block text-sm font-medium text-gray-700 mb-1">Storage Plan</label>
                                        <select name="plan_id"
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mb-4"
                                            required>
                                            <option value="" disabled selected>Select a plan...</option>
                                            @foreach ($storagePlans as $plan)
                                                <option value="{{ $plan->id }}">{{ $plan->plan_name }} ({{ $plan->storage_quota_gb }}GB) - Rp {{ number_format($plan->monthly_price, 0, ',', '.') }}/mo</option>
                                            @endforeach
                                        </select>

                                        <button type="submit"
                                            class="w-full bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition shadow-sm">Provision in MiniStack</button>
                                    </form>
                                </div>

                                {{-- File upload form --}}
                                <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 hover:shadow-md transition" id="upload-form">
                                    <h4 class="font-medium text-gray-800 mb-4 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                        </svg>
                                        Upload File to Bucket
                                    </h4>
                                    <form action="{{ route('s3.uploadObject') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Select Active Bucket</label>
                                        @if (isset($bucketsData) && count($bucketsData) > 0)
                                            <select name="bucket_name"
                                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mb-4"
                                                required>
                                                <option value="" disabled {{ !session('current_bucket') ? 'selected' : '' }}>Choose target bucket...</option>
                                                @foreach ($bucketsData as $bucket)
                                                    <option value="{{ $bucket['name'] }}"
                                                        {{ session('current_bucket') === $bucket['name'] ? 'selected' : '' }}>
                                                        {{ $bucket['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input type="text" disabled
                                                class="w-full border-gray-300 rounded-md shadow-sm bg-gray-100 text-gray-500 mb-4 cursor-not-allowed"
                                                placeholder="Please create a bucket first.">
                                        @endif

                                        <label class="block text-sm font-medium text-gray-700 mb-1">Files</label>
                                        <input type="file" name="files[]" multiple
                                            class="w-full border border-gray-300 rounded-md shadow-sm p-2 mb-4 bg-white" required>

                                        <button type="submit"
                                            class="w-full bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition shadow-sm"
                                            {{ count($bucketsData) === 0 ? 'disabled' : '' }}>Upload Objects</button>
                                    </form>
                                </div>
                            </div>

                            <hr class="my-6 border-gray-200">

                            <!-- File Explorer -->
                            <div class="bg-gray-50 p-5 rounded-lg border border-gray-200">
                                <h4 class="font-medium text-gray-800 mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Storage Explorer & Termination
                                </h4>

                                <form id="explorer-form" action="{{ route('s3.viewFiles') }}" method="POST" class="flex gap-2 mb-4">
                                    @csrf
                                    @if (isset($bucketsData) && count($bucketsData) > 0)
                                        <select name="bucket_name"
                                            class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            required>
                                            <option value="" disabled {{ !session('current_bucket') ? 'selected' : '' }}>Select a bucket to view files...</option>
                                            @foreach ($bucketsData as $bucket)
                                                <option value="{{ $bucket['name'] }}"
                                                    {{ session('current_bucket') === $bucket['name'] ? 'selected' : '' }}>
                                                    {{ $bucket['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" id="explorer-btn"
                                            class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700">Open Explorer</button>
                                    @else
                                        <input type="text" disabled
                                            class="flex-1 border-gray-300 rounded-md shadow-sm bg-gray-100 text-gray-500 cursor-not-allowed"
                                            placeholder="No active buckets available.">
                                        <button type="button" disabled
                                            class="bg-gray-400 text-white px-4 py-2 rounded-md cursor-not-allowed">Open Explorer</button>
                                    @endif
                                </form>

                                <div id="file-explorer-container" class="bg-white border rounded-md overflow-hidden mb-4 {{ session('files') ? '' : 'hidden' }}">
                                    <table class="w-full text-sm text-left text-gray-500">
                                        <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                            <tr>
                                                <th class="px-4 py-3">File Name</th>
                                                <th class="px-4 py-3">Size (Bytes)</th>
                                                <th class="px-4 py-3 text-right">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="file-explorer-body">
                                            @if (session('files'))
                                                @forelse(session('files') as $file)
                                                    <tr class="border-b">
                                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $file['Key'] }}</td>
                                                        <td class="px-4 py-3">{{ number_format($file['Size']) }}</td>
                                                        <td class="px-4 py-3 text-right flex justify-end gap-2">
                                                            <a href="{{ route('s3.downloadFile', ['bucket' => session('current_bucket'), 'key' => $file['Key']]) }}"
                                                                class="text-blue-600 hover:underline">Download</a>
                                                            <form class="ajax-delete-form" action="{{ route('s3.deleteFile') }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="bucket_name" value="{{ session('current_bucket') }}">
                                                                <input type="hidden" name="file_key" value="{{ $file['Key'] }}">
                                                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="px-4 py-4 text-center text-gray-500">Bucket is empty.</td>
                                                    </tr>
                                                @endforelse
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                <div id="danger-zone-container" class="mt-4 pt-4 border-t border-gray-200 {{ session('current_bucket') ? '' : 'hidden' }}">
                                    
                                    <!-- Modify Subscription Plan Feature -->
                                    <div class="mb-5 pb-5 border-b border-gray-200">
                                        <h5 class="text-sm font-semibold text-gray-800 mb-2">Modify Storage Plan</h5>
                                        <form action="{{ route('s3.modifyPlan') }}" method="POST" class="flex flex-col sm:flex-row items-end gap-3">
                                            @csrf
                                            <input type="hidden" name="bucket_name" id="modify-plan-bucket" value="{{ session('current_bucket') }}">
                                            <div class="flex-1 w-full">
                                                <select name="target_plan_id" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                                    <option value="" disabled selected>Select a new storage plan...</option>
                                                    @foreach ($storagePlans as $plan)
                                                        <option value="{{ $plan->id }}">
                                                            {{ $plan->plan_name }} ({{ $plan->storage_quota_gb }}GB) - Rp {{ number_format($plan->monthly_price, 0, ',', '.') }}/mo
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <button type="submit" class="w-full sm:w-auto bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700 text-sm font-bold transition">Change Plan</button>
                                        </form>
                                    </div>

                                    <form action="{{ route('s3.deleteBucket') }}" method="POST" onsubmit="return confirm('WARNING: Are you sure you want to terminate this bucket? This will stop billing and cannot be undone.');">
                                        @csrf
                                        <input type="hidden" name="bucket_name" id="danger-zone-bucket" value="{{ session('current_bucket') }}">
                                        <button type="submit" class="text-sm text-red-600 font-medium hover:text-red-800 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            <span id="danger-zone-text">Terminate Service ({{ session('current_bucket') }})</span>
                                        </button>
                                    </form>
                                </div>

                                @if (session('current_bucket'))
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <!-- Modify Subscription Plan Feature -->
                                        <div class="mb-5 pb-5 border-b border-gray-200">
                                            <h5 class="text-sm font-semibold text-gray-800 mb-2">Modify Storage Plan</h5>
                                            <form action="{{ route('s3.modifyPlan') }}" method="POST" class="flex flex-col sm:flex-row items-end gap-3">
                                                @csrf
                                                <input type="hidden" name="bucket_name" value="{{ session('current_bucket') }}">
                                                <div class="flex-1 w-full">
                                                    <select name="target_plan_id" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                                        <option value="" disabled selected>Select a new storage plan...</option>
                                                        @foreach ($storagePlans as $plan)
                                                            <option value="{{ $plan->id }}">
                                                                {{ $plan->plan_name }} ({{ $plan->storage_quota_gb }}GB) - Rp {{ number_format($plan->monthly_price, 0, ',', '.') }}/mo
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <button type="submit" class="w-full sm:w-auto bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700 text-sm font-bold transition">Change Plan</button>
                                            </form>
                                        </div>

                                        <form action="{{ route('s3.deleteBucket') }}" method="POST" onsubmit="return confirm('WARNING: Are you sure you want to terminate this bucket? This will stop billing and cannot be undone.');">
                                            @csrf
                                            <input type="hidden" name="bucket_name" value="{{ session('current_bucket') }}">
                                            <button type="submit" class="text-sm text-red-600 font-medium hover:text-red-800 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Terminate Service ({{ session('current_bucket') }})
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div> <!-- End of TAB 2 -->

                    <!-- TAB 3: EC2 COMPUTE -->
                    <div x-show="activeTab === 'ec2'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6" style="display: none;">
                        
                        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-800">EC2 Compute Management</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Launch Instance Form --}}
                                <div id="ec2-launch-form" class="bg-gray-50 p-5 rounded-lg border border-gray-200 hover:shadow-md transition">
                                    <h4 class="font-medium text-gray-800 mb-4 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                        Launch New Instance
                                    </h4>
                                    <form action="{{ route('ec2.launch') }}" method="POST">
                                        @csrf
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Instance Name</label>
                                        <p class="text-xs text-gray-500 mb-2">Letters, numbers, dots, hyphens, underscores.</p>
                                        <input type="text" name="instance_name"
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 mb-4"
                                            placeholder="e.g., web-server-01" required>

                                        <label class="block text-sm font-medium text-gray-700 mb-1">Compute Plan</label>
                                        <select name="plan_id"
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 mb-4"
                                            required>
                                            <option value="" disabled selected>Select a plan...</option>
                                            @foreach ($computePlans as $plan)
                                                <option value="{{ $plan->id }}">{{ $plan->plan_name }} ({{ $plan->compute_quota_vcpu }} vCPU) - Rp {{ number_format($plan->monthly_price, 0, ',', '.') }}/mo</option>
                                            @endforeach
                                        </select>

                                        <button type="submit"
                                            class="w-full bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition shadow-sm">Launch in MiniStack</button>
                                    </form>
                                </div>

                                {{-- Instance Explorer --}}
                                <div id="ec2-explorer-section" class="bg-gray-50 p-5 rounded-lg border border-gray-200 hover:shadow-md transition">
                                    <h4 class="font-medium text-gray-800 mb-4 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Instance Explorer
                                    </h4>

                                    <button type="button" id="ec2-refresh-btn"
                                        class="w-full bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition mb-4">Refresh Instances</button>

                                    <div id="ec2-instance-container" class="bg-white border rounded-md overflow-hidden">
                                        <table class="w-full text-sm text-left text-gray-500">
                                            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                                <tr>
                                                    <th class="px-3 py-3">Name</th>
                                                    <th class="px-3 py-3">Instance ID</th>
                                                    <th class="px-3 py-3">Type</th>
                                                    <th class="px-3 py-3">State</th>
                                                    <th class="px-3 py-3 text-right">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="ec2-instance-body">
                                                @if (count($instancesData) > 0)
                                                    @foreach ($instancesData as $inst)
                                                        <tr class="border-b">
                                                            <td class="px-3 py-3 font-medium text-gray-900">{{ $inst['instance_name'] }}</td>
                                                            <td class="px-3 py-3 font-mono text-xs">{{ $inst['instance_id'] }}</td>
                                                            <td class="px-3 py-3">{{ $inst['instance_type'] }}</td>
                                                            <td class="px-3 py-3">
                                                                @if ($inst['status'] === 'running')
                                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">● running</span>
                                                                @else
                                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">● stopped</span>
                                                                @endif
                                                            </td>
                                                            <td class="px-3 py-3 text-right">
                                                                <div class="flex justify-end gap-1">
                                                                    @if ($inst['status'] === 'running')
                                                                        <form class="ec2-action-form" action="{{ route('ec2.stop') }}" method="POST">
                                                                            @csrf
                                                                            <input type="hidden" name="instance_id" value="{{ $inst['instance_id'] }}">
                                                                            <button type="submit" class="text-yellow-600 hover:underline text-xs">Stop</button>
                                                                        </form>
                                                                    @else
                                                                        <form class="ec2-action-form" action="{{ route('ec2.start') }}" method="POST">
                                                                            @csrf
                                                                            <input type="hidden" name="instance_id" value="{{ $inst['instance_id'] }}">
                                                                            <button type="submit" class="text-green-600 hover:underline text-xs">Start</button>
                                                                        </form>
                                                                    @endif
                                                                    <form class="ec2-action-form" action="{{ route('ec2.terminate') }}" method="POST" onsubmit="return confirm('WARNING: Terminate instance {{ $inst['instance_id'] }}? Billing will stop and this cannot be undone.');">
                                                                        @csrf
                                                                        <input type="hidden" name="instance_id" value="{{ $inst['instance_id'] }}">
                                                                        <button type="submit" class="text-red-600 hover:underline text-xs">Terminate</button>
                                                                    </form>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="5" class="px-4 py-4 text-center text-gray-500">No active instances. Launch one to get started.</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div> <!-- End of TAB 3 -->

                </main>
            </div>

        </div>
    </div>

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


            // --- FEATURE: AJAX STORAGE EXPLORER ---
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
                                        `<tr><td colspan="3" class="px-4 py-4 text-center text-gray-500">Bucket is empty.</td></tr>`;
                                } else {
                                    data.files.forEach(file => {
                                        const sizeFmt = new Intl.NumberFormat().format(file.Size);
                                        const downloadUrl = `/s3/download/${bucketName}/${file.Key}`;
                                        const token = document.querySelector('input[name="_token"]').value;

                                        tbody.innerHTML += `
                                    <tr class="border-b">
                                        <td class="px-4 py-3 font-medium text-gray-900">${file.Key}</td>
                                        <td class="px-4 py-3">${sizeFmt}</td>
                                        <td class="px-4 py-3 text-right flex justify-end gap-2">
                                            <a href="${downloadUrl}" class="text-blue-600 hover:underline">Download</a>
                                            <form class="ajax-delete-form" action="/s3/delete-file" method="POST">
                                                <input type="hidden" name="_token" value="${token}">
                                                <input type="hidden" name="bucket_name" value="${bucketName}">
                                                <input type="hidden" name="file_key" value="${file.Key}">
                                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                `;
                                    });
                                    bindAjaxDeleteForms();
                                }

                                document.getElementById('danger-zone-container').classList.remove('hidden');
                                document.getElementById('danger-zone-bucket').value = bucketName;
                                document.getElementById('danger-zone-text').innerText = `Terminate Service (${bucketName})`;
                                const modifyPlanBucket = document.getElementById('modify-plan-bucket');
                                if (modifyPlanBucket) {
                                    modifyPlanBucket.value = bucketName;
                                }
                            }
                        })
                        .catch(() => {
                            btn.innerText = originalText;
                            btn.disabled = false;
                            alert('Network error while fetching files.');
                        });
                });
            }

            // FEATURE 2: DYNAMIC BUCKET QUOTA SELECTOR
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
                    progressBar.className = `${bucket.colorClass} h-2 rounded-full transition-all duration-500`;
                });
            }

            // FEATURE 3: EC2 INSTANCE EXPLORER REFRESH
            const ec2RefreshBtn = document.getElementById('ec2-refresh-btn');
            if (ec2RefreshBtn) {
                ec2RefreshBtn.addEventListener('click', function() {
                    const originalText = this.innerText;
                    this.innerText = 'Refreshing...';
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
                            this.innerText = originalText;
                            this.disabled = false;

                            if (data.success) {
                                const tbody = document.getElementById('ec2-instance-body');
                                tbody.innerHTML = '';

                                if (data.instances.length === 0) {
                                    tbody.innerHTML =
                                        `<tr><td colspan="5" class="px-4 py-4 text-center text-gray-500">No active instances. Launch one to get started.</td></tr>`;
                                } else {
                                    data.instances.forEach(inst => {
                                        const stateBadge = inst.status === 'running' ?
                                            `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">● running</span>` :
                                            `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">● stopped</span>`;

                                        const actionBtn = inst.status === 'running' ?
                                            `<form class="ec2-action-form" action="/ec2/stop" method="POST">
                                            <input type="hidden" name="_token" value="${document.querySelector('input[name="_token"]').value}">
                                            <input type="hidden" name="instance_id" value="${inst.instance_id}">
                                            <button type="submit" class="text-yellow-600 hover:underline text-xs">Stop</button>
                                        </form>` :
                                            `<form class="ec2-action-form" action="/ec2/start" method="POST">
                                            <input type="hidden" name="_token" value="${document.querySelector('input[name="_token"]').value}">
                                            <input type="hidden" name="instance_id" value="${inst.instance_id}">
                                            <button type="submit" class="text-green-600 hover:underline text-xs">Start</button>
                                        </form>`;

                                        tbody.innerHTML += `
                                    <tr class="border-b">
                                        <td class="px-3 py-3 font-medium text-gray-900">${inst.instance_name}</td>
                                        <td class="px-3 py-3 font-mono text-xs">${inst.instance_id}</td>
                                        <td class="px-3 py-3">${inst.instance_type}</td>
                                        <td class="px-3 py-3">${stateBadge}</td>
                                        <td class="px-3 py-3 text-right">
                                            <div class="flex justify-end gap-1">
                                                ${actionBtn}
                                                <form class="ec2-action-form" action="/ec2/terminate" method="POST" onsubmit="return confirm('WARNING: Terminate instance ${inst.instance_id}? Billing will stop and this cannot be undone.');">
                                                    <input type="hidden" name="_token" value="${document.querySelector('input[name="_token"]').value}">
                                                    <input type="hidden" name="instance_id" value="${inst.instance_id}">
                                                    <button type="submit" class="text-red-600 hover:underline text-xs">Terminate</button>
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
                            this.innerText = originalText;
                            this.disabled = false;
                            alert('Network error while refreshing instances.');
                        });
                });
            }

            // FEATURE 4: EC2 AJAX ACTIONS
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
                        const originalText = btn.innerText;
                        btn.innerText = '...';
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
                                    btn.innerText = originalText;
                                    btn.disabled = false;
                                }
                            })
                            .catch(() => {
                                alert('Error processing action.');
                                btn.innerText = originalText;
                                btn.disabled = false;
                            });
                    });
                });
            }

            bindEc2ActionForms();

        });
    </script>
</x-app-layout>
