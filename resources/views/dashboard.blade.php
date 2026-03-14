<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - DevShort</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900 font-sans min-h-screen">

    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="w-64 bg-white border-r p-6 flex-shrink-0 hidden md:block">
            {{-- Logo --}}
            <a href="/" class="flex items-center gap-2 mb-8">
                <div class="w-10 h-10 gradient-bg rounded-xl flex items-center justify-center text-white shadow-lg">
                    <i class="fas fa-link text-xl"></i>
                </div>
                <span class="text-2xl font-extrabold tracking-tight text-indigo-600">DevShort</span>
            </a>

            <nav class="space-y-2">
                <a href="{{ route('dashboard') }}" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-indigo-50 text-indigo-600 font-bold">
                    <i class="fas fa-th-large"></i> Overview
                </a>
            </nav>

            <div class="mt-auto pt-10">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-500 hover:bg-red-50 font-medium">
                        <i class="fas fa-sign-out-alt"></i> Keluar
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="flex-grow p-4 md:p-10">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
                <div>
                    <h1 class="text-3xl font-bold">Overview</h1>
                    <p class="text-slate-500">Selamat datang kembali, {{ auth()->user()->name }}!</p>
                </div>
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <p class="text-slate-500 text-sm mb-1">Total Klik</p>
                    <h4 class="text-3xl font-bold">0</h4>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <p class="text-slate-500 text-sm mb-1">Link Aktif</p>
                    <h4 class="text-3xl font-bold">0</h4>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <p class="text-slate-500 text-sm mb-1">QR Scan</p>
                    <h4 class="text-3xl font-bold">0</h4>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <p class="text-slate-500 text-sm mb-1">Domain</p>
                    <h4 class="text-3xl font-bold">0</h4>
                </div>
            </div>

            {{-- Empty State --}}
            <div class="bg-white p-20 rounded-3xl border border-dashed border-slate-300 text-center">
                <i class="fas fa-link text-4xl text-slate-200 mb-4"></i>
                <p class="text-slate-400">Belum ada link. Buat link pertama Anda!</p>
            </div>
        </main>
    </div>

</body>
</html>
