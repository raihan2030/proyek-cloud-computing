<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CloudOS - Platform IaaS Terpercaya</title>
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .glow-effect {
            position: relative;
        }
        .glow-effect::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            padding: 1px;
            background: linear-gradient(to right, rgba(99, 102, 241, 0.2), rgba(168, 85, 247, 0.2));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }
        .bg-glow-blob {
            filter: blur(120px);
            z-index: 0;
            animation: pulse-glow 8s infinite alternate ease-in-out;
        }
        @keyframes pulse-glow {
            0% { transform: scale(1) translate(0px, 0px); opacity: 0.15; }
            100% { transform: scale(1.1) translate(30px, -20px); opacity: 0.3; }
        }
        .animated-card {
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .animated-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.05), 0 10px 10px -5px rgba(15, 23, 42, 0.04);
            border-color: rgba(99, 102, 241, 0.4);
        }
        .fade-in-up {
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-[#0F0F0F] text-on-surface font-sans min-h-screen selection:bg-primary-container selection:text-on-primary-container relative overflow-x-hidden antialiased">
    <!-- Ambient Backdrop Glows -->
    <div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-white/5 rounded-full blur-[150px] pointer-events-none"></div>
    <div class="absolute top-1/2 right-1/4 w-[600px] h-[600px] bg-white/3 rounded-full blur-[180px] pointer-events-none"></div>

    <!-- Navigation Header -->
    <nav class="bg-surface/80 backdrop-blur-md border-b border-outline-variant fixed top-0 w-full z-50 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <span class="material-symbols-outlined text-primary text-2xl" style="font-variation-settings: 'FILL' 1;">cloud</span>
                    <span class="text-lg font-bold text-primary tracking-tight">CloudOS</span>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-6">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-on-surface-variant hover:text-primary transition-colors">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="btn-primary text-xs uppercase tracking-wider px-4 py-2 rounded">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-on-surface-variant hover:text-primary transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="btn-primary text-xs uppercase tracking-wider px-4 py-2 rounded">
                            Register
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button class="text-on-surface-variant hover:text-primary transition-colors" id="mobile-menu-btn" aria-label="Toggle menu">
                        <span class="material-symbols-outlined text-2xl">menu</span>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Dropdown Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-surface border-b border-outline-variant px-4 py-4 space-y-3">
            @auth
                <a href="{{ route('dashboard') }}" class="block text-sm font-semibold text-on-surface-variant hover:text-primary py-2">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full btn-primary text-center py-2 rounded text-xs uppercase tracking-wider">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block text-sm font-semibold text-on-surface-variant hover:text-primary py-2">Login</a>
                <a href="{{ route('register') }}" class="block w-full btn-primary text-center py-2 rounded text-xs uppercase tracking-wider">
                    Register
                </a>
            @endauth
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 md:pt-48 md:pb-32 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center gap-2 bg-surface-container-highest border border-outline-variant px-3 py-1 rounded-full text-[10px] font-bold font-mono tracking-widest text-primary uppercase mb-6">
                <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                Next-Gen IaaS Engine
            </div>
            <h1 class="text-4xl md:text-6xl font-bold text-primary tracking-tight mb-6 max-w-4xl mx-auto leading-tight">
                Transformasi Digital Bisnis Anda dengan CloudOS
            </h1>
            <p class="text-sm md:text-base text-on-surface-variant mb-10 max-w-2xl mx-auto leading-relaxed">
                Platform Infrastructure as a Service (IaaS) dengan performa ekstrim, skalabilitas instan, dan kontrol total. Dibangun untuk developer dan bisnis modern yang mengutamakan kecepatan dan reliabilitas.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                @guest
                    <a href="{{ route('register') }}" class="btn-primary px-8 py-3 text-xs uppercase tracking-wider rounded w-full sm:w-auto text-center font-bold">
                        Mulai Sekarang
                    </a>
                    <a href="{{ route('login') }}" class="btn-secondary px-8 py-3 text-xs uppercase tracking-wider rounded w-full sm:w-auto text-center font-bold">
                        Masuk Akun
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn-primary px-8 py-3 text-xs uppercase tracking-wider rounded w-full sm:w-auto text-center font-bold">
                        Buka Dashboard
                    </a>
                @endguest
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-surface border-y border-outline-variant relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-2xl md:text-3xl font-bold text-primary tracking-tight">Layanan Unggulan Kami</h2>
                <p class="text-xs text-on-surface-variant mt-2 font-mono">High-performance building blocks for infrastructure.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-lg">
                <!-- S3 Storage Card -->
                <div class="bg-surface-container border border-outline-variant rounded-lg p-lg hover:border-primary transition-all duration-300 group">
                    <div class="w-10 h-10 bg-primary/10 border border-outline-variant rounded flex items-center justify-center text-primary mb-6 transition-colors group-hover:bg-primary group-hover:text-on-primary-fixed">
                        <span class="material-symbols-outlined text-lg">hard_drive</span>
                    </div>
                    <h3 class="text-lg font-bold text-primary mb-3">S3 Storage</h3>
                    <p class="text-xs text-on-surface-variant leading-relaxed">
                        Penyimpanan cloud object yang scalable, terisolasi, dan aman dengan redundansi data. Didukung integrasi API access key.
                    </p>
                </div>

                <!-- EC2 Compute Card -->
                <div class="bg-surface-container border border-outline-variant rounded-lg p-lg hover:border-primary transition-all duration-300 group">
                    <div class="w-10 h-10 bg-primary/10 border border-outline-variant rounded flex items-center justify-center text-primary mb-6 transition-colors group-hover:bg-primary group-hover:text-on-primary-fixed">
                        <span class="material-symbols-outlined text-lg">dns</span>
                    </div>
                    <h3 class="text-lg font-bold text-primary mb-3">EC2 Compute</h3>
                    <p class="text-xs text-on-surface-variant leading-relaxed">
                        Instance compute vCPU privat yang dapat Anda kelola secara langsung (Start, Stop, Terminate) di dalam dashboard.
                    </p>
                </div>

                <!-- VPS Network Card -->
                <div class="bg-surface-container border border-outline-variant rounded-lg p-lg hover:border-primary transition-all duration-300 group">
                    <div class="w-10 h-10 bg-primary/10 border border-outline-variant rounded flex items-center justify-center text-primary mb-6 transition-colors group-hover:bg-primary group-hover:text-on-primary-fixed">
                        <span class="material-symbols-outlined text-lg">lan</span>
                    </div>
                    <h3 class="text-lg font-bold text-primary mb-3">VPS Network</h3>
                    <p class="text-xs text-on-surface-variant leading-relaxed">
                        Jaringan virtual private terdedikasi untuk perlindungan latensi rendah, bandwidth optimal, serta throughput tinggi.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-lg text-center">
                <!-- Stat 1: Customers -->
                <div class="stat-card bg-surface-container border border-outline-variant p-lg rounded">
                    <p class="text-3xl md:text-4xl font-bold text-primary tracking-tight mb-2">5,000+</p>
                    <p class="text-xs font-mono text-on-surface-variant uppercase tracking-wider">Pelanggan Puas</p>
                </div>

                <!-- Stat 2: Uptime -->
                <div class="stat-card bg-surface-container border border-outline-variant p-lg rounded">
                    <p class="text-3xl md:text-4xl font-bold text-primary tracking-tight mb-2">99.9%</p>
                    <p class="text-xs font-mono text-on-surface-variant uppercase tracking-wider">Uptime SLA</p>
                </div>

                <!-- Stat 3: Support -->
                <div class="stat-card bg-surface-container border border-outline-variant p-lg rounded">
                    <p class="text-3xl md:text-4xl font-bold text-primary tracking-tight mb-2">24/7</p>
                    <p class="text-xs font-mono text-on-surface-variant uppercase tracking-wider">Expert Support</p>
                </div>

                <!-- Stat 4: Data Centers -->
                <div class="stat-card bg-surface-container border border-outline-variant p-lg rounded">
                    <p class="text-3xl md:text-4xl font-bold text-primary tracking-tight mb-2">50+</p>
                    <p class="text-xs font-mono text-on-surface-variant uppercase tracking-wider">Data Centers</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-surface border-t border-outline-variant text-on-surface-variant py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-lg mb-12">
                <!-- About -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-3 text-primary">
                        <span class="material-symbols-outlined text-xl" style="font-variation-settings: 'FILL' 1;">cloud</span>
                        <span class="font-bold tracking-tight">CloudOS</span>
                    </div>
                    <p class="text-xs leading-relaxed">Platform IaaS terpercaya untuk transformasi digital infrastruktur bisnis Anda.</p>
                </div>

                <!-- Products -->
                <div>
                    <h4 class="text-xs font-bold text-primary uppercase tracking-wider mb-4 font-mono">Layanan</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="#" class="hover:text-primary transition-colors">S3 Storage</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">EC2 Compute</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">VPS Network</a></li>
                    </ul>
                </div>

                <!-- Company -->
                <div>
                    <h4 class="text-xs font-bold text-primary uppercase tracking-wider mb-4 font-mono">Perusahaan</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="#" class="hover:text-primary transition-colors">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Karir</a></li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h4 class="text-xs font-bold text-primary uppercase tracking-wider mb-4 font-mono">Legalitas</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="#" class="hover:text-primary transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Security Audit</a></li>
                    </ul>
                </div>
            </div>

            <!-- Copyright -->
            <div class="border-t border-outline-variant/30 pt-8 text-center text-xs">
                <p>&copy; 2024 CloudOS. Semua hak dilindungi. Powered by CloudOS Infrastructure.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const menuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        if (menuBtn && mobileMenu) {
            menuBtn.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }
    </script>
</body>
</html>
