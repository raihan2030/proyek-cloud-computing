<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-md">
            <div>
                <h2 class="font-bold text-2xl text-primary tracking-tight">Manajemen Pengguna</h2>
                <p class="text-sm text-on-surface-variant mt-1">Kelola akses, instansi, dan paket langganan pengguna cloud.</p>
            </div>
            <div class="flex items-center gap-sm shrink-0 w-full sm:w-auto mt-2 sm:mt-0">
                <div class="relative flex-1 sm:w-64">
                    <span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none text-[20px]">search</span>
                    <input class="w-full bg-surface-container-lowest border border-outline-variant rounded py-1 pl-10 pr-md text-sm text-on-surface placeholder-on-surface-variant focus:outline-none focus:border-primary transition-colors" placeholder="Cari pengguna..." type="text"/>
                </div>
                <button class="btn-primary px-4 py-2 text-xs font-semibold uppercase tracking-wider rounded flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">add</span>
                    Tambah Pengguna
                </button>
            </div>
        </div>
    </x-slot>

    <!-- Bento Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter mb-lg">
        <div class="stat-card">
            <span class="font-mono text-[10px] text-on-surface-variant uppercase tracking-wider">Total Pengguna</span>
            <div class="flex items-end gap-sm mt-sm">
                <span class="text-3xl font-bold text-primary">1,248</span>
                <span class="font-mono text-xs text-secondary mb-1">+12%</span>
            </div>
        </div>
        <div class="stat-card">
            <span class="font-mono text-[10px] text-on-surface-variant uppercase tracking-wider">Pro Subscribers</span>
            <div class="flex items-end gap-sm mt-sm">
                <span class="text-3xl font-bold text-primary">412</span>
                <span class="font-mono text-xs text-secondary mb-1">+5%</span>
            </div>
        </div>
        <div class="stat-card">
            <span class="font-mono text-[10px] text-on-surface-variant uppercase tracking-wider">Active Instances</span>
            <div class="flex items-end gap-sm mt-sm">
                <span class="text-3xl font-bold text-primary">89</span>
                <span class="font-mono text-xs text-on-surface-variant mb-1">-2%</span>
            </div>
        </div>
    </div>

    <!-- Data Table Container -->
    <div class="stat-card p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead class="bg-surface-container border-b border-outline-variant">
                    <tr class="font-mono text-[11px] text-on-surface-variant uppercase tracking-wider">
                        <th class="py-3 px-md font-semibold">ID</th>
                        <th class="py-3 px-md font-semibold">Nama</th>
                        <th class="py-3 px-md font-semibold">Email</th>
                        <th class="py-3 px-md font-semibold">Paket</th>
                        <th class="py-3 px-md font-semibold">Status</th>
                        <th class="py-3 px-md font-semibold text-right">Dibuat</th>
                        <th class="py-3 px-md"></th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-outline-variant/30 text-on-surface">
                    <!-- Row 1 -->
                    <tr class="hover:bg-surface-container-highest transition-colors">
                        <td class="py-4 px-md font-mono text-on-surface-variant">USR-0921</td>
                        <td class="py-4 px-md font-semibold text-primary flex items-center gap-sm">
                            <div class="w-8 h-8 rounded bg-surface-container-highest flex items-center justify-center text-primary font-bold">JD</div>
                            John Doe
                        </td>
                        <td class="py-4 px-md text-on-surface-variant">john.doe@example.com</td>
                        <td class="py-4 px-md text-on-surface-variant font-mono">Pro</td>
                        <td class="py-4 px-md">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-primary text-on-primary-fixed uppercase font-mono">
                                Active
                            </span>
                        </td>
                        <td class="py-4 px-md font-mono text-on-surface-variant text-right">Oct 12, 2023</td>
                        <td class="py-4 px-md text-right">
                            <button aria-label="More options" class="p-1 text-on-surface-variant hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-[18px]">more_vert</span>
                            </button>
                        </td>
                    </tr>
                    <!-- Row 2 -->
                    <tr class="hover:bg-surface-container-highest transition-colors">
                        <td class="py-4 px-md font-mono text-on-surface-variant">USR-0922</td>
                        <td class="py-4 px-md font-semibold text-primary flex items-center gap-sm">
                            <div class="w-8 h-8 rounded bg-surface-container-highest flex items-center justify-center text-primary font-bold">AS</div>
                            Alice Smith
                        </td>
                        <td class="py-4 px-md text-on-surface-variant">alice.smith@example.com</td>
                        <td class="py-4 px-md text-on-surface-variant font-mono">Enterprise</td>
                        <td class="py-4 px-md">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-bold border border-outline-variant text-primary uppercase font-mono">
                                Pending
                            </span>
                        </td>
                        <td class="py-4 px-md font-mono text-on-surface-variant text-right">Oct 14, 2023</td>
                        <td class="py-4 px-md text-right">
                            <button aria-label="More options" class="p-1 text-on-surface-variant hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-[18px]">more_vert</span>
                            </button>
                        </td>
                    </tr>
                    <!-- Row 3 -->
                    <tr class="hover:bg-surface-container-highest transition-colors">
                        <td class="py-4 px-md font-mono text-on-surface-variant">USR-0923</td>
                        <td class="py-4 px-md font-semibold text-primary flex items-center gap-sm">
                            <div class="w-8 h-8 rounded bg-surface-container-highest flex items-center justify-center text-primary font-bold">BW</div>
                            Bob Wilson
                        </td>
                        <td class="py-4 px-md text-on-surface-variant">bob.wilson@example.com</td>
                        <td class="py-4 px-md text-on-surface-variant font-mono">Basic</td>
                        <td class="py-4 px-md">
                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-error/15 text-error text-[9px] font-bold uppercase font-mono">
                                Suspended
                            </span>
                        </td>
                        <td class="py-4 px-md font-mono text-on-surface-variant text-right">Oct 15, 2023</td>
                        <td class="py-4 px-md text-right">
                            <button aria-label="More options" class="p-1 text-on-surface-variant hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-[18px]">more_vert</span>
                            </button>
                        </td>
                    </tr>
                    <!-- Row 4 -->
                    <tr class="hover:bg-surface-container-highest transition-colors">
                        <td class="py-4 px-md font-mono text-on-surface-variant">USR-0924</td>
                        <td class="py-4 px-md font-semibold text-primary flex items-center gap-sm">
                            <div class="w-8 h-8 rounded bg-surface-container-highest flex items-center justify-center text-primary font-bold">EJ</div>
                            Emma Johnson
                        </td>
                        <td class="py-4 px-md text-on-surface-variant">emma.j@example.com</td>
                        <td class="py-4 px-md text-on-surface-variant font-mono">Pro</td>
                        <td class="py-4 px-md">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-primary text-on-primary-fixed uppercase font-mono">
                                Active
                            </span>
                        </td>
                        <td class="py-4 px-md font-mono text-on-surface-variant text-right">Oct 18, 2023</td>
                        <td class="py-4 px-md text-right">
                            <button aria-label="More options" class="p-1 text-on-surface-variant hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-[18px]">more_vert</span>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Footer -->
        <div class="px-md py-sm border-t border-outline-variant bg-surface flex items-center justify-between font-mono text-xs">
            <span class="text-on-surface-variant">Showing 1 to 4 of 1,248</span>
            <div class="flex gap-xs">
                <button class="px-sm py-1 border border-outline-variant rounded text-on-surface-variant hover:bg-surface-container-highest hover:text-primary disabled:opacity-50 disabled:cursor-not-allowed transition-colors" disabled>
                    Previous
                </button>
                <button class="px-sm py-1 border border-outline-variant rounded text-on-surface-variant hover:bg-surface-container-highest hover:text-primary transition-colors">
                    Next
                </button>
            </div>
        </div>
    </div>
</x-app-layout>
