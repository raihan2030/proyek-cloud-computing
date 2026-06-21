<nav x-data="{ open: false }" class="bg-surface-container-lowest border-b border-outline-variant sticky top-0 z-50 transition-colors duration-300">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo Admin -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('admin.index') }}" class="flex items-center gap-2 group">
                        <span class="material-symbols-outlined text-primary text-3xl group-hover:scale-110 transition-transform">shield_person</span>
                        <span class="font-bold text-lg text-primary tracking-tight hidden sm:block">Admin Panel</span>
                    </a>
                </div>

                <!-- Navigation Links (Pengganti Sidebar) -->
                <div class="hidden space-x-2 sm:-my-px sm:ms-10 sm:flex">
                    @php $tab = request()->query('tab', $tab ?? 'overview'); @endphp
                    
                    <x-nav-link :href="route('admin.index')" :active="$tab === 'overview'">
                        <span class="material-symbols-outlined text-[18px] mr-1.5">dashboard</span> Ringkasan
                    </x-nav-link>
                    <x-nav-link :href="route('admin.users')" :active="$tab === 'users'">
                        <span class="material-symbols-outlined text-[18px] mr-1.5">group</span> Pengguna
                    </x-nav-link>
                    <x-nav-link :href="route('admin.plans')" :active="$tab === 'plans'">
                        <span class="material-symbols-outlined text-[18px] mr-1.5">inventory_2</span> Paket
                    </x-nav-link>
                    <x-nav-link :href="route('admin.resources')" :active="$tab === 'resources'">
                        <span class="material-symbols-outlined text-[18px] mr-1.5">dns</span> Resource
                    </x-nav-link>
                    <x-nav-link :href="route('admin.payments')" :active="$tab === 'payments'">
                        <span class="material-symbols-outlined text-[18px] mr-1.5">payments</span> Transaksi
                    </x-nav-link>
                    <x-nav-link :href="route('admin.logs')" :active="$tab === 'logs'">
                        <span class="material-symbols-outlined text-[18px] mr-1.5">history</span> Log
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Tombol kembali ke Dashboard User -->
                <a href="{{ route('dashboard') }}" class="text-xs font-semibold text-on-surface-variant hover:text-primary transition-colors mr-4 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">exit_to_app</span> Mode User
                </a>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-on-surface-variant bg-surface hover:text-primary focus:outline-none transition ease-in-out duration-150 group">
                            <div class="flex items-center gap-2">
                                <div class="h-6 w-6 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold text-xs">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span class="font-semibold">{{ Auth::user()->name }}</span>
                            </div>
                            <div class="ms-1">
                                <span class="material-symbols-outlined text-sm group-hover:translate-y-0.5 transition-transform">expand_more</span>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">person</span> Profil Admin
                            </div>
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();" class="text-error hover:bg-error/10">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px]">logout</span> Log Out
                                </div>
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-on-surface-variant hover:text-primary hover:bg-surface-container focus:outline-none focus:bg-surface-container focus:text-primary transition duration-150 ease-in-out">
                    <span class="material-symbols-outlined text-2xl" x-show="!open">menu</span>
                    <span class="material-symbols-outlined text-2xl" x-show="open" style="display: none;">close</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-surface-container-lowest border-b border-outline-variant">
        <div class="pt-2 pb-3 space-y-1">
            @php $tab = request()->query('tab', $tab ?? 'overview'); @endphp
            <x-responsive-nav-link :href="route('admin.index')" :active="$tab === 'overview'">Ringkasan</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.users')" :active="$tab === 'users'">Pengguna</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.plans')" :active="$tab === 'plans'">Paket</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.resources')" :active="$tab === 'resources'">Resource</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.payments')" :active="$tab === 'payments'">Transaksi</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.logs')" :active="$tab === 'logs'">Log</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard')">Mode User</x-responsive-nav-link>
        </div>
        <!-- (Responsive Profile Settings omitted for brevity, you can copy from navigation.blade.php) -->
    </div>
</nav>