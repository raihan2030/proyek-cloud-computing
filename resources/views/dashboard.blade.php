<x-app-layout>
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-gray-600">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Total Resources (Template) </p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">0</p>
                            <p class="text-gray-600 text-xs mt-2">+2 added this month</p>
                        </div>
                        <div class="bg-gray-100 rounded-full p-3">
                            <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-gray-600">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Active Services (Template)</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">0</p>
                            <p class="text-gray-600 text-xs mt-2">0 maintenance required</p>
                        </div>
                        <div class="bg-gray-100 rounded-full p-3">
                            <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v2h16V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5H4v8a2 2 0 002 2h12a2 2 0 002-2V7h-2v1a1 1 0 11-2 0V7H9v1a1 1 0 11-2 0V7H6v1a1 1 0 11-2 0V7z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-gray-600">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Monthly Bill (Template)</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">$0.00</p>
                            <p class="text-gray-600 text-xs mt-2">$0.00 from previous month</p>
                        </div>
                        <div class="bg-gray-100 rounded-full p-3">
                            <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8.16 5.314l4.897-1.596c.191-.062.392-.062.583 0l4.898 1.596c.59.192.984.768.984 1.404v7.284a1.563 1.563 0 01-.92 1.438l-4.898 1.595a1.563 1.563 0 01-.583 0l-4.898-1.595a1.563 1.563 0 01-.92-1.438V6.718c0-.636.394-1.212.984-1.404zM10 9a1 1 0 100 2 1 1 0 000-2z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 mb-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">S3 Storage Management (Test Workspace)</h3>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">{{ session('error') }}</div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 hover:shadow-md transition">
                        <h4 class="font-medium text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Create Isolated Bucket
                        </h4>
                        <form action="{{ route('s3.createBucket') }}" method="POST">
                            @csrf
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bucket Name</label>
                            <p class="text-xs text-gray-500 mb-2">Must be lowercase, no spaces.</p>
                            <input type="text" name="bucket_name" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mb-4" placeholder="e.g., iaas-firas-123" required>
                            <button type="submit" class="w-full bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition shadow-sm">Provision in MiniStack</button>
                        </form>
                    </div>

                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 hover:shadow-md transition">
                        <h4 class="font-medium text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Upload File to Bucket
                        </h4>
                        <form id="upload-form" action="{{ route('s3.uploadObject') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <label class="block text-sm font-medium text-gray-700 mb-1">Target Bucket</label>
                            <input type="text" name="bucket_name" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mb-3" placeholder="e.g., iaas-firas-123" required>
                            
                            <label class="block text-sm font-medium text-gray-700 mb-1">Select File</label>
                            <input type="file" name="file" class="w-full border-gray-300 rounded-md shadow-sm bg-white mb-4 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>

                            <div id="progress-container" class="hidden mb-4">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-xs font-medium text-blue-700">Uploading...</span>
                                    <span id="progress-text" class="text-xs font-bold text-blue-700">0%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                    <div id="progress-bar" class="bg-blue-600 h-1.5 rounded-full transition-all duration-75" style="width: 0%"></div>
                                </div>
                            </div>
                            
                            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition shadow-sm">Upload to MiniStack</button>
                        </form>
                    </div>

                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 md:col-span-2">
                        <h4 class="font-medium text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Storage Explorer & Termination
                        </h4>
                        
                        <form action="{{ route('s3.viewFiles') }}" method="POST" class="flex gap-2 mb-4">
                            @csrf
                            <input type="text" name="bucket_name" class="flex-1 border-gray-300 rounded-md shadow-sm" placeholder="Enter bucket name to view files..." required value="{{ session('current_bucket') ?? '' }}">
                            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700">Open Explorer</button>
                        </form>

                        @if(session('files'))
                            <div class="bg-white border rounded-md overflow-hidden mb-4">
                                <table class="w-full text-sm text-left text-gray-500">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-3">File Name</th>
                                            <th class="px-4 py-3">Size (Bytes)</th>
                                            <th class="px-4 py-3 text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse(session('files') as $file)
                                            <tr class="border-b">
                                                <td class="px-4 py-3 font-medium text-gray-900">{{ $file['Key'] }}</td>
                                                <td class="px-4 py-3">{{ number_format($file['Size']) }}</td>
                                                <td class="px-4 py-3 text-right flex justify-end gap-2">
                                                    <a href="{{ route('s3.downloadFile', ['bucket' => session('current_bucket'), 'key' => $file['Key']]) }}" class="text-blue-600 hover:underline">Download</a>
                                                    <form class="ajax-delete-form" action="{{ route('s3.deleteFile') }}" method="POST">                                                        @csrf
                                                        <input type="hidden" name="bucket_name" value="{{ session('current_bucket') }}">
                                                        <input type="hidden" name="file_key" value="{{ $file['Key'] }}">
                                                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="3" class="px-4 py-4 text-center text-gray-500">Bucket is empty.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        @if(session('current_bucket'))
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <form action="{{ route('s3.deleteBucket') }}" method="POST" onsubmit="return confirm('WARNING: Are you sure you want to terminate this bucket? This will stop billing and cannot be undone.');">
                                @csrf
                                <input type="hidden" name="bucket_name" value="{{ session('current_bucket') }}">
                                <button type="submit" class="text-sm text-red-600 font-medium hover:text-red-800 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Terminate Service ({{ session('current_bucket') }})
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>

                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition border-t-4 border-blue-500">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">S3 Storage (Active)</h3>
                        <div class="bg-gray-100 rounded-full p-2">
                            <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" />
                            </svg>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-600">Capacity Used</span>
                            <span class="text-sm font-bold text-gray-800">{{ number_format($usedGB ?? 0, 4) }} GB / {{ $totalGB ?? 5 }} GB ({{ number_format($percentage ?? 0, 0) }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="{{ ($percentage ?? 0) > 90 ? 'bg-red-600' : (($percentage ?? 0) > 75 ? 'bg-yellow-500' : 'bg-blue-600') }} h-2 rounded-full transition-all duration-500" style="width: {{ min($percentage ?? 0, 100) }}%"></div>
                        </div>
                    </div>

                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Available Storage</span>
                            <span class="font-semibold text-gray-800">{{ number_format(max(($totalGB ?? 5) - ($usedGB ?? 0), 0), 2) }} GB</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Active Buckets</span>
                            <span class="font-semibold text-gray-800">{{ session('current_bucket') ? '1' : '0' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Data Requests/Day</span>
                            <span class="font-semibold text-gray-800">12 (Mocked)</span>
                        </div>
                    </div>

                    <div class="flex gap-2 pt-4 border-t border-gray-200">
                        <button class="flex-1 px-3 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700 transition">
                            Manage
                        </button>
                        <button class="flex-1 px-3 py-2 bg-gray-100 text-gray-800 text-sm rounded-lg hover:bg-gray-200 transition">
                            View Details
                        </button>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">EC2 Compute (Template)</h3>
                        <div class="bg-gray-100 rounded-full p-2">
                            <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 7H7v6h6V7z" />
                                <path fill-rule="evenodd" d="M7 2a1 1 0 012 0v1h2V2a1 1 0 112 0v1h2V2a1 1 0 112 0v1h1a2 2 0 012 2v1h1a1 1 0 110 2h-1v2h1a1 1 0 110 2h-1v2h1a1 1 0 110 2h-1v1a2 2 0 01-2 2h-1v1a1 1 0 11-2 0v-1h-2v1a1 1 0 11-2 0v-1H7v1a1 1 0 11-2 0v-1H4a2 2 0 01-2-2v-1H1a1 1 0 110-2h1V9H1a1 1 0 010-2h1V7H1a1 1 0 110-2h1V4a2 2 0 012-2h1V2a1 1 0 010-2z" />
                            </svg>
                        </div>
                    </div>

                    <div class="space-y-3 mb-4">
                        <div class="bg-gray-50 rounded p-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-600">CPU Cores</span>
                                <span class="text-xl font-bold text-gray-800">16 vCPU</span>
                            </div>
                            <div class="mt-2">
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-gray-600">Usage</span>
                                    <span class="text-gray-800">68%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                    <div class="bg-gray-600 h-1.5 rounded-full" style="width: 68%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded p-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-600">Memory</span>
                                <span class="text-xl font-bold text-gray-800">64 GB</span>
                            </div>
                            <div class="mt-2">
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-gray-600">Usage</span>
                                    <span class="text-gray-800">52%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                    <div class="bg-gray-600 h-1.5 rounded-full" style="width: 52%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Active Instances</span>
                            <span class="font-semibold text-gray-800">8</span>
                        </div>
                    </div>

                    <div class="flex gap-2 pt-4 border-t border-gray-200">
                        <button class="flex-1 px-3 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700 transition">
                            Manage
                        </button>
                        <button class="flex-1 px-3 py-2 bg-gray-100 text-gray-800 text-sm rounded-lg hover:bg-gray-200 transition">
                            View Details
                        </button>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">VPS Network (Template)</h3>
                        <div class="bg-gray-100 rounded-full p-2">
                            <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                            </svg>
                        </div>
                    </div>

                    <div class="space-y-3 mb-4">
                        <div class="bg-gray-50 rounded p-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-600">Bandwidth</span>
                                <span class="text-xl font-bold text-gray-800">1 Gbps</span>
                            </div>
                            <div class="mt-2">
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-gray-600">Usage</span>
                                    <span class="text-gray-800">45%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                    <div class="bg-gray-600 h-1.5 rounded-full" style="width: 45%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between text-sm bg-gray-50 rounded p-3">
                            <span class="text-gray-600">Active Connections</span>
                            <span class="font-semibold text-gray-800">342</span>
                        </div>

                        <div class="flex justify-between text-sm bg-gray-50 rounded p-3">
                            <span class="text-gray-600">Network Uptime</span>
                            <span class="font-semibold text-gray-800">99.98%</span>
                        </div>
                    </div>

                    <div class="flex gap-2 pt-4 border-t border-gray-200">
                        <button class="flex-1 px-3 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700 transition">
                            Manage
                        </button>
                        <button class="flex-1 px-3 py-2 bg-gray-100 text-gray-800 text-sm rounded-lg hover:bg-gray-200 transition">
                            View Details
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Health Status</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                                <svg class="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">S3 Storage</p>
                            <p class="text-xs text-green-600">Fully Operational</p>
                            <p class="text-xs text-gray-500 mt-1">All systems nominal</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                                <svg class="h-6 w-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">EC2 Compute</p>
                            <p class="text-xs text-yellow-600">In Progress</p>
                            <p class="text-xs text-gray-500 mt-1">Backend services</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                                <svg class="h-6 w-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">VPS Network</p>
                            <p class="text-xs text-yellow-600">In Progress</p>
                            <p class="text-xs text-gray-500 mt-1">Backend services</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Activity (Template)</h3>
                <div class="space-y-4">
                    <div class="flex items-start gap-4 pb-4 border-b border-gray-200 last:border-0">
                        <div class="flex-shrink-0 mt-1">
                            <div class="flex items-center justify-center h-8 w-8 rounded-full bg-gray-100">
                                <svg class="h-4 w-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8 5a3 3 0 106 0 3 3 0 00-6 0zM15 4a1 1 0 11-2 0 1 1 0 012 0zM2.5 7a2.5 2.5 0 100 5 2.5 2.5 0 000-5zM17 8a1 1 0 100-2 1 1 0 000 2z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">S3 Storage Bucket Created</p>
                            <p class="text-xs text-gray-600 mt-1">New bucket "prod-app-backups" created with versioning enabled</p>
                            <p class="text-xs text-gray-500 mt-1">2 hours ago</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 pb-4 border-b border-gray-200 last:border-0">
                        <div class="flex-shrink-0 mt-1">
                            <div class="flex items-center justify-center h-8 w-8 rounded-full bg-gray-100">
                                <svg class="h-4 w-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 7H7v6h6V7z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">EC2 Instance Launched</p>
                            <p class="text-xs text-gray-600 mt-1">New t3.large instance "web-server-prod-02" started in us-east-1</p>
                            <p class="text-xs text-gray-500 mt-1">5 hours ago</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 pb-4 border-b border-gray-200 last:border-0">
                        <div class="flex-shrink-0 mt-1">
                            <div class="flex items-center justify-center h-8 w-8 rounded-full bg-gray-100">
                                <svg class="h-4 w-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">Network Security Group Updated</p>
                            <p class="text-xs text-gray-600 mt-1">Inbound rule added for HTTPS traffic from 0.0.0.0/0</p>
                            <p class="text-xs text-gray-500 mt-1">8 hours ago</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 pb-4 border-b border-gray-200 last:border-0">
                        <div class="flex-shrink-0 mt-1">
                            <div class="flex items-center justify-center h-8 w-8 rounded-full bg-gray-100">
                                <svg class="h-4 w-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8 5a3 3 0 106 0 3 3 0 00-6 0zM15 4a1 1 0 11-2 0 1 1 0 012 0zM2.5 7a2.5 2.5 0 100 5 2.5 2.5 0 000-5zM17 8a1 1 0 100-2 1 1 0 000 2z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">Billing Threshold Alert</p>
                            <p class="text-xs text-gray-600 mt-1">Monthly charges have reached 85% of your budget limit</p>
                            <p class="text-xs text-gray-500 mt-1">12 hours ago</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 mt-1">
                            <div class="flex items-center justify-center h-8 w-8 rounded-full bg-gray-100">
                                <svg class="h-4 w-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 7H7v6h6V7z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">Backup Completed Successfully</p>
                            <p class="text-xs text-gray-600 mt-1">Daily backup of database completed. 2.1 GB backed up in 4 minutes</p>
                            <p class="text-xs text-gray-500 mt-1">1 day ago</p>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // FEATURE 1: AJAX UPLOAD WITH PROGRESS BAR
        const uploadForm = document.getElementById('upload-form');
        if (uploadForm) {
            uploadForm.addEventListener('submit', function(e) {
                e.preventDefault(); // Stop page reload
                
                const formData = new FormData(uploadForm);
                const progressContainer = document.getElementById('progress-container');
                const progressBar = document.getElementById('progress-bar');
                const progressText = document.getElementById('progress-text');
                const uploadBtn = document.getElementById('upload-btn');

                // Show progress bar, disable button
                progressContainer.classList.remove('hidden');
                uploadBtn.disabled = true;
                uploadBtn.innerText = 'Uploading...';
                uploadBtn.classList.add('opacity-50');

                // We use XMLHttpRequest instead of Fetch because Fetch doesn't support upload progress natively yet
                const xhr = new XMLHttpRequest();
                xhr.open('POST', uploadForm.action, true);
                xhr.setRequestHeader('Accept', 'application/json');

                // Listen to the upload progress
                xhr.upload.onprogress = function(event) {
                    if (event.lengthComputable) {
                        let percentComplete = Math.round((event.loaded / event.total) * 100);
                        progressBar.style.width = percentComplete + '%';
                        progressText.innerText = percentComplete + '%';
                    }
                };

                // When upload finishes
                xhr.onload = function() {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        // Success! Refresh the page to show the new file and updated capacity
                        window.location.reload(); 
                    } else {
                        alert('Upload failed or quota exceeded.');
                        progressContainer.classList.add('hidden');
                        uploadBtn.disabled = false;
                        uploadBtn.innerText = 'Upload to MiniStack';
                        uploadBtn.classList.remove('opacity-50');
                    }
                };

                xhr.send(formData);
            });
        }

        // FEATURE 2: SEAMLESS AJAX DELETE
        const deleteForms = document.querySelectorAll('.ajax-delete-form');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Stop page reload
                
                if (!confirm('Delete this file?')) return;

                const formData = new FormData(form);
                const tableRow = form.closest('tr'); // Find the specific row to hide

                // Dim the row to show it's working
                tableRow.style.opacity = '0.5';

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        // Make the row disappear smoothly
                        tableRow.style.display = 'none';
                    } else {
                        alert('Failed to delete file.');
                        tableRow.style.opacity = '1';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    tableRow.style.opacity = '1';
                });
            });
        });

    });
    </script>

</x-app-layout>