<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CloudOS - Platform IaaS Terpercaya</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <!-- Navigation Header -->
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gray-800 rounded-lg"></div>
                    <span class="text-xl font-bold text-gray-900">CloudOS</span>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900 font-medium transition">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 font-medium transition">Login</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition">Register</a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button class="text-gray-600 hover:text-gray-900" id="mobile-menu-btn">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-white py-20 md:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-4">
                    Selamat Datang di CloudOS
                </h1>
                <p class="text-lg md:text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                    Platform Infrastructure as a Service (IaaS) terpercaya untuk bisnis modern. Skalabilitas, keamanan, dan performa tinggi dalam satu solusi cloud yang komprehensif.
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @guest
                        <a href="{{ route('register') }}" class="px-8 py-3 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition font-semibold">
                            Mulai Sekarang
                        </a>
                        <a href="{{ route('login') }}" class="px-8 py-3 bg-gray-200 text-gray-900 rounded-lg hover:bg-gray-300 transition font-semibold">
                            Masuk Akun
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="px-8 py-3 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition font-semibold">
                            Buka Dashboard
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 md:py-32 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 text-center mb-12">
                Layanan Unggulan Kami
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- S3 Storage Card -->
                <div class="bg-white rounded-lg p-8 border border-gray-200 hover:border-gray-400 transition">
                    <div class="w-12 h-12 bg-gray-800 rounded-lg mb-4"></div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">S3 Storage</h3>
                    <p class="text-gray-600">
                        Penyimpanan cloud yang scalable dan aman dengan redundansi data multi-region. Akses cepat dan transfer unlimited untuk semua kebutuhan Anda.
                    </p>
                </div>

                <!-- EC2 Compute Card -->
                <div class="bg-white rounded-lg p-8 border border-gray-200 hover:border-gray-400 transition">
                    <div class="w-12 h-12 bg-gray-700 rounded-lg mb-4"></div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">EC2 Compute</h3>
                    <p class="text-gray-600">
                        Instance kompute yang fleksibel dengan berbagai pilihan ukuran dan tipe. Skalabilitas otomatis dan monitoring real-time untuk performa optimal.
                    </p>
                </div>

                <!-- VPS Network Card -->
                <div class="bg-white rounded-lg p-8 border border-gray-200 hover:border-gray-400 transition">
                    <div class="w-12 h-12 bg-gray-600 rounded-lg mb-4"></div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">VPS Network</h3>
                    <p class="text-gray-600">
                        Jaringan virtual private yang dedicated dan aman. Bandwidth unlimited dengan latency rendah untuk konektivitas optimal.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 md:py-32 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                <!-- Stat 1: Customers -->
                <div>
                    <p class="text-4xl md:text-5xl font-bold text-gray-900 mb-2">5000+</p>
                    <p class="text-lg text-gray-600">Pelanggan Puas</p>
                </div>

                <!-- Stat 2: Uptime -->
                <div>
                    <p class="text-4xl md:text-5xl font-bold text-gray-900 mb-2">99.9%</p>
                    <p class="text-lg text-gray-600">Uptime Guarantee</p>
                </div>

                <!-- Stat 3: Support -->
                <div>
                    <p class="text-4xl md:text-5xl font-bold text-gray-900 mb-2">24/7</p>
                    <p class="text-lg text-gray-600">Support Team</p>
                </div>

                <!-- Stat 4: Data Centers -->
                <div>
                    <p class="text-4xl md:text-5xl font-bold text-gray-900 mb-2">50+</p>
                    <p class="text-lg text-gray-600">Data Centers</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <!-- About -->
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-gray-700 rounded-lg"></div>
                        <span class="text-white font-bold">CloudOS</span>
                    </div>
                    <p class="text-sm">Platform IaaS terpercaya untuk transformasi digital bisnis Anda.</p>
                </div>

                <!-- Products -->
                <div>
                    <h4 class="text-white font-semibold mb-4">Produk</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition">S3 Storage</a></li>
                        <li><a href="#" class="hover:text-white transition">EC2 Compute</a></li>
                        <li><a href="#" class="hover:text-white transition">VPS Network</a></li>
                    </ul>
                </div>

                <!-- Company -->
                <div>
                    <h4 class="text-white font-semibold mb-4">Perusahaan</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-white transition">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition">Karir</a></li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h4 class="text-white font-semibold mb-4">Legal</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white transition">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-white transition">Security</a></li>
                    </ul>
                </div>
            </div>

            <!-- Copyright -->
            <div class="border-t border-gray-800 pt-8 text-center text-sm">
                <p>&copy; 2024 CloudOS. Semua hak dilindungi. Powered by CloudOS Infrastructure.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const menu = document.querySelector('nav');
            menu.classList.toggle('mobile-menu-open');
        });
    </script>
</body>
</html>
