<x-app-layout>
    <div class="py-10 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Success / Error Alerts -->
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition
                     class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-lg shadow-sm flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-emerald-600 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm font-medium text-emerald-800">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 ml-4 shrink-0 transition focus:outline-none" title="Tutup">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-transition
                     class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 rounded-r-lg shadow-sm flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-rose-600 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm font-medium text-rose-800">{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-rose-500 hover:text-rose-700 ml-4 shrink-0 transition focus:outline-none" title="Tutup">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Sidebar Navigation -->
                <aside class="w-full lg:w-64 shrink-0">
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden p-4 space-y-1">
                        <div class="px-3 py-2 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">
                            Menu Navigasi
                        </div>
                        
                        <!-- Overview -->
                        <a href="{{ route('admin.index') }}" 
                           class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ $tab === 'overview' ? 'bg-gray-800 text-white shadow-md shadow-slate-200' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                            </svg>
                            <span>Ringkasan</span>
                        </a>

                        <!-- Users -->
                        <a href="{{ route('admin.users') }}" 
                           class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ $tab === 'users' ? 'bg-gray-800 text-white shadow-md shadow-slate-200' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span>Kelola Pengguna</span>
                        </a>

                        <!-- Plans -->
                        <a href="{{ route('admin.plans') }}" 
                           class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ $tab === 'plans' ? 'bg-gray-800 text-white shadow-md shadow-slate-200' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <span>Paket Langganan</span>
                        </a>

                        <!-- Provisioned Resources -->
                        <a href="{{ route('admin.resources') }}" 
                           class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ $tab === 'resources' ? 'bg-gray-800 text-white shadow-md shadow-slate-200' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                            </svg>
                            <span>Sumber Daya Aktif</span>
                        </a>

                        <!-- Payments -->
                        <a href="{{ route('admin.payments') }}" 
                           class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ $tab === 'payments' ? 'bg-gray-800 text-white shadow-md shadow-slate-200' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Transaksi & Tagihan</span>
                        </a>

                        <!-- Logs -->
                        <a href="{{ route('admin.logs') }}" 
                           class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ $tab === 'logs' ? 'bg-gray-800 text-white shadow-md shadow-slate-200' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Log Aktivitas</span>
                        </a>
                    </div>
                </aside>

                <!-- Content Area -->
                <main class="flex-1">
                    
                    <!-- RINGKASAN OVERVIEW -->
                    @if($tab === 'overview')
                        <div class="space-y-8">
                            <div class="flex items-center justify-between">
                                <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Overview Dashboard</h2>
                                <span class="text-sm font-medium text-slate-500">{{ now()->isoFormat('D MMMM YYYY') }}</span>
                            </div>

                            <!-- Stat Cards Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                                <!-- Users Stats -->
                                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex items-center space-x-5 hover:shadow-md transition">
                                    <div class="p-4 bg-indigo-50 text-indigo-600 rounded-xl">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Total Pengguna</p>
                                        <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $stats['total_users'] }}</h3>
                                    </div>
                                </div>

                                <!-- Running Instances (Compute) -->
                                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex items-center space-x-5 hover:shadow-md transition">
                                    <div class="p-4 bg-sky-50 text-sky-600 rounded-xl">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">EC2 Compute Aktif</p>
                                        <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $stats['running_instances'] }}</h3>
                                    </div>
                                </div>

                                <!-- Active Buckets (Storage) -->
                                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex items-center space-x-5 hover:shadow-md transition">
                                    <div class="p-4 bg-emerald-50 text-emerald-600 rounded-xl">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">S3 Storage Aktif</p>
                                        <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $stats['active_buckets'] }}</h3>
                                    </div>
                                </div>

                                <!-- Revenue Stats -->
                                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex items-center space-x-5 hover:shadow-md transition">
                                    <div class="p-4 bg-teal-50 text-teal-600 rounded-xl">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Pendapatan Diterima</p>
                                        <h3 class="text-2xl font-extrabold text-emerald-600 mt-1">Rp {{ number_format($stats['total_revenue'], 2, ',', '.') }}</h3>
                                    </div>
                                </div>

                                <!-- Pending Payments -->
                                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex items-center space-x-5 hover:shadow-md transition">
                                    <div class="p-4 bg-amber-50 text-amber-600 rounded-xl">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Tagihan Tertunda (Pending)</p>
                                        <h3 class="text-2xl font-extrabold text-amber-600 mt-1">Rp {{ number_format($stats['pending_payments'], 2, ',', '.') }}</h3>
                                    </div>
                                </div>

                                <!-- Virtual Balance in System -->
                                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex items-center space-x-5 hover:shadow-md transition">
                                    <div class="p-4 bg-fuchsia-50 text-fuchsia-600 rounded-xl">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Total Saldo Pengguna</p>
                                        <h3 class="text-2xl font-extrabold text-fuchsia-600 mt-1">Rp {{ number_format($stats['total_balance'], 2, ',', '.') }}</h3>
                                    </div>
                                </div>
                            </div>

                            <!-- Recent logs list -->
                            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                                <h3 class="text-lg font-bold text-slate-800 mb-6">Log Aktivitas Terbaru</h3>
                                <div class="space-y-4">
                                    @forelse($recentLogs as $log)
                                        <div class="flex items-start justify-between p-3 rounded-xl hover:bg-slate-50 transition">
                                            <div class="flex items-start space-x-4">
                                                <div class="p-2 rounded-lg shrink-0 mt-0.5
                                                    @if($log->action_type === 'create') bg-emerald-50 text-emerald-600
                                                    @elseif($log->action_type === 'delete') bg-rose-50 text-rose-600
                                                    @else bg-blue-50 text-blue-600 @endif">
                                                    <span class="text-xs font-bold uppercase">{{ $log->action_type }}</span>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-800">{{ $log->description }}</p>
                                                    <p class="text-xs text-slate-400 mt-1">
                                                        Oleh: <span class="font-medium text-slate-600">{{ $log->user_name ?? 'System/Deleted User' }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                            <span class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</span>
                                        </div>
                                    @empty
                                        <div class="text-center py-6 text-slate-400 italic text-sm">Tidak ada log aktivitas saat ini.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- KELOLA PENGGUNA -->
                    @if($tab === 'users')
                        <div class="space-y-6">
                            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Kelola Pengguna</h2>

                            <!-- Users Table -->
                            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                                <table class="min-w-full divide-y divide-slate-100 text-left">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama</th>
                                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Email</th>
                                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Role</th>
                                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Saldo Virtual</th>
                                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach($users as $user)
                                            <tr class="hover:bg-slate-50/50 transition">
                                                <td class="px-6 py-4">
                                                    <span class="text-sm font-semibold text-slate-800">{{ $user->name }}</span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="text-sm text-slate-600">{{ $user->email }}</span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="inline-flex px-2.5 py-1 text-xs font-bold rounded-full 
                                                        {{ $user->role === 'admin' ? 'bg-indigo-50 text-indigo-700 border border-indigo-100' : 'bg-slate-50 text-slate-600 border border-slate-100' }}">
                                                        {{ strtoupper($user->role) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-right">
                                                    <span class="text-sm font-bold text-slate-800">Rp {{ number_format($user->virtual_balance, 2, ',', '.') }}</span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center justify-center space-x-3">
                                                        <!-- Adjust Balance Form -->
                                                        <form action="{{ route('admin.users.balance', $user->id) }}" method="POST" class="flex items-center space-x-1">
                                                            @csrf
                                                            <input type="number" name="amount" placeholder="+/- Jumlah" step="0.01"
                                                                   class="w-24 px-2 py-1 text-xs border border-slate-200 rounded-lg focus:ring-1 focus:ring-indigo-500 focus:outline-none" required>
                                                            <button type="submit" class="bg-gray-800 hover:bg-gray-700 text-white text-xs font-bold px-2 py-1.5 rounded-lg transition" title="Adjust Balance">
                                                                Sesuaikan
                                                            </button>
                                                        </form>

                                                        @if(Auth::id() != $user->id)
                                                            <!-- Change Role Form -->
                                                            <form action="{{ route('admin.users.role', $user->id) }}" method="POST" class="inline">
                                                                @csrf
                                                                <input type="hidden" name="role" value="{{ $user->role === 'admin' ? 'user' : 'admin' }}">
                                                                <button type="submit" class="text-xs font-semibold text-slate-500 hover:text-indigo-600 transition px-2 py-1.5 bg-slate-50 hover:bg-indigo-50 rounded-lg">
                                                                    Jadikan {{ $user->role === 'admin' ? 'User' : 'Admin' }}
                                                                </button>
                                                            </form>

                                                            <!-- Delete User Form -->
                                                            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini? Semua data terkait juga akan terhapus.');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-xs font-semibold text-rose-500 hover:text-rose-700 transition px-2 py-1.5 bg-slate-50 hover:bg-rose-50 rounded-lg">
                                                                    Hapus
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- PAKET LANGGANAN -->
                    @if($tab === 'plans')
                        <div class="space-y-8">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Manajemen Paket Langganan</h2>
                                <button onclick="document.getElementById('add-plan-panel').scrollIntoView({ behavior: 'smooth' });"
                                        class="bg-gray-800 hover:bg-gray-700 text-white text-sm font-bold px-4 py-2.5 rounded-xl shadow-sm hover:shadow transition self-start md:self-auto">
                                    + Tambah Paket Baru
                                </button>
                            </div>

                            <!-- List of plans -->
                            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                                <table class="min-w-full divide-y divide-slate-100 text-left">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Paket</th>
                                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Kategori / Layanan</th>
                                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Kuota Detail</th>
                                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Harga Bulanan</th>
                                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Status</th>
                                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach($plans as $plan)
                                            <tr class="hover:bg-slate-50/50 transition">
                                                <td class="px-6 py-4">
                                                    <span class="text-sm font-bold text-slate-800 block">{{ $plan->plan_name }}</span>
                                                    <span class="text-xs text-slate-400 mt-0.5 block line-clamp-1 max-w-xs">{{ $plan->description }}</span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="text-xs font-bold text-slate-700 block">{{ $plan->service_name }}</span>
                                                    <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mt-0.5">{{ $plan->service_category }}</span>
                                                </td>
                                                <td class="px-6 py-4 text-xs text-slate-600 space-y-0.5">
                                                    @if($plan->storage_quota_gb > 0)
                                                        <div>Penyimpanan: <strong>{{ $plan->storage_quota_gb }} GB</strong></div>
                                                    @endif
                                                    @if($plan->compute_quota_vcpu > 0)
                                                        <div>Compute: <strong>{{ $plan->compute_quota_vcpu }} vCPU</strong></div>
                                                    @endif
                                                    @if($plan->network_quota_vpc > 0)
                                                        <div>Jaringan: <strong>{{ $plan->network_quota_vpc }} VPC</strong></div>
                                                    @endif
                                                    @if($plan->storage_quota_gb == 0 && $plan->compute_quota_vcpu == 0 && $plan->network_quota_vpc == 0)
                                                        <span class="text-slate-400 italic">No custom quotas</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-right">
                                                    <span class="text-sm font-bold text-slate-800">Rp {{ number_format($plan->monthly_price, 2, ',', '.') }}</span>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <span class="inline-flex px-2.5 py-1 text-xs font-bold rounded-full 
                                                        {{ $plan->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-slate-50 text-slate-500 border border-slate-200' }}">
                                                        {{ $plan->is_active ? 'AKTIF' : 'NON-AKTIF' }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center justify-center space-x-2">
                                                        <form action="{{ route('admin.plans.toggle', $plan->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" class="text-xs font-semibold px-2 py-1.5 rounded-lg border transition {{ $plan->is_active ? 'border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100' : 'border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                                                                {{ $plan->is_active ? 'Matikan' : 'Aktifkan' }}
                                                            </button>
                                                        </form>

                                                        <form action="{{ route('admin.plans.delete', $plan->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket langganan ini?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-xs font-semibold px-2 py-1.5 rounded-lg border border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100 transition">
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Add New Plan Form -->
                            <div id="add-plan-panel" class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 scroll-mt-6">
                                <h3 class="text-lg font-bold text-slate-800 mb-6">Tambah Paket Langganan Baru</h3>
                                <form action="{{ route('admin.plans.create') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    @csrf

                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Layanan IaaS</label>
                                        <select id="service-select" name="service_id" class="w-full border-slate-200 rounded-xl focus:ring-1 focus:ring-indigo-500 focus:outline-none" required>
                                            <option value="" disabled selected>-- Pilih Layanan --</option>
                                            @foreach($services as $svc)
                                                <option value="{{ $svc->id }}" data-category="{{ $svc->service_category }}">{{ $svc->service_name }} ({{ $svc->service_category }})</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Paket</label>
                                        <input type="text" name="plan_name" placeholder="Contoh: Premium Storage 200GB" class="w-full border-slate-200 rounded-xl focus:ring-1 focus:ring-indigo-500 focus:outline-none" required>
                                    </div>

                                    <div id="storage-quota-container" style="display: none;">
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kapasitas Penyimpanan (GB)</label>
                                        <input type="number" id="storage_quota_input" name="storage_quota_gb" placeholder="0" min="0" class="w-full border-slate-200 rounded-xl focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                                    </div>

                                    <div id="compute-quota-container" style="display: none;">
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Compute Quota (vCPU)</label>
                                        <input type="number" id="compute_quota_input" name="compute_quota_vcpu" placeholder="0" min="0" class="w-full border-slate-200 rounded-xl focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                                    </div>

                                    <input type="hidden" name="network_quota_vpc" value="0">

                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Harga Bulanan (Rp)</label>
                                        <input type="number" name="monthly_price" placeholder="0" min="0" step="0.01" class="w-full border-slate-200 rounded-xl focus:ring-1 focus:ring-indigo-500 focus:outline-none" required>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Deskripsi Paket</label>
                                        <textarea name="description" rows="3" placeholder="Tuliskan deskripsi paket, batas pemakaian, dll..." class="w-full border-slate-200 rounded-xl focus:ring-1 focus:ring-indigo-500 focus:outline-none"></textarea>
                                    </div>

                                    <div class="md:col-span-2">
                                        <button type="submit" class="w-full bg-gray-800 hover:bg-gray-700 text-white font-bold py-3 rounded-xl shadow-sm hover:shadow transition">
                                            Simpan Paket Baru
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- SUMBER DAYA AKTIF -->
                    @if($tab === 'resources')
                        <div class="space-y-6">
                            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Sumber Daya Aktif</h2>

                            <!-- Resources Table -->
                            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                                <table class="min-w-full divide-y divide-slate-100 text-left">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Penyewa (User)</th>
                                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Jenis & Nama Layanan</th>
                                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Resource ID</th>
                                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Biaya per Jam</th>
                                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Status</th>
                                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Mulai Sewa</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach($resources as $res)
                                            <tr class="hover:bg-slate-50/50 transition">
                                                <td class="px-6 py-4">
                                                    <span class="text-sm font-semibold text-slate-800">{{ $res->user_name }}</span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="text-sm font-bold text-slate-800 block">{{ $res->instance_name }}</span>
                                                    <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mt-0.5">{{ $res->resource_type }} ({{ $res->plan_name }})</span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <code class="text-xs bg-slate-100 px-2 py-1 rounded text-slate-600 block w-full truncate max-w-[150px]">{{ $res->ministack_resource_id }}</code>
                                                </td>
                                                <td class="px-6 py-4 text-right">
                                                    <span class="text-sm font-semibold text-slate-700 block">Rp {{ number_format($res->hourly_cost, 2, ',', '.') }}/jam</span>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <span class="inline-flex px-2.5 py-1 text-xs font-bold rounded-full 
                                                        @if($res->status === 'running') bg-emerald-50 text-emerald-700 border border-emerald-100
                                                        @elseif($res->status === 'stopped') bg-amber-50 text-amber-700 border border-amber-100
                                                        @else bg-rose-50 text-rose-700 border border-rose-100 @endif">
                                                        {{ strtoupper($res->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-right">
                                                    <span class="text-xs text-slate-600 block">{{ \Carbon\Carbon::parse($res->rent_start_date)->isoFormat('D MMM YYYY, HH:mm') }}</span>
                                                    <span class="text-[10px] text-slate-400 block mt-0.5">{{ \Carbon\Carbon::parse($res->rent_start_date)->diffForHumans() }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- TRANSAKSI & TAGIHAN -->
                    @if($tab === 'payments')
                        <div class="space-y-6">
                            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Transaksi & Tagihan</h2>

                            <!-- Payments Table -->
                            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                                <table class="min-w-full divide-y divide-slate-100 text-left">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">No. Invoice</th>
                                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Pelanggan (User)</th>
                                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Layanan</th>
                                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Jumlah Tagihan</th>
                                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Status</th>
                                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Aksi Konfirmasi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach($payments as $pay)
                                            <tr class="hover:bg-slate-50/50 transition">
                                                <td class="px-6 py-4">
                                                    <span class="text-sm font-bold text-indigo-600 block">{{ $pay->invoice_number }}</span>
                                                    <span class="text-[10px] text-slate-400 mt-0.5 block">{{ \Carbon\Carbon::parse($pay->billing_date)->isoFormat('D MMM YYYY') }}</span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="text-sm font-semibold text-slate-800 block">{{ $pay->user_name }}</span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="text-xs text-slate-700 block">{{ $pay->instance_name ?? 'Resource/Deleted' }}</span>
                                                    <span class="text-[10px] text-slate-400 mt-0.5 block">Method: {{ $pay->payment_method ?? 'Saldo Virtual' }}</span>
                                                </td>
                                                <td class="px-6 py-4 text-right">
                                                    <span class="text-sm font-extrabold text-slate-800">Rp {{ number_format($pay->billing_amount, 2, ',', '.') }}</span>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <span class="inline-flex px-2.5 py-1 text-xs font-bold rounded-full 
                                                        @if($pay->status === 'paid') bg-emerald-50 text-emerald-700 border border-emerald-100
                                                        @elseif($pay->status === 'pending') bg-amber-50 text-amber-700 border border-amber-100
                                                        @else bg-rose-50 text-rose-700 border border-rose-100 @endif">
                                                        {{ strtoupper($pay->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center justify-center space-x-1.5">
                                                        @if($pay->status !== 'paid')
                                                            <form action="{{ route('admin.payments.status', $pay->id) }}" method="POST" class="inline">
                                                                @csrf
                                                                <input type="hidden" name="status" value="paid">
                                                                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-2 py-1.5 rounded-lg shadow-sm hover:shadow transition">
                                                                    Tandai Lunas
                                                                </button>
                                                            </form>
                                                        @endif

                                                        @if($pay->status !== 'failed')
                                                            <form action="{{ route('admin.payments.status', $pay->id) }}" method="POST" class="inline">
                                                                @csrf
                                                                <input type="hidden" name="status" value="failed">
                                                                <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold px-2 py-1.5 rounded-lg border border-rose-200 transition">
                                                                    Gagal
                                                                </button>
                                                            </form>
                                                        @endif

                                                        @if($pay->status === 'paid' || $pay->status === 'failed')
                                                            <form action="{{ route('admin.payments.status', $pay->id) }}" method="POST" class="inline">
                                                                @csrf
                                                                <input type="hidden" name="status" value="pending">
                                                                <button type="submit" class="bg-slate-50 hover:bg-slate-100 text-slate-600 text-xs font-semibold px-2 py-1.5 rounded-lg border border-slate-200 transition">
                                                                    Set Pending
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- LOG AKTIVITAS -->
                    @if($tab === 'logs')
                        <div class="space-y-6">
                            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Log Aktivitas Sistem</h2>

                            <!-- Logs Table -->
                            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                                <table class="min-w-full divide-y divide-slate-100 text-left">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-32">Tipe Aksi</th>
                                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-40">Pelaku (User)</th>
                                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Deskripsi Aktivitas</th>
                                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right w-48">Waktu Kejadian</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach($logs as $log)
                                            <tr class="hover:bg-slate-50/50 transition">
                                                <td class="px-6 py-4">
                                                    <span class="inline-flex px-2.5 py-1 text-xs font-bold rounded-full
                                                        @if($log->action_type === 'create') bg-emerald-50 text-emerald-700 border border-emerald-100
                                                        @elseif($log->action_type === 'delete') bg-rose-50 text-rose-700 border border-rose-100
                                                        @else bg-blue-50 text-blue-700 border border-blue-100 @endif">
                                                        {{ strtoupper($log->action_type) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="text-sm font-semibold text-slate-800">{{ $log->user_name ?? 'System/Deleted' }}</span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="text-sm text-slate-600">{{ $log->description }}</span>
                                                </td>
                                                <td class="px-6 py-4 text-right">
                                                    <span class="text-xs text-slate-600 block">{{ \Carbon\Carbon::parse($log->created_at)->isoFormat('D MMMM YYYY, HH:mm:ss') }}</span>
                                                    <span class="text-[10px] text-slate-400 block mt-0.5">{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <!-- Pagination Links -->
                                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                                    {{ $logs->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                    
                </main>
            </div>
        </div>
        </div>
    </div>

    <!-- Toggle plan quota fields based on category -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const serviceSelect = document.getElementById('service-select');
            const storageContainer = document.getElementById('storage-quota-container');
            const computeContainer = document.getElementById('compute-quota-container');
            const storageInput = document.getElementById('storage_quota_input');
            const computeInput = document.getElementById('compute_quota_input');

            function toggleQuotaFields() {
                if (!serviceSelect) return;
                const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
                if (!selectedOption || serviceSelect.value === "") {
                    storageContainer.style.display = 'none';
                    computeContainer.style.display = 'none';
                    return;
                }

                const category = selectedOption.getAttribute('data-category');
                if (category === 'Storage') {
                    storageContainer.style.display = 'block';
                    computeContainer.style.display = 'none';
                    computeInput.value = '0';
                } else if (category === 'Compute') {
                    storageContainer.style.display = 'none';
                    computeContainer.style.display = 'block';
                    storageInput.value = '0';
                } else {
                    storageContainer.style.display = 'none';
                    computeContainer.style.display = 'none';
                }
            }

            if (serviceSelect) {
                serviceSelect.addEventListener('change', toggleQuotaFields);
                toggleQuotaFields(); // Initial run
            }
        });
    </script>
</x-app-layout>
