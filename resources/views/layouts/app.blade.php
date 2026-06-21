<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1"
        rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        // Sync page's dark class
        document.documentElement.classList.add('dark');
    </script>
</head>

<body
    class="bg-[#0F0F0F] text-on-surface font-sans antialiased min-h-screen flex selection:bg-primary-container selection:text-on-primary-container">
    @php
        $currentTab = request()->query('tab', 'overview');
        $isDashboard = request()->routeIs('dashboard');
        // $isAdmin = request()->is('admin'); <-- (Opsional: baris ini bisa dihapus karena sudah tidak dipakai untuk menandai tab aktif di dashboard)
        $isProfile = request()->routeIs('profile.edit');
    @endphp

    <aside
        class="bg-surface border-r border-outline-variant fixed left-0 top-0 h-full w-60 flex flex-col justify-between py-lg z-50 transition-colors duration-150">
        <div class="px-md">
            <div class="mb-xl flex items-center px-sm gap-sm">
                {{-- <span class="material-symbols-outlined text-primary text-3xl" style="font-variation-settings: 'FILL' 1;">cloud</span> --}}
                <span class="material-symbols-outlined text-primary text-3xl"
                    style="font-variation-settings: 'FILL' 1;">cyclone</span>
                <div>
                    <h1 class="font-bold text-lg text-primary leading-none">Vortex Cloud</h1>
                    <p class="font-mono text-[10px] text-on-surface-variant mt-1">Infrastructure v1.0</p>
                </div>
            </div>

            <nav class="flex flex-col gap-base">
                <a class="{{ $isDashboard && $currentTab === 'overview' ? 'nav-item-active' : 'nav-item-inactive' }}"
                    href="{{ route('dashboard', ['tab' => 'overview']) }}">
                    <span class="material-symbols-outlined text-xl">dashboard</span>
                    <span>Dashboard</span>
                </a>

                <a class="{{ $isDashboard && $currentTab === 'resources' ? 'nav-item-active' : 'nav-item-inactive' }}"
                    href="{{ route('dashboard', ['tab' => 'resources']) }}">
                    <span class="material-symbols-outlined text-xl">cloud</span>
                    <span>Resource Cloud</span>
                </a>

                <a class="{{ $isDashboard && $currentTab === 'plans' ? 'nav-item-active' : 'nav-item-inactive' }}"
                    href="{{ route('dashboard', ['tab' => 'plans']) }}">
                    <span class="material-symbols-outlined text-xl">payments</span>
                    <span>Paket Langganan</span>
                </a>

                <a class="{{ $isDashboard && $currentTab === 'credentials' ? 'nav-item-active' : 'nav-item-inactive' }}"
                    href="{{ route('dashboard', ['tab' => 'credentials']) }}">
                    <span class="material-symbols-outlined text-xl">key</span>
                    <span>Kredensial API</span>
                </a>

                <a class="{{ $isDashboard && $currentTab === 'logs' ? 'nav-item-active' : 'nav-item-inactive' }}"
                    href="{{ route('dashboard', ['tab' => 'logs']) }}">
                    <span class="material-symbols-outlined text-xl">history</span>
                    <span>Log Aktivitas</span>
                </a>

                @if (Auth::user()->role === 'admin')
                    <div class="h-px bg-outline-variant my-2"></div>
                    <a class="nav-item-inactive hover:text-primary transition-colors"
                        href="{{ route('admin.index') }}"> <span
                            class="material-symbols-outlined text-xl">shield_person</span>
                        <span>Mode Admin</span>
                    </a>
                @endif
            </nav>
        </div>

        <div class="px-md flex flex-col gap-2">
            <a class="{{ $isProfile ? 'nav-item-active' : 'nav-item-inactive' }} border-t border-outline-variant pt-md"
                href="{{ route('profile.edit') }}">
                <span class="material-symbols-outlined text-xl">account_circle</span>
                <span class="truncate">Profile ({{ Auth::user()->name }})</span>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-md px-md py-sm rounded-DEFAULT text-error hover:bg-error/10 hover:text-error transition-all duration-200 text-left font-semibold">
                    <span class="material-symbols-outlined text-xl">logout</span>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="ml-60 flex-1 flex flex-col min-h-screen">
        <header
            class="bg-surface border-b border-outline-variant fixed top-0 right-0 w-[calc(100%-240px)] flex justify-between items-center h-16 px-lg z-40 transition-colors duration-150">
            <div class="flex items-center text-on-surface-variant w-96">
                {{-- <span class="material-symbols-outlined text-xl mr-sm">search</span>
                <input
                    class="bg-transparent border-none outline-none text-sm placeholder:text-outline focus:ring-0 w-full"
                    placeholder="Search resources, files, or configs..." type="text" /> --}}
            </div>
            <div class="flex items-center gap-md text-on-surface-variant">
                {{-- <button
                    class="hover:bg-surface-container-highest rounded-lg p-sm transition-colors duration-150 flex items-center justify-center">
                    <span class="material-symbols-outlined">notifications</span>
                </button> --}}
                <div class="h-8 w-px bg-outline-variant"></div>
                <div class="flex items-center gap-2">
                    <div
                        class="w-8 h-8 rounded-full bg-surface-container-highest flex items-center justify-center font-mono font-bold text-primary">
                        {{ substr(Auth::user()->name, 0, 2) }}
                    </div>
                    <span class="text-sm font-semibold text-primary hidden md:inline">{{ Auth::user()->name }}</span>
                </div>
            </div>
        </header>

        <main class="flex-1 mt-16 p-margin-desktop space-y-xl">
            @isset($header)
                <div class="border-b border-outline-variant pb-md">
                    {{ $header }}
                </div>
            @endisset

            {{ $slot }}
        </main>
    </div>
</body>

</html>
