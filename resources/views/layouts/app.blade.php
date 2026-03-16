<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'DevShort') }} - @yield('title', 'Dashboard')</title>

    <!-- Tailwind CSS (via CDN for MVP per welcome.blade.php) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased">
    <div class="min-h-screen flex flex-col md:flex-row pt-16" x-data="{ sidebarOpen: false }">
        
        <!-- Mobile Header nav for dashboard -->
        <nav class="fixed top-0 w-full z-50 bg-white border-b md:hidden flex justify-between h-16 items-center px-4">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 gradient-bg rounded-lg flex items-center justify-center text-white shadow-sm">
                    <i class="fas fa-link text-sm"></i>
                </div>
                <span class="text-xl font-extrabold tracking-tight text-indigo-600">DevShort</span>
            </a>
            <button @click="sidebarOpen = !sidebarOpen" class="text-slate-600 focus:outline-none">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </nav>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed md:static inset-y-0 left-0 z-40 w-64 bg-white border-r p-6 transform transition-transform duration-300 ease-in-out md:translate-x-0 flex-shrink-0 mt-16 md:mt-0 overflow-y-auto">
            
            <!-- Desktop Logo -->
            <a href="{{ route('home') }}" class="hidden md:flex items-center gap-2 mb-10 cursor-pointer">
                <div class="w-10 h-10 gradient-bg rounded-xl flex items-center justify-center text-white shadow-lg">
                    <i class="fas fa-link text-xl"></i>
                </div>
                <span class="text-2xl font-extrabold tracking-tight text-indigo-600">DevShort</span>
            </a>

            <nav class="space-y-2">
                <a href="{{ route('dashboard') }}" class="sidebar-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600 font-bold' : 'text-slate-500 hover:bg-slate-50 font-medium' }}">
                    <i class="fas fa-th-large w-5"></i> Overview
                </a>
                <a href="{{ route('links.index') }}" class="sidebar-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('links.*') ? 'bg-indigo-50 text-indigo-600 font-bold' : 'text-slate-500 hover:bg-slate-50 font-medium' }}">
                    <i class="fas fa-link w-5"></i> Link Saya
                </a>
                <a href="#" class="sidebar-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 hover:bg-slate-50 font-medium cursor-not-allowed opacity-50" title="Coming in Phase 6">
                    <i class="fas fa-globe w-5"></i> Custom Domains
                </a>
                <a href="#" class="sidebar-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 hover:bg-slate-50 font-medium cursor-not-allowed opacity-50" title="Coming in Phase 4">
                    <i class="fas fa-credit-card w-5"></i> Langganan
                </a>
                
                <div class="pt-10">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-500 hover:bg-red-50 font-medium">
                            <i class="fas fa-sign-out-alt w-5"></i> Keluar
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-grow p-4 md:p-10 relative mt-16 md:mt-0 w-full md:w-auto overflow-hidden">
            <!-- Toast Notifications -->
            @if (session('status'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="absolute top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-3 z-50" x-transition>
                    <i class="fas fa-check-circle"></i>
                    <span class="font-medium">{{ session('status') }}</span>
                    <button @click="show = false"><i class="fas fa-times"></i></button>
                </div>
            @endif
            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="absolute top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-3 z-50" x-transition>
                    <i class="fas fa-exclamation-circle"></i>
                    <span class="font-medium">{{ session('error') }}</span>
                    <button @click="show = false"><i class="fas fa-times"></i></button>
                </div>
            @endif
            @if ($errors->any())
                <div x-data="{ show: true }" x-show="show" class="absolute top-4 right-4 bg-red-50 text-red-600 px-6 py-4 rounded-xl shadow-lg border border-red-100 z-50" x-transition>
                    <div class="flex justify-between items-start mb-2">
                        <div class="font-bold flex items-center gap-2"><i class="fas fa-exclamation-triangle"></i> Terjadi Kesalahan</div>
                        <button @click="show = false" class="text-red-400 hover:text-red-600"><i class="fas fa-times"></i></button>
                    </div>
                    <ul class="list-disc pl-5 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @yield('scripts')
</body>
</html>
