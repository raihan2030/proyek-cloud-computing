<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'MiniStack Cloud') }} - Authentication</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-primary bg-surface-container-lowest">
        <!-- Tambahkan class relative di sini agar tombol absolute mengikuti kontainer ini -->
        <div class="relative min-h-screen flex flex-col sm:justify-center items-center pt-16 sm:pt-0 my-6">
            
            <!-- Tombol Back to Home -->
            <a href="/" class="absolute top-6 left-6 sm:top-8 sm:left-8 flex items-center gap-2 text-sm font-mono text-on-surface-variant hover:text-primary transition-colors group">
                <span class="material-symbols-outlined text-lg group-hover:-translate-x-1 transition-transform">arrow_back</span>
                <span class="hidden sm:inline uppercase tracking-wider text-[10px]">Back to Home</span>
            </a>

            <!-- Logo Section -->
            <div class="mb-8 text-center">
                <a href="/" class="flex flex-col items-center gap-2 group">
                    <div class="h-16 w-16 bg-primary/10 rounded-2xl border border-outline-variant flex items-center justify-center group-hover:border-primary transition-colors">
                        <span class="material-symbols-outlined text-primary text-3xl" style="font-variation-settings: 'FILL' 1;">cyclone</span>
                    </div>
                    <span class="font-bold text-2xl text-primary tracking-tight mt-2">Vortex Cloud</span>
                </a>
            </div>

            <!-- Card Section -->
            <div class="w-full sm:max-w-md px-8 py-8 bg-surface border border-outline-variant shadow-lg sm:rounded-xl">
                {{ $slot }}
            </div>
            
        </div>
    </body>
</html>