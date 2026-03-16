<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevShort - Shorten. Brand. Track.</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            scroll-behavior: smooth;
        }

        .glass-morphism {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .gradient-bg {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        }

        .card-hover:hover {
            transform: translateY(-5px);
            transition: all 0.3s ease;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900">

    <!-- NAVIGATION -->
    <nav class="fixed w-full z-50 glass-morphism border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2 cursor-pointer" onclick="showPage('landing')">
                    <div class="w-10 h-10 gradient-bg rounded-xl flex items-center justify-center text-white shadow-lg">
                        <i class="fas fa-link text-xl"></i>
                    </div>
                    <span class="text-2xl font-extrabold tracking-tight text-indigo-600">DevShort</span>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="font-medium hover:text-indigo-600 transition">Fitur</a>
                    <a href="#pricing" class="font-medium hover:text-indigo-600 transition">Harga</a>

                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="bg-indigo-600 text-white px-6 py-2.5 rounded-full font-bold hover:bg-indigo-700 shadow-md transition transform hover:scale-105">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}"
                            class="font-medium text-slate-600 hover:text-indigo-600 transition">Masuk</a>
                        <a href="{{ route('register') }}"
                            class="bg-indigo-600 text-white px-6 py-2.5 rounded-full font-bold hover:bg-indigo-700 shadow-md transition transform hover:scale-105">Daftar
                            Gratis</a>
                    @endauth
                </div>
                <!-- Mobile Navigation Toggle (Placeholder for MVP) -->
                <div class="md:hidden flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-indigo-600 font-bold">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-indigo-600 font-bold">Masuk</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- PAGE: LANDING -->
    <div id="page-landing" class="page-container pt-16">
        <!-- Hero Section -->
        <header class="relative overflow-hidden pt-20 pb-32">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-5xl md:text-7xl font-extrabold mb-6 leading-tight">
                    Link Lebih <span class="text-transparent bg-clip-text gradient-bg">Pendek</span>, <br>Dampak Lebih
                    Besar.
                </h1>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto mb-10">
                    Bukan sekadar penyingkat URL. Bangun brand Anda dengan custom domain, amankan link dengan password,
                    dan pantau performa dengan analitik real-time.
                </p>

                <!-- Quick Shorten Mockup -->
                <div
                    class="max-w-3xl mx-auto p-2 bg-white rounded-2xl shadow-2xl border border-indigo-100 flex flex-col md:flex-row gap-2">
                    <input type="text" placeholder="Tempel URL panjang di sini..."
                        class="flex-grow px-6 py-4 rounded-xl focus:outline-none text-lg">
                    <button
                        class="gradient-bg text-white px-8 py-4 rounded-xl font-bold text-lg hover:opacity-90 transition">Singkatkan!</button>
                </div>
                <p class="mt-4 text-sm text-slate-500 italic">Dapatkan custom alias dan QR Code gratis untuk link
                    pertama Anda.</p>
            </div>
        </header>

        <!-- Features Section -->
        <section id="features" class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold mb-4">Kenapa Memilih DevShort?</h2>
                    <p class="text-slate-500">Fitur lengkap untuk kebutuhan personal hingga enterprise.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="p-8 rounded-3xl border border-slate-100 bg-slate-50 card-hover">
                        <div
                            class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mb-6">
                            <i class="fas fa-globe text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Custom Branded Domains</h3>
                        <p class="text-slate-600">Gunakan domain Anda sendiri (misal: link.brandmu.com) untuk
                            meningkatkan kepercayaan audiens.</p>
                    </div>
                    <!-- Feature 2 -->
                    <div class="p-8 rounded-3xl border border-slate-100 bg-slate-50 card-hover">
                        <div
                            class="w-12 h-12 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center mb-6">
                            <i class="fas fa-chart-pie text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Advanced Analytics</h3>
                        <p class="text-slate-600">Pantau siapa yang mengklik, dari mana asalnya, dan perangkat apa yang
                            mereka gunakan secara detail.</p>
                    </div>
                    <!-- Feature 3 -->
                    <div class="p-8 rounded-3xl border border-slate-100 bg-slate-50 card-hover">
                        <div
                            class="w-12 h-12 bg-pink-100 text-pink-600 rounded-2xl flex items-center justify-center mb-6">
                            <i class="fas fa-qrcode text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3">QR Code Generator</h3>
                        <h3 class="text-xl font-bold mb-3">QR Code Otomatis</h3>
                        <p class="text-slate-600">Setiap link yang dibuat otomatis mendapatkan QR Code yang bisa
                            didownload dan diatur warnanya.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pricing Section -->
        <section id="pricing" class="py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold mb-4">Pilih Paket Yang Sesuai</h2>
                    <p class="text-slate-500">Mulai gratis, upgrade kapan saja saat bisnis Anda berkembang.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Free Plan -->
                    <div class="bg-white p-10 rounded-3xl border border-slate-200 shadow-sm flex flex-col">
                        <h3 class="text-xl font-bold mb-2">Gratis</h3>
                        <div class="text-4xl font-extrabold mb-6">Rp 0<span
                                class="text-base text-slate-400 font-normal">/bulan</span></div>
                        <ul class="space-y-4 mb-10 flex-grow text-slate-600">
                            <li><i class="fas fa-check text-green-500 mr-2"></i> 50 Link per bulan</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i> Analitik Dasar</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i> QR Code Standar</li>
                            <li class="opacity-40"><i class="fas fa-times mr-2"></i> Custom Domain</li>
                        </ul>
                        @auth
                            <a href="{{ route('dashboard') }}"
                                class="w-full py-3 text-center rounded-xl border-2 border-indigo-600 text-indigo-600 font-bold hover:bg-indigo-50 transition block">Buka
                                Dashboard</a>
                        @else
                            <a href="{{ route('register') }}"
                                class="w-full py-3 text-center rounded-xl border-2 border-indigo-600 text-indigo-600 font-bold hover:bg-indigo-50 transition block">Mulai
                                Sekarang</a>
                        @endauth
                    </div>
                    <!-- Pro Plan -->
                    <div
                        class="bg-white p-10 rounded-3xl border-4 border-indigo-500 shadow-xl flex flex-col relative scale-105 z-10">
                        <div
                            class="absolute -top-5 left-1/2 transform -translate-x-1/2 bg-indigo-500 text-white px-4 py-1 rounded-full text-sm font-bold tracking-widest uppercase">
                            Populer</div>
                        <h3 class="text-xl font-bold mb-2">Pro</h3>
                        <div class="text-4xl font-extrabold mb-6">Rp 99rb<span
                                class="text-base text-slate-400 font-normal">/bulan</span></div>
                        <ul class="space-y-4 mb-10 flex-grow text-slate-600">
                            <li><i class="fas fa-check text-green-500 mr-2"></i> Unlimited Link</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i> Analitik Mendalam</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i> 3 Custom Domain</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i> Password Protection</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i> Link Expiration</li>
                        </ul>
                        @auth
                            <a href="{{ route('dashboard') }}"
                                class="w-full py-3 text-center rounded-xl gradient-bg text-white font-bold hover:opacity-90 shadow-lg transition block">Mulai
                                Pro</a>
                        @else
                            <a href="{{ route('register') }}"
                                class="w-full py-3 text-center rounded-xl gradient-bg text-white font-bold hover:opacity-90 shadow-lg transition block">Upgrade
                                ke Pro</a>
                        @endauth
                    </div>
                    <!-- Enterprise Plan -->
                    <div class="bg-white p-10 rounded-3xl border border-slate-200 shadow-sm flex flex-col">
                        <h3 class="text-xl font-bold mb-2">Enterprise</h3>
                        <div class="text-4xl font-extrabold mb-6">Kontak Kami</div>
                        <ul class="space-y-4 mb-10 flex-grow text-slate-600">
                            <li><i class="fas fa-check text-green-500 mr-2"></i> Semua fitur Pro</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i> Custom Domain Unlimited</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i> Dedicated Support</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i> API Access Full</li>
                        </ul>
                        <a href="mailto:support@devshort.id"
                            class="w-full py-3 text-center rounded-xl border-2 border-slate-800 text-slate-800 font-bold hover:bg-slate-50 transition block">Hubungi
                            Sales</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-slate-900 text-white py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-12">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-indigo-600">
                            <i class="fas fa-link text-sm"></i>
                        </div>
                        <span class="text-xl font-bold">DevShort</span>
                    </div>
                    <p class="text-slate-400 max-w-sm">Solusi manajemen link terbaik untuk pengembang dan pemasar
                        modern. Buat tautan Anda bekerja lebih cerdas.</p>
                </div>
                <div>
                    <h4 class="font-bold mb-6">Produk</h4>
                    <ul class="space-y-4 text-slate-400">
                        <li><a href="#" class="hover:text-white">Fitur</a></li>
                        <li><a href="#" class="hover:text-white">API</a></li>
                        <li><a href="#" class="hover:text-white">Custom Domain</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-6">Bantuan</h4>
                    <ul class="space-y-4 text-slate-400">
                        <li><a href="#" class="hover:text-white">Dokumentasi</a></li>
                        <li><a href="#" class="hover:text-white">Status Server</a></li>
                        <li><a href="#" class="hover:text-white">Kontak</a></li>
                    </ul>
                </div>
            </div>
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-16 pt-8 border-t border-slate-800 text-center text-slate-500 text-sm">
                &copy; {{ date('Y') }} DevShort. Dibuat dengan cinta untuk komunitas developer.
            </div>
        </footer>
    </div>

    <script>
        // NAVIGATION LOGIC
        function showPage(pageId) {
            // Simplified for just scrolling landing page sections
            if (pageId === 'landing') {
                window.scrollTo(0, 0);
            }
        }
    </script>
</body>

</html>