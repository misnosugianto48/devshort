@extends('layouts.app')

@section('title', 'Overview')

@section('content')
<div x-data="{ openCreateModal: false }">
    <!-- Header Dashboard -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Overview</h1>
            <p class="text-slate-500">Selamat datang kembali, {{ auth()->user()->name }}!</p>
        </div>
        <button @click="openCreateModal = true" class="gradient-bg text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:opacity-90 flex items-center gap-2 transition transform hover:-translate-y-1">
            <i class="fas fa-plus"></i> Buat Link Baru
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10 text-indigo-500"><i class="fas fa-mouse-pointer text-5xl"></i></div>
            <p class="text-slate-500 text-sm mb-1 font-medium">Total Klik</p>
            <h4 class="text-3xl font-bold text-slate-800">{{ number_format($totalClicks) }}</h4>
            <p class="text-indigo-500 text-xs mt-2 font-bold"><i class="fas fa-chart-line mr-1"></i> Dari semua link</p>
        </div>
        
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10 text-purple-500"><i class="fas fa-link text-5xl"></i></div>
            <p class="text-slate-500 text-sm mb-1 font-medium">Link Dibuat</p>
            <h4 class="text-3xl font-bold text-slate-800">{{ number_format($totalLinks) }}</h4>
            <p class="text-purple-500 text-xs mt-2 font-bold">Total tautan aktif</p>
        </div>
        
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden opacity-50" title="Coming Soon in Phase 2">
            <div class="absolute top-0 right-0 p-4 opacity-10 text-slate-500"><i class="fas fa-qrcode text-5xl"></i></div>
            <p class="text-slate-500 text-sm mb-1 font-medium">QR Scan</p>
            <h4 class="text-3xl font-bold text-slate-800">--</h4>
            <p class="text-slate-400 text-xs mt-2 font-bold"><i class="fas fa-tools mr-1"></i> Segera Hadir</p>
        </div>
        
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden opacity-50" title="Coming Soon in Phase 6">
            <div class="absolute top-0 right-0 p-4 opacity-10 text-slate-500"><i class="fas fa-globe text-5xl"></i></div>
            <p class="text-slate-500 text-sm mb-1 font-medium">Domain</p>
            <h4 class="text-3xl font-bold text-slate-800">--</h4>
            <p class="text-slate-400 text-xs mt-2 font-bold"><i class="fas fa-tools mr-1"></i> Segera Hadir</p>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-50 flex justify-between items-center">
            <h3 class="font-bold text-lg text-slate-800">Link Terbaru</h3>
            <a href="{{ route('links.index') }}" class="text-indigo-600 text-sm font-bold hover:underline">Lihat Semua &rarr;</a>
        </div>
        
        <div class="overflow-x-auto">
            @if($recentLinks->count() > 0)
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-slate-50 text-slate-400 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-4">Link Asli</th>
                            <th class="px-6 py-4">Short Link</th>
                            <th class="px-6 py-4">Klik</th>
                            <th class="px-6 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($recentLinks as $link)
                            <tr class="hover:bg-slate-50/50 transition duration-150">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-slate-900 truncate max-w-xs" title="{{ $link->title ?? $link->original_url }}">
                                        {{ $link->title ?? $link->original_url }}
                                    </div>
                                    <div class="text-xs text-slate-500 truncate max-w-xs">
                                        {{ $link->original_url }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ url($link->short_code) }}" target="_blank" class="text-indigo-600 font-bold hover:underline flex items-center gap-1 group">
                                        {{ request()->getHost() }}/{{ $link->short_code }}
                                        <i class="fas fa-external-link-alt text-xs opacity-0 group-hover:opacity-100 transition"></i>
                                    </a>
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-700">
                                    {{ number_format($link->clicks_count) }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2" x-data="{ copied: false }">
                                        <button @click="navigator.clipboard.writeText('{{ url($link->short_code) }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                                                class="p-2 bg-slate-100 hover:bg-indigo-100 hover:text-indigo-600 rounded-lg text-slate-500 transition tooltip" 
                                                :title="copied ? 'Disalin!' : 'Salin URL'">
                                            <i class="fas fa-copy" x-show="!copied"></i>
                                            <i class="fas fa-check text-green-500" x-show="copied" x-cloak></i>
                                        </button>
                                        <a href="{{ route('links.show', $link) }}" class="p-2 bg-slate-100 hover:bg-purple-100 hover:text-purple-600 rounded-lg text-slate-500 transition" title="Lihat Analitik">
                                            <i class="fas fa-chart-line"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-12 text-center flex flex-col items-center justify-center">
                    <div class="w-16 h-16 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-400 mb-4">
                        <i class="fas fa-link text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-bold text-slate-700 mb-1">Belum ada tautan</h4>
                    <p class="text-slate-500 max-w-sm mx-auto mb-6">Mulai perpendek tautan panjang Anda untuk kemudahan berbagi dan pantau statistiknya.</p>
                    <button @click="openCreateModal = true" class="px-6 py-2.5 bg-indigo-50 text-indigo-600 font-bold rounded-xl hover:bg-indigo-100 transition">
                        Buat Tautan Pertama
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- MODAL: CREATE LINK (Alpine.js controlled) -->
    <div x-show="openCreateModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div x-show="openCreateModal" x-transition.opacity class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openCreateModal = false"></div>
        
        <!-- Modal Panel -->
        <div x-show="openCreateModal" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="bg-white w-full max-w-xl rounded-3xl shadow-2xl relative z-10 overflow-hidden">
            
            <div class="p-6 border-b flex justify-between items-center bg-slate-50/50">
                <h3 class="text-xl font-bold text-slate-800">Buat Tautan Baru</h3>
                <button @click="openCreateModal = false" class="text-slate-400 hover:text-slate-600 p-2 rounded-full hover:bg-slate-100 transition"><i class="fas fa-times text-xl"></i></button>
            </div>
            
            <form action="{{ route('links.store') }}" method="POST" class="p-8 space-y-6">
                @csrf
                <div>
                    <label for="original_url" class="block text-sm font-bold text-slate-700 mb-2">URL Tujuan <span class="text-red-500">*</span></label>
                    <input type="url" name="original_url" id="original_url" required placeholder="https://example.com/very/long/path/to/my/awesome/article" class="w-full p-4 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition text-slate-700 bg-slate-50 focus:bg-white" value="{{ old('original_url') }}">
                    <p class="text-xs text-slate-400 mt-2">Masukkan URL panjang yang ingin Anda perpendek.</p>
                </div>

                <div>
                    <label for="title" class="block text-sm font-bold text-slate-700 mb-2">Judul Referensi (Opsional)</label>
                    <input type="text" name="title" id="title" placeholder="Promo Campaign Agustus" class="w-full p-4 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition text-slate-700 bg-slate-50 focus:bg-white" value="{{ old('title') }}">
                    <p class="text-xs text-slate-400 mt-2">Membantu Anda mengidentifikasi tautan ini di dashboard.</p>
                </div>
                
                <!-- Future Phase Placeholders -->
                <div class="border-t pt-6 space-y-4 opacity-50 relative">
                    <div class="absolute inset-0 bg-white/50 backdrop-blur-[1px] z-10 flex items-center justify-center">
                        <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-xs font-bold shadow-sm">Fitur Premium (Segera Hadir)</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4 pointer-events-none">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Domain</label>
                            <select disabled class="w-full p-3 border rounded-xl bg-slate-100">
                                <option>devsh.rt</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Custom Alias</label>
                            <input type="text" disabled placeholder="my-awesome-link" class="w-full p-3 border rounded-xl bg-slate-100">
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3">
                    <button type="button" @click="openCreateModal = false" class="px-6 py-3 rounded-xl font-bold text-slate-600 hover:bg-slate-100 transition">Batal</button>
                    <button type="submit" class="gradient-bg text-white px-8 py-3 rounded-xl font-bold hover:opacity-90 shadow-lg shadow-indigo-200 transition transform hover:-translate-y-0.5">Pendekkan!</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
