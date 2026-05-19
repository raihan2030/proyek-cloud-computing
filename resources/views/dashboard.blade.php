<x-app-layout>
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Overview Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Total Resources Card -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-gray-600">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Total Resources</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">24</p>
                            <p class="text-gray-600 text-xs mt-2">+2 added this month</p>
                        </div>
                        <div class="bg-gray-100 rounded-full p-3">
                            <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Active Services Card -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-gray-600">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Active Services</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">21</p>
                            <p class="text-gray-600 text-xs mt-2">3 maintenance required</p>
                        </div>
                        <div class="bg-gray-100 rounded-full p-3">
                            <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v2h16V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5H4v8a2 2 0 002 2h12a2 2 0 002-2V7h-2v1a1 1 0 11-2 0V7H9v1a1 1 0 11-2 0V7H6v1a1 1 0 11-2 0V7z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Monthly Bill Card -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-gray-600">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Monthly Bill</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">$4,285</p>
                            <p class="text-gray-600 text-xs mt-2">$285 from previous month</p>
                        </div>
                        <div class="bg-gray-100 rounded-full p-3">
                            <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8.16 5.314l4.897-1.596c.191-.062.392-.062.583 0l4.898 1.596c.59.192.984.768.984 1.404v7.284a1.563 1.563 0 01-.92 1.438l-4.898 1.595a1.563 1.563 0 01-.583 0l-4.898-1.595a1.563 1.563 0 01-.92-1.438V6.718c0-.636.394-1.212.984-1.404zM10 9a1 1 0 100 2 1 1 0 000-2z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- S3 Storage Card -->
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">S3 Storage</h3>
                        <div class="bg-gray-100 rounded-full p-2">
                            <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Storage Progress -->
                    <div class="mb-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-600">Capacity Used</span>
                            <span class="text-sm font-bold text-gray-800">7.2 TB / 10 TB (72%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gray-600 h-2 rounded-full" style="width: 72%"></div>
                        </div>
                    </div>

                    <!-- Metrics -->
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Available Storage</span>
                            <span class="font-semibold text-gray-800">2.8 TB</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Number of Buckets</span>
                            <span class="font-semibold text-gray-800">14</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Data Requests/Day</span>
                            <span class="font-semibold text-gray-800">284,520</span>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-2 pt-4 border-t border-gray-200">
                        <button class="flex-1 px-3 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700 transition">
                            Manage
                        </button>
                        <button class="flex-1 px-3 py-2 bg-gray-100 text-gray-800 text-sm rounded-lg hover:bg-gray-200 transition">
                            View Details
                        </button>
                    </div>
                </div>

                <!-- EC2 Compute Card -->
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">EC2 Compute</h3>
                        <div class="bg-gray-100 rounded-full p-2">
                            <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 7H7v6h6V7z" />
                                <path fill-rule="evenodd" d="M7 2a1 1 0 012 0v1h2V2a1 1 0 112 0v1h2V2a1 1 0 112 0v1h1a2 2 0 012 2v1h1a1 1 0 110 2h-1v2h1a1 1 0 110 2h-1v2h1a1 1 0 110 2h-1v1a2 2 0 01-2 2h-1v1a1 1 0 11-2 0v-1h-2v1a1 1 0 11-2 0v-1H7v1a1 1 0 11-2 0v-1H4a2 2 0 01-2-2v-1H1a1 1 0 110-2h1V9H1a1 1 0 010-2h1V7H1a1 1 0 110-2h1V4a2 2 0 012-2h1V2a1 1 0 010-2z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Metrics -->
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

                    <!-- Buttons -->
                    <div class="flex gap-2 pt-4 border-t border-gray-200">
                        <button class="flex-1 px-3 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700 transition">
                            Manage
                        </button>
                        <button class="flex-1 px-3 py-2 bg-gray-100 text-gray-800 text-sm rounded-lg hover:bg-gray-200 transition">
                            View Details
                        </button>
                    </div>
                </div>

                <!-- VPS Network Card -->
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">VPS Network</h3>
                        <div class="bg-gray-100 rounded-full p-2">
                            <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Metrics -->
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

                    <!-- Buttons -->
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

            <!-- Health Status Section -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Health Status</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- S3 Health -->
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

                    <!-- EC2 Health -->
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                                <svg class="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">EC2 Compute</p>
                            <p class="text-xs text-green-600">Fully Operational</p>
                            <p class="text-xs text-gray-500 mt-1">CPU usage normal</p>
                        </div>
                    </div>

                    <!-- Network Health -->
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
                            <p class="text-xs text-yellow-600">Maintenance Scheduled</p>
                            <p class="text-xs text-gray-500 mt-1">Minor updates in queue</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Section -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Activity</h3>
                <div class="space-y-4">
                    <!-- Activity Item 1 -->
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

                    <!-- Activity Item 2 -->
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

                    <!-- Activity Item 3 -->
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

                    <!-- Activity Item 4 -->
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

                    <!-- Activity Item 5 -->
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
</x-app-layout>