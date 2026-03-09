<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevShort - Shorten. Brand. Track.</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
                    <button onclick="showPage('dashboard')" class="bg-indigo-600 text-white px-6 py-2.5 rounded-full font-bold hover:bg-indigo-700 shadow-md transition transform hover:scale-105">Dashboard</button>
                </div>
                <div class="md:hidden">
                    <button class="text-slate-600"><i class="fas fa-bars text-2xl"></i></button>
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
                    Link Lebih <span class="text-transparent bg-clip-text gradient-bg">Pendek</span>, <br>Dampak Lebih Besar.
                </h1>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto mb-10">
                    Bukan sekadar penyingkat URL. Bangun brand Anda dengan custom domain, amankan link dengan password, dan pantau performa dengan analitik real-time.
                </p>
                
                <!-- Quick Shorten Mockup -->
                <div class="max-w-3xl mx-auto p-2 bg-white rounded-2xl shadow-2xl border border-indigo-100 flex flex-col md:flex-row gap-2">
                    <input type="text" placeholder="Tempel URL panjang di sini..." class="flex-grow px-6 py-4 rounded-xl focus:outline-none text-lg">
                    <button class="gradient-bg text-white px-8 py-4 rounded-xl font-bold text-lg hover:opacity-90 transition">Singkatkan!</button>
                </div>
                <p class="mt-4 text-sm text-slate-500 italic">Dapatkan custom alias dan QR Code gratis untuk link pertama Anda.</p>
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
                        <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mb-6">
                            <i class="fas fa-globe text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Custom Branded Domains</h3>
                        <p class="text-slate-600">Gunakan domain Anda sendiri (misal: link.brandmu.com) untuk meningkatkan kepercayaan audiens.</p>
                    </div>
                    <!-- Feature 2 -->
                    <div class="p-8 rounded-3xl border border-slate-100 bg-slate-50 card-hover">
                        <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center mb-6">
                            <i class="fas fa-chart-pie text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Advanced Analytics</h3>
                        <p class="text-slate-600">Pantau siapa yang mengklik, dari mana asalnya, dan perangkat apa yang mereka gunakan secara detail.</p>
                    </div>
                    <!-- Feature 3 -->
                    <div class="p-8 rounded-3xl border border-slate-100 bg-slate-50 card-hover">
                        <div class="w-12 h-12 bg-pink-100 text-pink-600 rounded-2xl flex items-center justify-center mb-6">
                            <i class="fas fa-qrcode text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3">QR Code Generator</h3>
                        <h3 class="text-xl font-bold mb-3">QR Code Otomatis</h3>
                        <p class="text-slate-600">Setiap link yang dibuat otomatis mendapatkan QR Code yang bisa didownload dan diatur warnanya.</p>
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
                        <div class="text-4xl font-extrabold mb-6">Rp 0<span class="text-base text-slate-400 font-normal">/bulan</span></div>
                        <ul class="space-y-4 mb-10 flex-grow text-slate-600">
                            <li><i class="fas fa-check text-green-500 mr-2"></i> 50 Link per bulan</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i> Analitik Dasar</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i> QR Code Standar</li>
                            <li class="opacity-40"><i class="fas fa-times mr-2"></i> Custom Domain</li>
                        </ul>
                        <button onclick="showPage('dashboard')" class="w-full py-3 rounded-xl border-2 border-indigo-600 text-indigo-600 font-bold hover:bg-indigo-50 transition">Mulai Sekarang</button>
                    </div>
                    <!-- Pro Plan -->
                    <div class="bg-white p-10 rounded-3xl border-4 border-indigo-500 shadow-xl flex flex-col relative scale-105 z-10">
                        <div class="absolute -top-5 left-1/2 transform -translate-x-1/2 bg-indigo-500 text-white px-4 py-1 rounded-full text-sm font-bold tracking-widest uppercase">Populer</div>
                        <h3 class="text-xl font-bold mb-2">Pro</h3>
                        <div class="text-4xl font-extrabold mb-6">Rp 99rb<span class="text-base text-slate-400 font-normal">/bulan</span></div>
                        <ul class="space-y-4 mb-10 flex-grow text-slate-600">
                            <li><i class="fas fa-check text-green-500 mr-2"></i> Unlimited Link</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i> Analitik Mendalam</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i> 3 Custom Domain</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i> Password Protection</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i> Link Expiration</li>
                        </ul>
                        <button onclick="showPage('dashboard')" class="w-full py-3 rounded-xl gradient-bg text-white font-bold hover:opacity-90 shadow-lg transition">Upgrade ke Pro</button>
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
                        <button class="w-full py-3 rounded-xl border-2 border-slate-800 text-slate-800 font-bold hover:bg-slate-50 transition">Hubungi Sales</button>
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
                    <p class="text-slate-400 max-w-sm">Solusi manajemen link terbaik untuk pengembang dan pemasar modern. Buat tautan Anda bekerja lebih cerdas.</p>
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
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-16 pt-8 border-t border-slate-800 text-center text-slate-500 text-sm">
                &copy; 2024 DevShort. Dibuat dengan cinta untuk komunitas developer.
            </div>
        </footer>
    </div>

    <!-- PAGE: DASHBOARD -->
    <div id="page-dashboard" class="page-container hidden flex flex-col md:flex-row min-h-screen pt-16">
        <!-- Sidebar -->
        <aside class="w-full md:w-64 bg-white border-r p-6 flex-shrink-0">
            <nav class="space-y-2">
                <button onclick="switchTab('overview')" class="sidebar-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-indigo-50 text-indigo-600 font-bold">
                    <i class="fas fa-th-large"></i> Overview
                </button>
                <button onclick="switchTab('my-links')" class="sidebar-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 hover:bg-slate-50 font-medium">
                    <i class="fas fa-link"></i> Link Saya
                </button>
                <button onclick="switchTab('domains')" class="sidebar-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 hover:bg-slate-50 font-medium">
                    <i class="fas fa-globe"></i> Custom Domains
                </button>
                <button onclick="switchTab('billing')" class="sidebar-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 hover:bg-slate-50 font-medium">
                    <i class="fas fa-credit-card"></i> Langganan
                </button>
                <div class="pt-10">
                    <button onclick="showPage('landing')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-500 hover:bg-red-50 font-medium">
                        <i class="fas fa-sign-out-alt"></i> Keluar
                    </button>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-grow p-4 md:p-10">
            <!-- Header Dashboard -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
                <div>
                    <h1 class="text-3xl font-bold" id="tab-title">Overview</h1>
                    <p class="text-slate-500">Selamat datang kembali, Dev!</p>
                </div>
                <button onclick="openModal('create-link')" class="gradient-bg text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:opacity-90 flex items-center gap-2">
                    <i class="fas fa-plus"></i> Buat Link Baru
                </button>
            </div>

            <!-- Tab: Overview -->
            <div id="tab-overview" class="tab-content active space-y-8">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                        <p class="text-slate-500 text-sm mb-1">Total Klik</p>
                        <h4 class="text-3xl font-bold">12,482</h4>
                        <p class="text-green-500 text-xs mt-2 font-bold"><i class="fas fa-arrow-up mr-1"></i> 12% bulan ini</p>
                    </div>
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                        <p class="text-slate-500 text-sm mb-1">Link Aktif</p>
                        <h4 class="text-3xl font-bold">48</h4>
                        <p class="text-indigo-500 text-xs mt-2 font-bold">Sisa kuota: 2</p>
                    </div>
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                        <p class="text-slate-500 text-sm mb-1">QR Scan</p>
                        <h4 class="text-3xl font-bold">3,120</h4>
                        <p class="text-slate-400 text-xs mt-2 font-bold">25% dari total klik</p>
                    </div>
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                        <p class="text-slate-500 text-sm mb-1">Domain</p>
                        <h4 class="text-3xl font-bold">1</h4>
                        <p class="text-slate-400 text-xs mt-2 font-bold">link.devshort.id</p>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-50 flex justify-between items-center">
                        <h3 class="font-bold">Link Terbaru</h3>
                        <button onclick="switchTab('my-links')" class="text-indigo-600 text-sm font-bold">Lihat Semua</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 text-slate-400 text-xs uppercase">
                                <tr>
                                    <th class="px-6 py-4">Link Asli</th>
                                    <th class="px-6 py-4">Short Link</th>
                                    <th class="px-6 py-4">Klik</th>
                                    <th class="px-6 py-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <tr>
                                    <td class="px-6 py-4 text-sm max-w-[200px] truncate text-slate-600">https://github.com/project-keren-sekali-banget</td>
                                    <td class="px-6 py-4"><span class="text-indigo-600 font-bold">devsh.rt/github-pro</span></td>
                                    <td class="px-6 py-4 font-semibold">1,240</td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            <button class="p-2 hover:bg-slate-100 rounded-lg text-slate-400" title="Copy"><i class="fas fa-copy"></i></button>
                                            <button class="p-2 hover:bg-slate-100 rounded-lg text-slate-400" title="Analytics"><i class="fas fa-chart-line"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 text-sm max-w-[200px] truncate text-slate-600">https://linkedin.com/in/john-doe-professional</td>
                                    <td class="px-6 py-4"><span class="text-indigo-600 font-bold">devsh.rt/mycv</span></td>
                                    <td class="px-6 py-4 font-semibold">892</td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            <button class="p-2 hover:bg-slate-100 rounded-lg text-slate-400"><i class="fas fa-copy"></i></button>
                                            <button class="p-2 hover:bg-slate-100 rounded-lg text-slate-400"><i class="fas fa-chart-line"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab: Billing -->
            <div id="tab-billing" class="tab-content space-y-8">
                <div class="bg-white p-8 rounded-3xl border-2 border-indigo-100 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div>
                        <span class="px-3 py-1 bg-indigo-100 text-indigo-600 rounded-full text-xs font-bold uppercase mb-2 inline-block">Paket Saat Ini</span>
                        <h2 class="text-2xl font-bold">Paket Gratis</h2>
                        <p class="text-slate-500">Anda telah menggunakan 48 dari 50 link bulan ini.</p>
                    </div>
                    <button class="gradient-bg text-white px-8 py-3 rounded-xl font-bold shadow-lg">Upgrade ke Pro</button>
                </div>
            </div>
            
            <!-- Tab Placeholder (My Links, Domains) -->
            <div id="tab-my-links" class="tab-content">
                <div class="bg-white p-20 rounded-3xl border border-dashed border-slate-300 text-center">
                    <i class="fas fa-link text-4xl text-slate-200 mb-4"></i>
                    <p class="text-slate-400">Halaman Manajemen Link Sedang Dimuat...</p>
                </div>
            </div>

            <div id="tab-domains" class="tab-content">
                <div class="bg-white p-10 rounded-3xl border border-slate-100 shadow-sm">
                    <h3 class="text-xl font-bold mb-6">Kelola Custom Domain</h3>
                    <div class="flex flex-col md:flex-row gap-4 mb-8">
                        <input type="text" placeholder="misal: link.perusahaanmu.com" class="flex-grow p-3 border rounded-xl focus:ring-2 ring-indigo-500 outline-none">
                        <button class="bg-slate-900 text-white px-6 py-3 rounded-xl font-bold">Tambahkan Domain</button>
                    </div>
                    <div class="p-4 bg-yellow-50 text-yellow-700 rounded-xl border border-yellow-100 flex gap-4">
                        <i class="fas fa-info-circle mt-1"></i>
                        <p class="text-sm">Anda perlu mengarahkan CNAME domain Anda ke <strong>cname.devshort.id</strong> di pengaturan DNS domain Anda sebelum domain aktif.</p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- MODAL: CREATE LINK -->
    <div id="modal-create-link" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('create-link')"></div>
        <div class="bg-white w-full max-w-xl rounded-3xl shadow-2xl relative z-10 overflow-hidden">
            <div class="p-6 border-b flex justify-between items-center">
                <h3 class="text-xl font-bold">Buat Link Baru</h3>
                <button onclick="closeModal('create-link')" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-8 space-y-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">URL Panjang</label>
                    <input type="text" placeholder="https://..." class="w-full p-3 border rounded-xl focus:ring-2 ring-indigo-500 outline-none">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Domain</label>
                        <select class="w-full p-3 border rounded-xl focus:ring-2 ring-indigo-500 outline-none">
                            <option>devsh.rt</option>
                            <option>link.devshort.id</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Custom Alias</label>
                        <input type="text" placeholder="my-awesome-link" class="w-full p-3 border rounded-xl focus:ring-2 ring-indigo-500 outline-none">
                    </div>
                </div>
                
                <!-- Advanced Features Accordion -->
                <div class="border-t pt-6 space-y-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" class="w-5 h-5 accent-indigo-600">
                        <span class="text-sm font-medium">Lindungi dengan Password</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" class="w-5 h-5 accent-indigo-600">
                        <span class="text-sm font-medium">Atur Tanggal Kadaluarsa</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" checked class="w-5 h-5 accent-indigo-600">
                        <span class="text-sm font-medium">Generate QR Code</span>
                    </label>
                </div>

                <button class="w-full gradient-bg text-white py-4 rounded-2xl font-bold text-lg shadow-xl shadow-indigo-200 hover:opacity-95 transition">Buat Tautan</button>
            </div>
        </div>
    </div>

    <script>
        // NAVIGATION LOGIC
        function showPage(pageId) {
            // Hide all pages
            document.querySelectorAll('.page-container').forEach(p => p.classList.add('hidden'));
            // Show requested page
            const targetPage = document.getElementById('page-' + pageId);
            targetPage.classList.remove('hidden');
            
            // Auto scroll to top
            window.scrollTo(0, 0);

            // Conditional Nav Styles
            if(pageId === 'dashboard') {
                document.querySelector('nav').classList.add('hidden');
            } else {
                document.querySelector('nav').classList.remove('hidden');
            }
        }

        // DASHBOARD TABS LOGIC
        function switchTab(tabId) {
            // Update Tab Content
            document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
            document.getElementById('tab-' + tabId).classList.add('active');

            // Update Sidebar Buttons
            document.querySelectorAll('.sidebar-btn').forEach(btn => {
                btn.classList.remove('bg-indigo-50', 'text-indigo-600', 'font-bold');
                btn.classList.add('text-slate-500', 'font-medium');
                btn.classList.remove('hover:bg-slate-50');
            });

            const activeBtn = event.currentTarget;
            activeBtn.classList.add('bg-indigo-50', 'text-indigo-600', 'font-bold');
            activeBtn.classList.remove('text-slate-500', 'font-medium');

            // Update Title
            const titles = {
                'overview': 'Overview',
                'my-links': 'Daftar Link Saya',
                'domains': 'Custom Domains',
                'billing': 'Tagihan & Paket'
            };
            document.getElementById('tab-title').innerText = titles[tabId];
        }

        // MODAL LOGIC
        function openModal(id) {
            document.getElementById('modal-' + id).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(id) {
            document.getElementById('modal-' + id).classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Initialize view
        window.onload = () => {
            // You can force a specific page for testing
            // showPage('dashboard');
        };
    </script>
</body>
</html>