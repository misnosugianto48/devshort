@extends('layouts.app')

@section('title', 'Overview')

@section('content')
<div x-data="{ openCreateModal: {{ $errors->any() ? 'true' : 'false' }}, hasExpiration: {{ old('expires_at') ? 'true' : 'false' }}, hasPassword: {{ old('password') ? 'true' : 'false' }} }">
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
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden flex flex-col justify-between">
            <div class="absolute top-0 right-0 p-4 opacity-10 text-indigo-500"><i class="fas fa-mouse-pointer text-5xl"></i></div>
            <div>
                <p class="text-slate-500 text-sm mb-1 font-medium">Total Klik</p>
                <h4 class="text-3xl font-bold text-slate-800">{{ number_format($totalClicks) }}</h4>
                <p class="text-indigo-500 text-xs mt-2 font-bold"><i class="fas fa-chart-line mr-1"></i> 7 hari terakhir</p>
            </div>
            <div class="w-full h-12 mt-4 -mx-1 -mb-2">
                <canvas id="sparklineChart"></canvas>
            </div>
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

    <!-- Recent Activity & Links Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Recent Links -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden flex flex-col">
            <div class="p-6 border-b border-slate-50 flex justify-between items-center">
                <h3 class="font-bold text-lg text-slate-800">Link Terbaru</h3>
                <a href="{{ route('links.index') }}" class="text-indigo-600 text-sm font-bold hover:underline">Lihat Semua &rarr;</a>
            </div>
            
            <div class="overflow-x-auto flex-1">
                @if($recentLinks->count() > 0)
                    <table class="w-full text-left whitespace-nowrap">
                        <thead class="bg-slate-50 text-slate-400 text-xs uppercase font-semibold">
                            <tr>
                                <th class="px-6 py-4">Link Asli</th>
                                <th class="px-6 py-4">Short Link</th>
                                <th class="px-6 py-4">Klik</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($recentLinks as $link)
                                <tr class="hover:bg-slate-50/50 transition duration-150">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-slate-900 truncate max-w-[150px]" title="{{ $link->title ?? $link->original_url }}">
                                            {{ $link->title ?? $link->original_url }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ url($link->short_code) }}" target="_blank" class="text-indigo-600 font-bold hover:underline text-sm truncate max-w-[150px] block">
                                            {{ request()->getHost() }}/{{ $link->short_code }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-slate-700 text-sm">
                                        {{ number_format($link->clicks_count) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-12 text-center flex flex-col items-center justify-center h-full">
                        <div class="w-16 h-16 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-400 mb-4">
                            <i class="fas fa-link text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-bold text-slate-700 mb-1">Belum ada tautan</h4>
                        <p class="text-slate-500 text-sm max-w-xs mx-auto mb-4">Mulai perpendek tautan untuk melihat data.</p>
                        <button @click="openCreateModal = true" class="px-4 py-2 bg-indigo-50 text-indigo-600 font-bold text-sm rounded-xl hover:bg-indigo-100 transition">
                            Buat Tautan
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Clicks Activity Feed -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden flex flex-col">
            <div class="p-6 border-b border-slate-50 flex justify-between items-center">
                <h3 class="font-bold text-lg text-slate-800">Aktivitas Terbaru</h3>
            </div>
            <div class="p-0 flex-1 overflow-y-auto max-h-[400px]">
                @if($recentActivity->count() > 0)
                    <div class="divide-y divide-slate-50">
                        @foreach($recentActivity as $click)
                            <div class="p-4 flex items-start gap-4 hover:bg-slate-50/50 transition duration-150">
                                <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-500 flex items-center justify-center flex-shrink-0 mt-1">
                                    <i class="fas fa-mouse-pointer text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-slate-800 truncate">
                                        Seseorang mengklik <a href="{{ route('links.show', $click->link) }}" class="text-indigo-600 hover:underline">{{ $click->link->title ?? $click->link->short_code }}</a>
                                    </p>
                                    <div class="flex items-center gap-3 mt-1 text-xs text-slate-500">
                                        <span title="{{ $click->created_at->format('d M Y H:i:s') }}">{{ $click->created_at->diffForHumans() }}</span>
                                        <span>•</span>
                                        <span class="capitalize"><i class="fas fa-desktop mr-1"></i>{{ $click->device }}</span>
                                        <span>•</span>
                                        <span><i class="fas fa-globe-americas mr-1"></i>{{ $click->country === 'Unknown' ? 'Unknown' : $click->country }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center flex flex-col items-center justify-center h-full">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-400 mb-4">
                            <i class="fas fa-chart-line text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-bold text-slate-700 mb-1">Belum ada klik</h4>
                        <p class="text-slate-500 text-sm max-w-xs mx-auto">Sebarkan tautan Anda untuk mulai merekam aktivitas pengunjung.</p>
                    </div>
                @endif
            </div>
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
                    @error('original_url')
                        <p class="text-xs text-red-500 mt-2 font-semibold">{{ $message }}</p>
                    @else
                        <p class="text-xs text-slate-400 mt-2">Masukkan URL panjang yang ingin Anda perpendek.</p>
                    @enderror
                </div>

                <div>
                    <label for="title" class="block text-sm font-bold text-slate-700 mb-2">Judul Referensi (Opsional)</label>
                    <input type="text" name="title" id="title" placeholder="Promo Campaign Agustus" class="w-full p-4 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition text-slate-700 bg-slate-50 focus:bg-white" value="{{ old('title') }}">
                    @error('title')
                        <p class="text-xs text-red-500 mt-2 font-semibold">{{ $message }}</p>
                    @else
                        <p class="text-xs text-slate-400 mt-2">Membantu Anda mengidentifikasi tautan ini di dashboard.</p>
                    @enderror
                </div>
                
                <div class="border-t pt-6 space-y-4 relative">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Domain</label>
                            <select disabled class="w-full p-3 border rounded-xl bg-slate-100">
                                <option>devsh.rt</option>
                            </select>
                        </div>
                        <div>
                            <label for="custom_alias" class="block text-sm font-bold text-slate-700 mb-2">Custom Alias</label>
                            <input type="text" name="custom_alias" id="custom_alias" placeholder="my-awesome-link" class="w-full p-4 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition text-slate-700 bg-slate-50 focus:bg-white" value="{{ old('custom_alias') }}">
                            @error('custom_alias')
                                <p class="text-xs text-red-500 mt-2 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="border-t pt-4 space-y-4">
                    <label class="flex items-start gap-3 cursor-pointer group p-3 rounded-xl hover:bg-slate-50 transition border border-transparent hover:border-slate-100">
                        <div class="mt-0.5">
                            <input type="checkbox" x-model="hasExpiration" class="w-5 h-5 accent-indigo-600 rounded">
                        </div>
                        <div>
                            <span class="text-sm font-bold text-slate-800 block">Atur Batas Waktu Kadaluarsa</span>
                            <span class="text-xs text-slate-500 block mt-0.5">Tautan akan otomatis nonaktif setelah tanggal yang ditentukan.</span>
                        </div>
                    </label>

                    <div x-show="hasExpiration" x-cloak x-transition.opacity class="pl-11 pr-3 pb-3">
                        <input type="datetime-local" name="expires_at" id="expires_at" class="w-full p-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition text-slate-700 bg-slate-50 focus:bg-white" value="{{ old('expires_at') }}">
                        @error('expires_at')
                            <p class="text-xs text-red-500 mt-2 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="border-t pt-4 space-y-4">
                    <label class="flex items-start gap-3 cursor-pointer group p-3 rounded-xl hover:bg-slate-50 transition border border-transparent hover:border-slate-100">
                        <div class="mt-0.5">
                            <input type="checkbox" x-model="hasPassword" class="w-5 h-5 accent-indigo-600 rounded">
                        </div>
                        <div>
                            <span class="text-sm font-bold text-slate-800 block">Lindungi dengan Password</span>
                            <span class="text-xs text-slate-500 block mt-0.5">Pengunjung harus memasukkan password untuk melanjutkan.</span>
                        </div>
                    </label>

                    <div x-show="hasPassword" x-cloak x-transition.opacity class="pl-11 pr-3 pb-3">
                        <div class="relative">
                            <input type="password" name="password" id="password" placeholder="Masukkan password yang kuat" class="w-full p-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition text-slate-700 bg-slate-50 focus:bg-white">
                            <i class="fas fa-lock absolute right-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                        </div>
                        @error('password')
                            <p class="text-xs text-red-500 mt-2 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button type="button" @click="openCreateModal = false" class="px-6 py-3 rounded-xl font-bold text-slate-600 hover:bg-slate-100 transition">Batal</button>
                    <button type="submit" class="gradient-bg text-white px-8 py-3 rounded-xl font-bold hover:opacity-90 shadow-lg shadow-indigo-200 transition transform hover:-translate-y-0.5">Pendekkan!</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@if($sparklineData->count() > 0)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('sparklineChart').getContext('2d');
        const data = @json($sparklineData);
        
        let gradient = ctx.createLinearGradient(0, 0, 0, 48);
        gradient.addColorStop(0, 'rgba(99, 102, 241, 0.3)');
        gradient.addColorStop(1, 'rgba(99, 102, 241, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(item => item.date),
                datasets: [{
                    data: data.map(item => item.count),
                    borderColor: '#6366f1',
                    backgroundColor: gradient,
                    borderWidth: 2,
                    pointRadius: 0,
                    pointHoverRadius: 4,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        enabled: true,
                        backgroundColor: '#1e293b',
                        displayColors: false,
                        callbacks: {
                            title: () => null, // Hide title
                        }
                    }
                },
                scales: {
                    x: { display: false },
                    y: { display: false, min: 0 }
                },
                layout: { padding: 0 },
                interaction: { intersect: false, mode: 'index' },
            }
        });
    });
</script>
@endif
@endsection
