<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CloudOS - Platform IaaS Terpercaya</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
<body class="bg-slate-50/50 text-slate-900 selection:bg-indigo-500 selection:text-white antialiased overflow-x-hidden min-h-screen flex flex-col relative">
    <!-- Background Glow Blobs (Light Mode soft gradients) -->
    <div class="absolute top-[-5%] left-[-5%] w-[45vw] h-[45vw] rounded-full bg-gradient-to-tr from-indigo-300/30 to-purple-300/30 bg-glow-blob pointer-events-none"></div>
    <div class="absolute bottom-[15%] right-[-5%] w-[40vw] h-[40vw] rounded-full bg-gradient-to-br from-pink-300/20 to-indigo-300/20 bg-glow-blob pointer-events-none"></div>

    <!-- Navigation Header -->
    <nav class="sticky top-0 z-50 backdrop-blur-md bg-white/75 border-b border-slate-200/80 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="/" class="flex items-center space-x-2.5 focus:outline-none">
                    <div class="w-9 h-9 bg-gradient-to-tr from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/10">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
                        </svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-slate-900">CloudOS</span>
                </a>

                <!-- Navigation Links (Desktop) -->
                <div class="hidden md:flex items-center space-x-8">
                    @auth
                        <div class="flex items-center space-x-3">
                            <span class="text-xs text-slate-500">Masuk sebagai:</span>
                            <span class="text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</span>
                            @if(Auth::user()->role === 'admin')
                                <span class="bg-indigo-50 text-indigo-700 border border-indigo-100 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider">Admin</span>
                            @endif
                        </div>
                        <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 text-sm font-bold bg-white text-slate-700 rounded-xl border border-slate-200 hover:bg-slate-50 hover:text-slate-950 transition">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition">Login</a>
                        <a href="{{ route('register') }}" class="px-4.5 py-2.5 text-sm font-bold bg-gray-800 text-white rounded-xl hover:bg-gray-700 transition shadow-sm">Register</a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button class="text-slate-600 hover:text-slate-900" id="mobile-menu-btn" aria-label="Menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Tray -->
        <div class="hidden md:hidden border-t border-slate-200 bg-white px-4 py-4 space-y-3" id="mobile-menu-tray">
            @auth
                <div class="px-3 py-2 text-sm border-b border-slate-100">
                    <div class="text-slate-400 text-xs">Logged in as</div>
                    <div class="text-slate-800 font-bold text-base truncate">{{ Auth::user()->name }}</div>
                </div>
                <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-base font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-lg">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" class="block w-full">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 text-base font-semibold text-rose-600 hover:text-rose-700 hover:bg-slate-50 rounded-lg">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block px-3 py-2 text-base font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-lg">Login</a>
                <a href="{{ route('register') }}" class="block px-3 py-2.5 text-center text-base font-bold bg-gray-800 text-white rounded-xl">Register</a>
            @endauth
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative z-10 flex-grow flex items-center py-16 md:py-24 fade-in-up">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <!-- Hero Left Info -->
                <div class="lg:col-span-7 text-center lg:text-left">
                    @auth
                        <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-full text-xs font-semibold mb-6">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>Sistem Cloud Aktif</span>
                        </div>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 mb-6 leading-tight">
                            Selamat Datang Kembali,<br>
                            <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 via-purple-600 to-fuchsia-600">{{ Auth::user()->name }}</span>! 👋
                        </h1>
                        <p class="text-base md:text-lg text-slate-500 mb-8 max-w-xl">
                            Kelola resource komputasi dan penyimpanan cloud Anda secara real-time. Status akun dan monitoring dapat diakses langsung dari dashboard personal Anda.
                        </p>
                    @else
                        <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 bg-indigo-50 text-indigo-700 border border-indigo-100 rounded-full text-xs font-semibold mb-6">
                            <span>🚀 CloudOS Platform Baru</span>
                        </div>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 mb-6 leading-tight">
                            Infrastruktur Cloud<br>
                            <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 via-purple-600 to-fuchsia-600">Mudah & Terpercaya</span>
                        </h1>
                        <p class="text-base md:text-lg text-slate-500 mb-8 max-w-xl">
                            Platform Infrastructure as a Service (IaaS) instan untuk meluncurkan server virtual EC2 dan Cloud Storage S3 dalam hitungan detik. Kinerja optimal, skalabilitas tinggi, harga bersahabat.
                        </p>
                    @endauth

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        @guest
                            <a href="{{ route('register') }}" class="px-8 py-4 bg-gray-800 text-white rounded-xl hover:bg-gray-750 transition font-bold shadow-md shadow-slate-900/10 text-center transform hover:scale-[1.02] transition-transform duration-200">
                                Mulai Sekarang
                            </a>
                            <a href="{{ route('login') }}" class="px-8 py-4 bg-white text-slate-700 rounded-xl border border-slate-200 hover:bg-slate-50 hover:text-slate-900 transition font-bold text-center">
                                Masuk Akun
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="px-8 py-4 bg-gray-800 text-white rounded-xl hover:bg-gray-750 transition font-bold shadow-md shadow-slate-900/10 text-center transform hover:scale-[1.02] transition-transform duration-200">
                                Buka Dashboard
                            </a>
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.index') }}" class="px-8 py-4 bg-white text-slate-700 rounded-xl border border-slate-200 hover:bg-slate-50 hover:text-slate-900 transition font-bold text-center">
                                    Buka Panel Admin
                                </a>
                            @endif
                        @endguest
                    </div>
                </div>

                <!-- Hero Right Component (Dynamic UI Card) -->
                <div class="lg:col-span-5">
                    @auth
                        <!-- Personalized Workspace Widget (Light Mode Glassmorphic) -->
                        <div class="glow-effect bg-white/80 backdrop-blur-md rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/50 p-6 relative overflow-hidden transform hover:scale-[1.01] transition-all duration-300">
                            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-indigo-500/5 to-transparent rounded-bl-3xl"></div>
                            
                            <h3 class="text-lg font-bold text-slate-800 mb-5 flex items-center space-x-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-indigo-500 shadow-sm shadow-indigo-500/50"></span>
                                <span>Ruang Kerja Anda</span>
                            </h3>

                            <!-- Balance Widget -->
                            <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 mb-5">
                                <span class="text-xs text-slate-400 font-bold uppercase tracking-wider block">Virtual Balance (Saldo)</span>
                                <div class="text-2xl font-extrabold text-slate-900 mt-1">
                                    Rp {{ number_format(Auth::user()->virtual_balance, 2, ',', '.') }}
                                </div>
                            </div>

                            <!-- User Info Stats Grid -->
                            <div class="grid grid-cols-2 gap-4 mb-5">
                                <div class="bg-slate-50/50 rounded-2xl p-3 border border-slate-100/80">
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Level Hak Akses</span>
                                    <div class="text-sm font-bold text-slate-700 mt-1 capitalize">{{ Auth::user()->role }}</div>
                                </div>
                                <div class="bg-slate-50/50 rounded-2xl p-3 border border-slate-100/80">
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Layanan Aktif</span>
                                    <div class="text-sm font-bold text-indigo-600 mt-1">
                                        {{ $activeResourcesCount ?? 0 }} Running
                                    </div>
                                </div>
                            </div>

                            <!-- Fast shortcut details -->
                            <div class="border-t border-slate-100 pt-4 flex items-center justify-between text-xs text-slate-400">
                                <span class="truncate">Email: {{ Auth::user()->email }}</span>
                                <span class="shrink-0 flex items-center space-x-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    <span class="text-[10px] font-semibold text-slate-500">Koneksi Aman</span>
                                </span>
                            </div>
                        </div>
                    @else
                        <!-- Interactive Static Tech Stack Mockup (Light Mode Code Block) -->
                        <div class="glow-effect bg-white/90 backdrop-blur-md rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/50 p-6 relative">
                            <!-- Terminal Mockup Header -->
                            <div class="flex items-center space-x-1.5 pb-4 border-b border-slate-100">
                                <div class="w-3 h-3 rounded-full bg-rose-400"></div>
                                <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                                <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
                                <span class="text-[10px] text-slate-400 font-mono pl-2">cloudos-api-cli.sh</span>
                            </div>
                            <div class="mt-4 font-mono text-xs text-slate-700 space-y-2.5">
                                <p class="text-slate-400">$ curl -X GET https://api.cloudos.io/status</p>
                                <p class="text-indigo-600">{"status": "online", "region": "ap-southeast-1"}</p>
                                <p class="text-slate-400">$ cloudos deploy compute-instance --plan t2.micro</p>
                                <p class="text-slate-600">⚡ Provisioning MiniStack EC2 instance...</p>
                                <p class="text-emerald-600 font-semibold">✔ Instance successfully running [i-0bf45812a]</p>
                                <p class="text-slate-400">$ _</p>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <!-- Services Overview Section -->
    <section class="py-20 bg-white border-t border-slate-200/60 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4">
                    Infrastruktur Cloud Sesuai Kebutuhan Anda
                </h2>
                <p class="text-slate-500">
                    Kami menyediakan komponen infrastruktur dasar terkelola untuk performa terbaik aplikasi Anda.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <!-- S3 Storage Card -->
                <div class="animated-card bg-slate-50/50 border border-slate-200 rounded-2xl p-6 md:p-8 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl mb-6 flex items-center justify-center border border-indigo-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">S3 Object Storage</h3>
                        <p class="text-slate-500 text-sm leading-relaxed mb-6">
                            Penyimpanan awan berbasis obyek (bucket) berkeamanan tinggi. Cocok untuk file multimedia, backup database, dan aset web Anda. Kapasitas fleksibel dengan kecepatan transfer prima.
                        </p>
                    </div>
                    <div class="flex items-center text-xs text-indigo-600 font-bold space-x-1">
                        <span>Penyimpanan Unlimited</span>
                        <span>•</span>
                        <span>Mulai Rp 25.000 / bln</span>
                    </div>
                </div>

                <!-- EC2 Compute Card -->
                <div class="animated-card bg-slate-50/50 border border-slate-200 rounded-2xl p-6 md:p-8 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl mb-6 flex items-center justify-center border border-purple-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 5h10a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">EC2 Virtual Servers</h3>
                        <p class="text-slate-500 text-sm leading-relaxed mb-6">
                            Virtual server dengan dedicated vCPU dan alokasi RAM bertenaga. Mudah dikembangkan, dihentikan (stop), atau dihancurkan (terminate) secara instan sesuai beban operasional aplikasi Anda.
                        </p>
                    </div>
                    <div class="flex items-center text-xs text-purple-600 font-bold space-x-1">
                        <span>Hingga 4 vCPU</span>
                        <span>•</span>
                        <span>Mulai Rp 100.000 / bln</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Monitor Section -->
    <section class="py-16 bg-slate-50 relative z-10 border-t border-slate-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 text-center">
                <div class="bg-white rounded-2xl p-6 border border-slate-200/60 shadow-sm">
                    <p class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-1">99.99%</p>
                    <p class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Jaminan Uptime</p>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-slate-200/60 shadow-sm">
                    <p class="text-3xl md:text-4xl font-extrabold text-indigo-600 mb-1">&lt; 15ms</p>
                    <p class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Rata-Rata Latensi</p>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-slate-200/60 shadow-sm">
                    <p class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-1">24 / 7</p>
                    <p class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Dukungan Teknis</p>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-slate-200/60 shadow-sm">
                    <p class="text-3xl md:text-4xl font-extrabold text-purple-600 mb-1">Instan</p>
                    <p class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Deploy Server</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white text-slate-500 py-12 border-t border-slate-200 mt-auto relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <!-- Brand Profile -->
                <div class="space-y-3">
                    <div class="flex items-center space-x-2">
                        <div class="w-7 h-7 bg-gradient-to-tr from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
                            </svg>
                        </div>
                        <span class="text-slate-800 font-extrabold">CloudOS</span>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">Platform IaaS terpercaya untuk transformasi digital bisnis Anda.</p>
                </div>

                <!-- Products -->
                <div>
                    <h4 class="text-slate-700 text-xs uppercase font-bold tracking-wider mb-4">Layanan</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="#" class="hover:text-slate-850 transition">S3 Storage</a></li>
                        <li><a href="#" class="hover:text-slate-850 transition">EC2 Compute</a></li>
                    </ul>
                </div>

                <!-- Company -->
                <div>
                    <h4 class="text-slate-700 text-xs uppercase font-bold tracking-wider mb-4">Perusahaan</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="#" class="hover:text-slate-850 transition">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-slate-850 transition">Blog</a></li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h4 class="text-slate-700 text-xs uppercase font-bold tracking-wider mb-4">Legalitas</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="#" class="hover:text-slate-850 transition">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-slate-850 transition">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
            </div>

            <!-- Copyright -->
            <div class="border-t border-slate-100 pt-8 text-center text-xs text-slate-400">
                <p>&copy; 2026 CloudOS. Semua hak dilindungi. Powered by CloudOS Infrastructure.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const menuBtn = document.getElementById('mobile-menu-btn');
        const menuTray = document.getElementById('mobile-menu-tray');
        if (menuBtn && menuTray) {
            menuBtn.addEventListener('click', function() {
                menuTray.classList.toggle('hidden');
            });
        }
    </script>
</body>
</html>
