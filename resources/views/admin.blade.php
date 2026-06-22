<x-admin-layout>
    @php
        $tab = request()->query('tab', $tab ?? 'overview');
    @endphp

    <x-slot name="header">
        <div>
            <h2 class="font-bold text-2xl text-primary tracking-tight">
                @if ($tab === 'overview')
                    Overview Admin
                @elseif ($tab === 'users')
                    Kelola Pengguna
                @elseif ($tab === 'plans')
                    Manajemen Paket Langganan
                @elseif ($tab === 'resources')
                    Pemantauan Sumber Daya Aktif
                @elseif ($tab === 'payments')
                    Transaksi & Tagihan
                @elseif ($tab === 'logs')
                    Log Aktivitas Sistem
                @endif
            </h2>
            <p class="text-sm text-on-surface-variant mt-1">
                @if ($tab === 'overview')
                    Ringkasan statistik sistem, pendapatan, dan alokasi resource.
                @elseif ($tab === 'users')
                    Manajemen akun pengguna, saldo virtual, dan peran akses.
                @elseif ($tab === 'plans')
                    Konfigurasi kuota dan harga paket IaaS yang ditawarkan ke pengguna.
                @elseif ($tab === 'resources')
                    Pemantauan seluruh instance EC2 dan S3 yang sedang disewa pengguna.
                @elseif ($tab === 'payments')
                    Verifikasi pembayaran, status tagihan, dan riwayat transaksi.
                @elseif ($tab === 'logs')
                    Audit trail lengkap dari semua operasi yang terjadi di sistem.
                @endif
            </p>
        </div>

        @if ($tab === 'plans')
            <div class="flex items-center gap-sm shrink-0 mb-1">
                <button onclick="document.getElementById('add-plan-panel').scrollIntoView({ behavior: 'smooth' });"
                    class="bg-primary hover:bg-primary/90 text-on-primary-fixed px-4 py-2 text-xs font-semibold uppercase tracking-wider rounded flex items-center gap-2 transition">
                    <span class="material-symbols-outlined text-sm">add</span> Tambah Paket Baru
                </button>
            </div>
        @endif
    </x-slot>

    {{-- Alert Messages --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition
            class="p-4 mb-4 bg-primary/20 text-primary border border-primary/30 rounded flex items-center justify-between font-semibold">
            <div class="flex items-center gap-md">
                <span class="material-symbols-outlined">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
            <button @click="show = false" class="text-primary hover:opacity-85 transition focus:outline-none" title="Tutup">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif
    @if (session('error'))
        <div x-data="{ show: true }" x-show="show" x-transition
            class="p-4 mb-4 bg-error/15 text-error border border-error/30 rounded flex items-center justify-between font-semibold">
            <div class="flex items-center gap-md">
                <span class="material-symbols-outlined">error</span>
                <span>{{ session('error') }}</span>
            </div>
            <button @click="show = false" class="text-error hover:opacity-85 transition focus:outline-none" title="Tutup">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif
    @if ($errors->any())
        <div x-data="{ show: true }" x-show="show" x-transition
            class="p-4 mb-4 bg-error/15 text-error border border-error/30 rounded flex flex-col gap-2 font-semibold">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-md">
                    <span class="material-symbols-outlined">error</span>
                    <span>Terdapat kesalahan pada input Anda:</span>
                </div>
                <button @click="show = false" class="text-error hover:opacity-85 transition focus:outline-none" title="Tutup">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <ul class="list-disc list-inside text-xs font-normal pl-8">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- OVERVIEW TAB --}}
    @if ($tab === 'overview')
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
            <div class="bg-surface border border-outline-variant rounded p-6">
                <p class="font-mono text-xs text-on-surface-variant uppercase tracking-widest mb-2">Total Pengguna</p>
                <h3 class="text-3xl font-bold text-primary">{{ $stats['total_users'] }}</h3>
            </div>
            <div class="bg-surface border border-outline-variant rounded p-6">
                <p class="font-mono text-xs text-on-surface-variant uppercase tracking-widest mb-2">EC2 Compute Aktif
                </p>
                <h3 class="text-3xl font-bold text-primary">{{ $stats['running_instances'] }}</h3>
            </div>
            <div class="bg-surface border border-outline-variant rounded p-6">
                <p class="font-mono text-xs text-on-surface-variant uppercase tracking-widest mb-2">S3 Storage Aktif</p>
                <h3 class="text-3xl font-bold text-primary">{{ $stats['active_buckets'] }}</h3>
            </div>
            <div class="bg-surface border border-outline-variant rounded p-6">
                <p class="font-mono text-xs text-on-surface-variant uppercase tracking-widest mb-2">Pendapatan Diterima
                </p>
                <h3 class="text-2xl font-bold text-primary">Rp {{ number_format($stats['total_revenue'], 2, ',', '.') }}
                </h3>
            </div>
            <div class="bg-surface border border-outline-variant rounded p-6">
                <p class="font-mono text-xs text-on-surface-variant uppercase tracking-widest mb-2">Tagihan Tertunda</p>
                <h3 class="text-2xl font-bold text-error">Rp
                    {{ number_format($stats['pending_payments'], 2, ',', '.') }}</h3>
            </div>
            <div class="bg-surface border border-outline-variant rounded p-6">
                <p class="font-mono text-xs text-on-surface-variant uppercase tracking-widest mb-2">Total Saldo Pengguna
                </p>
                <h3 class="text-2xl font-bold text-primary">Rp {{ number_format($stats['total_balance'], 2, ',', '.') }}
                </h3>
            </div>
        </div>

        {{-- WIDGET LOG AKTIVITAS TERBARU (Di dalam Tab Overview) --}}
        <div class="bg-surface border border-outline-variant rounded overflow-hidden mt-6">
            <div
                class="px-4 py-3 border-b border-outline-variant bg-surface-container-lowest flex justify-between items-center">
                <h3 class="font-semibold text-primary text-sm uppercase tracking-wide">Log Aktivitas Terbaru</h3>
                <a href="{{ route('admin.logs') }}"
                    class="text-[10px] uppercase font-bold text-primary hover:underline flex items-center gap-1">
                    Lihat Semua <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                    {{-- Menyandikan bentuk header yang sama dengan Tab Log utama --}}
                    <thead class="bg-surface-container-lowest border-b border-outline-variant">
                        <tr class="font-mono text-on-surface-variant uppercase">
                            <th class="px-4 py-3">Log ID</th>
                            <th class="px-4 py-3">Pengguna</th>
                            <th class="px-4 py-3">Aksi</th>
                            <th class="px-4 py-3">Keterangan</th>
                            <th class="px-4 py-3 text-right">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse($recentLogs as $log)
                            {{-- Batasi hanya 5 log teratas yang tampil di Overview --}}
                            @if ($loop->iteration > 5)
                                @break
                            @endif

                            <tr class="hover:bg-surface-container-highest transition-colors">
                                {{-- Kolom ID --}}
                                <td class="px-4 py-3 font-mono text-[10px] text-on-surface-variant">
                                    #{{ $log->id }}
                                </td>
                                {{-- Kolom Pengguna --}}
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="h-6 w-6 rounded-full bg-surface-container-highest flex items-center justify-center text-primary font-bold text-[10px]">
                                            {{ strtoupper(substr($log->user_name ?? 'S', 0, 1)) }}
                                        </div>
                                        <span class="font-semibold text-primary block">
                                            {{ $log->user_name ?? 'Sistem' }}
                                        </span>
                                    </div>
                                </td>
                                {{-- Kolom Jenis Aksi --}}
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex px-2 py-0.5 text-[10px] font-bold rounded bg-surface-container-high text-on-surface">
                                        {{ strtoupper($log->action_type ?? 'UMUM') }}
                                    </span>
                                </td>
                                {{-- Kolom Keterangan --}}
                                <td class="px-4 py-3 text-on-surface-variant whitespace-normal min-w-[250px]">
                                    {{ $log->description ?? '-' }}
                                </td>
                                {{-- Kolom Waktu (Menggunakan diffForHumans agar lebih interaktif untuk Overview) --}}
                                <td class="px-4 py-3 text-right font-mono text-[10px] text-on-surface-variant">
                                    {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"
                                    class="px-4 py-8 text-center text-on-surface-variant text-sm font-mono">
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="material-symbols-outlined text-4xl mb-2 opacity-50">history</span>
                                        Belum ada log aktivitas terbaru.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- KELOLA PENGGUNA TAB --}}
    @if ($tab === 'users')
        <div class="bg-surface border border-outline-variant rounded overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                    <thead class="bg-surface-container-lowest border-b border-outline-variant">
                        <tr class="font-mono text-on-surface-variant uppercase">
                            <th class="px-4 py-3">Nama & Email</th>
                            <th class="px-4 py-3">Role</th>
                            <th class="px-4 py-3 text-right">Saldo Virtual</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @foreach ($users as $user)
                            <tr class="hover:bg-surface-container-highest transition-colors">
                                <td class="px-4 py-3">
                                    <span class="font-semibold text-primary block">{{ $user->name }}</span>
                                    <span
                                        class="font-mono text-[10px] text-on-surface-variant">{{ $user->email }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex px-2.5 py-0.5 text-[9px] font-bold uppercase rounded-full border {{ $user->role === 'admin' ? 'bg-primary/20 text-primary border-primary/30' : 'bg-surface-container text-on-surface-variant border-outline-variant' }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <span class="text-sm font-bold text-primary font-mono">Rp
                                        {{ number_format($user->virtual_balance, 2, ',', '.') }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center space-x-2">
                                        <form action="{{ route('admin.users.balance', $user->id) }}" method="POST"
                                            class="flex items-center space-x-1">
                                            @csrf
                                            <input type="number" name="amount" placeholder="+/- Saldo" step="0.01"
                                                class="w-24 text-xs bg-surface-container-lowest border border-outline-variant rounded p-1 text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none"
                                                required>
                                            <button type="submit"
                                                class="bg-primary hover:bg-primary/90 text-on-primary-fixed px-2 py-1 text-xs rounded font-semibold transition"
                                                title="Sesuaikan Saldo">
                                                <span
                                                    class="material-symbols-outlined text-sm leading-none">account_balance_wallet</span>
                                            </button>
                                        </form>

                                        @if (Auth::id() != $user->id)
                                            <form action="{{ route('admin.users.role', $user->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                <input type="hidden" name="role"
                                                    value="{{ $user->role === 'admin' ? 'user' : 'admin' }}">
                                                <button type="submit"
                                                    class="p-1 text-on-surface-variant hover:text-primary hover:bg-surface-container rounded transition-colors border border-transparent hover:border-outline-variant"
                                                    title="Ubah Role">
                                                    <span
                                                        class="material-symbols-outlined text-sm leading-none">swap_horiz</span>
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.users.delete', $user->id) }}"
                                                method="POST" class="inline"
                                                onsubmit="return confirm('Yakin hapus pengguna ini? Semua data terkait terhapus.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-1 text-error/70 hover:text-error hover:bg-error/10 rounded transition-colors border border-transparent hover:border-error/30"
                                                    title="Hapus">
                                                    <span
                                                        class="material-symbols-outlined text-sm leading-none">delete</span>
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

    {{-- PAKET LANGGANAN TAB --}}
    @if ($tab === 'plans')
        <div class="space-y-8">
            <div class="bg-surface border border-outline-variant rounded overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                        <thead class="bg-surface-container-lowest border-b border-outline-variant">
                            <tr class="font-mono text-on-surface-variant uppercase">
                                <th class="px-4 py-3">Nama Paket</th>
                                <th class="px-4 py-3">Layanan</th>
                                <th class="px-4 py-3">Kuota</th>
                                <th class="px-4 py-3 text-right">Harga / Bln</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant">
                            @foreach ($plans as $plan)
                                <tr class="hover:bg-surface-container-highest transition-colors">
                                    <td class="px-4 py-3">
                                        <span class="font-semibold text-primary block">{{ $plan->plan_name }}</span>
                                        <span
                                            class="font-mono text-[10px] text-on-surface-variant block mt-0.5 truncate max-w-[200px]">{{ $plan->description }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="font-semibold text-primary block">{{ $plan->service_name }}</span>
                                        <span
                                            class="font-mono text-[9px] font-bold uppercase text-on-surface-variant block mt-0.5">{{ $plan->service_category }}</span>
                                    </td>
                                    <td class="px-4 py-3 font-mono text-[10px] text-on-surface-variant">
                                        @if ($plan->storage_quota_gb > 0)
                                            <div class="flex gap-1 items-center"><span
                                                    class="material-symbols-outlined text-[12px]">storage</span>
                                                {{ $plan->storage_quota_gb }} GB</div>
                                        @endif
                                        @if ($plan->compute_quota_vcpu > 0)
                                            <div class="flex gap-1 items-center"><span
                                                    class="material-symbols-outlined text-[12px]">memory</span>
                                                {{ $plan->compute_quota_vcpu }} vCPU</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right font-mono font-bold text-primary text-sm">
                                        Rp {{ number_format($plan->monthly_price, 2, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span
                                            class="inline-flex px-2.5 py-0.5 text-[9px] font-bold uppercase rounded-full border {{ $plan->is_active ? 'bg-primary/20 text-primary border-primary/30' : 'bg-surface-container text-on-surface-variant border-outline-variant' }}">
                                            {{ $plan->is_active ? 'AKTIF' : 'NON-AKTIF' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center space-x-2">
                                            <form action="{{ route('admin.plans.toggle', $plan->id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="px-2 py-1 text-xs rounded font-semibold border transition-colors {{ $plan->is_active ? 'border-outline-variant text-on-surface-variant hover:bg-surface-container' : 'bg-primary text-on-primary-fixed border-primary' }}">
                                                    {{ $plan->is_active ? 'Matikan' : 'Aktifkan' }}
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.plans.delete', $plan->id) }}"
                                                method="POST" class="inline"
                                                onsubmit="return confirm('Yakin hapus paket ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-1 text-error/70 hover:text-error hover:bg-error/10 rounded transition-colors">
                                                    <span
                                                        class="material-symbols-outlined text-sm leading-none">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="add-plan-panel" class="bg-surface border border-outline-variant rounded p-6 scroll-mt-24">
                <div class="border-b border-outline-variant pb-3 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">add_circle</span>
                    <h3 class="font-semibold text-primary">Buat Paket Baru</h3>
                </div>

                <form action="{{ route('admin.plans.create') }}" method="POST"
                    class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf
                    <div>
                        <label class="block font-mono text-[10px] text-on-surface-variant mb-1.5 uppercase">Layanan
                            IaaS</label>
                        <select id="service-select" name="service_id"
                            class="w-full text-sm bg-surface-container-lowest border border-outline-variant rounded p-2 text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none"
                            required>
                            <option value="" disabled selected>-- Pilih Layanan --</option>
                            @foreach ($services as $svc)
                                <option value="{{ $svc->id }}" data-category="{{ $svc->service_category }}">
                                    {{ $svc->service_name }} ({{ $svc->service_category }})</option>
                            @endforeach
                        </select>
                        @error('service_id')
                            <p class="text-[10px] text-error mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-mono text-[10px] text-on-surface-variant mb-1.5 uppercase">Nama
                            Paket</label>
                        <input type="text" name="plan_name" placeholder="Contoh: Premium Storage"
                            class="w-full text-sm bg-surface-container-lowest border border-outline-variant rounded p-2 text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none"
                            required>
                        @error('plan_name')
                            <p class="text-[10px] text-error mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="storage-quota-container" style="display: none;">
                        <label class="block font-mono text-[10px] text-on-surface-variant mb-1.5 uppercase">Penyimpanan
                            (GB)</label>
                        <input type="number" id="storage_quota_input" name="storage_quota_gb" placeholder="0"
                            min="0"
                            class="w-full text-sm bg-surface-container-lowest border border-outline-variant rounded p-2 text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none">
                        @error('storage_quota_gb')
                            <p class="text-[10px] text-error mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="compute-quota-container" style="display: none;">
                        <label class="block font-mono text-[10px] text-on-surface-variant mb-1.5 uppercase">Compute
                            (vCPU)</label>
                        <input type="number" id="compute_quota_input" name="compute_quota_vcpu" placeholder="0"
                            min="0"
                            class="w-full text-sm bg-surface-container-lowest border border-outline-variant rounded p-2 text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none">
                        @error('compute_quota_vcpu')
                            <p class="text-[10px] text-error mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <input type="hidden" name="network_quota_vpc" value="0">

                    <div>
                        <label class="block font-mono text-[10px] text-on-surface-variant mb-1.5 uppercase">Harga
                            Bulanan (Rp)</label>
                        <input type="number" name="monthly_price" placeholder="0" min="0" step="0.01"
                            class="w-full text-sm bg-surface-container-lowest border border-outline-variant rounded p-2 text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none"
                            required>
                        @error('monthly_price')
                            <p class="text-[10px] text-error mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block font-mono text-[10px] text-on-surface-variant mb-1.5 uppercase">Deskripsi /
                            SLA</label>
                        <textarea name="description" rows="2"
                            class="w-full text-sm bg-surface-container-lowest border border-outline-variant rounded p-2 text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none"></textarea>
                        @error('description')
                            <p class="text-[10px] text-error mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2 mt-2">
                        <button type="submit"
                            class="bg-primary hover:bg-primary/90 text-on-primary-fixed w-full py-2 text-sm rounded font-semibold transition">
                            Simpan Paket Baru
                        </button>
                    </div>
                </form>
            </div>

            <script>
                document.getElementById('service-select')?.addEventListener('change', function() {
                    const category = this.options[this.selectedIndex].getAttribute('data-category');
                    document.getElementById('storage-quota-container').style.display = category === 'Storage' ? 'block' :
                        'none';
                    document.getElementById('compute-quota-container').style.display = category === 'Compute' ? 'block' :
                        'none';
                    if (category === 'Storage') document.getElementById('compute_quota_input').value = '0';
                    if (category === 'Compute') document.getElementById('storage_quota_input').value = '0';
                });
            </script>
        </div>
    @endif

    {{-- SUMBER DAYA AKTIF (RESOURCES) TAB --}}
    @if ($tab === 'resources')
        <div class="bg-surface border border-outline-variant rounded overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                    <thead class="bg-surface-container-lowest border-b border-outline-variant">
                        <tr class="font-mono text-on-surface-variant uppercase">
                            <th class="px-4 py-3">Nama Instance / ID</th>
                            <th class="px-4 py-3">Pemilik (User)</th>
                            <th class="px-4 py-3">Tipe & Paket</th>
                            <th class="px-4 py-3">Region</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-right">Dibuat Pada</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse($resources as $resource)
                            <tr class="hover:bg-surface-container-highest transition-colors">
                                <td class="px-4 py-3">
                                    <span
                                        class="font-semibold text-primary block">{{ $resource->instance_name ?? 'N/A' }}</span>
                                    <span class="font-mono text-[10px] text-on-surface-variant">ID:
                                        {{ $resource->id }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="h-6 w-6 rounded-full bg-surface-container-highest flex items-center justify-center text-primary font-bold text-[10px]">
                                            {{ strtoupper(substr($resource->user_name, 0, 1)) }}
                                        </div>
                                        <span
                                            class="font-semibold text-primary block">{{ $resource->user_name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="font-semibold text-primary block uppercase">{{ $resource->resource_type }}</span>
                                    <span
                                        class="font-mono text-[10px] text-on-surface-variant block mt-0.5 truncate max-w-[150px]">{{ $resource->plan_name }}</span>
                                </td>
                                <td class="px-4 py-3 font-mono text-[10px] text-on-surface-variant">
                                    {{ $resource->region ?? 'us-east-1' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="inline-flex px-2.5 py-0.5 text-[9px] font-bold uppercase rounded-full border 
                                        {{ $resource->status === 'running' || $resource->status === 'active'
                                            ? 'bg-primary/20 text-primary border-primary/30'
                                            : ($resource->status === 'stopped' || $resource->status === 'terminated'
                                                ? 'bg-error/15 text-error border-error/30'
                                                : 'bg-surface-container text-on-surface-variant border-outline-variant') }}">
                                        {{ $resource->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right font-mono text-[10px] text-on-surface-variant">
                                    {{ \Carbon\Carbon::parse($resource->created_at)->format('d M Y, H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6"
                                    class="px-4 py-8 text-center text-on-surface-variant text-sm font-mono">
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="material-symbols-outlined text-4xl mb-2 opacity-50">dns</span>
                                        Belum ada sumber daya yang diprovisi oleh pengguna.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- PAYMENTS --}}

    {{-- LOG AKTIVITAS (LOGS) TAB --}}
    @if ($tab === 'logs')
        <div class="bg-surface border border-outline-variant rounded overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                    <thead class="bg-surface-container-lowest border-b border-outline-variant">
                        <tr class="font-mono text-on-surface-variant uppercase">
                            <th class="px-4 py-3">Log ID</th>
                            <th class="px-4 py-3">Pengguna</th>
                            <th class="px-4 py-3">Aksi</th>
                            <th class="px-4 py-3">Keterangan</th>
                            <th class="px-4 py-3 text-right">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse($logs as $log)
                            <tr class="hover:bg-surface-container-highest transition-colors">
                                <td class="px-4 py-3 font-mono text-[10px] text-on-surface-variant">
                                    #{{ $log->id }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        {{-- Inisial Nama --}}
                                        <div
                                            class="h-6 w-6 rounded-full bg-surface-container-highest flex items-center justify-center text-primary font-bold text-[10px]">
                                            {{ strtoupper(substr($log->user_name ?? ($log->user->name ?? 'S'), 0, 1)) }}
                                        </div>
                                        <span class="font-semibold text-primary block">
                                            {{ $log->user_name ?? ($log->user->name ?? 'Sistem') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex px-2 py-0.5 text-[10px] font-bold rounded bg-surface-container-high text-on-surface">
                                        {{ strtoupper($log->action_type ?? 'UMUM') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-on-surface-variant whitespace-normal min-w-[250px]">
                                    {{ $log->description ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-right font-mono text-[10px] text-on-surface-variant">
                                    {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, H:i:s') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"
                                    class="px-4 py-8 text-center text-on-surface-variant text-sm font-mono">
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="material-symbols-outlined text-4xl mb-2 opacity-50">history</span>
                                        Belum ada log aktivitas yang terekam.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Menampilkan Pagination Jika Log Menggunakan Method paginate() di Controller --}}
            @if (method_exists($logs, 'links'))
                <div class="p-4 border-t border-outline-variant bg-surface-container-lowest">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    @endif
</x-admin-layout>
