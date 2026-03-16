@extends('layouts.app')

@section('title', 'Analitik Tautan')

@section('content')
<div class="mb-6">
    <a href="{{ route('links.index') }}" class="text-slate-500 hover:text-indigo-600 font-medium text-sm flex items-center gap-2 mb-4 transition">
        <i class="fas fa-arrow-left"></i> Kembali ke daftar
    </a>
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 mb-2">{{ $link->title ?? 'Tautan Tanpa Judul' }}</h1>
            <a href="{{ $link->original_url }}" target="_blank" class="text-slate-500 hover:text-indigo-600 flex items-center gap-2 text-sm max-w-xl truncate">
                <i class="fas fa-external-link-square-alt"></i> {{ $link->original_url }}
            </a>
        </div>
        
        <div class="flex items-center gap-4 bg-white px-5 py-3 rounded-2xl border border-slate-200 shadow-sm" x-data="{ copied: false }">
            <a href="{{ url($link->short_code) }}" target="_blank" class="text-indigo-600 font-bold text-lg hover:underline">
                {{ request()->getHost() }}/{{ $link->short_code }}
            </a>
            <div class="w-px h-6 bg-slate-200"></div>
            <button @click="navigator.clipboard.writeText('{{ url($link->short_code) }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                    class="text-slate-400 hover:text-indigo-600 transition flex items-center gap-2 font-medium text-sm">
                <i class="fas fa-copy" x-show="!copied"></i>
                <i class="fas fa-check text-green-500" x-show="copied" x-cloak></i>
                <span x-show="!copied">Salin</span>
                <span x-show="copied" class="text-green-500" x-cloak>Tersalin!</span>
            </button>
        </div>
    </div>
</div>

<!-- Stats Summary -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm text-center">
        <div class="w-12 h-12 bg-indigo-50 text-indigo-500 rounded-full flex items-center justify-center mx-auto mb-4 text-xl">
            <i class="fas fa-mouse-pointer"></i>
        </div>
        <p class="text-slate-500 text-sm mb-1 font-medium">Total Klik</p>
        <h4 class="text-3xl font-bold text-slate-800">{{ number_format($link->clicks_count) }}</h4>
    </div>
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm text-center">
        <div class="w-12 h-12 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4 text-xl">
            <i class="fas fa-calendar-alt"></i>
        </div>
        <p class="text-slate-500 text-sm mb-1 font-medium">Dibuat Pada</p>
        <h4 class="text-xl font-bold text-slate-800 mt-2">{{ $link->created_at->format('d M Y') }}</h4>
    </div>
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm text-center flex flex-col items-center justify-center">
        <div class="mb-3">
            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(80)->color(79, 70, 229)->generate(url($link->short_code)) !!}
        </div>
        <p class="text-slate-500 text-sm font-medium mb-2">QR Code Tautan</p>
        <a href="data:image/svg+xml;base64,{{ base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::size(300)->color(79, 70, 229)->generate(url($link->short_code))) }}" download="qr-{{ $link->short_code }}.svg" class="text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-lg hover:bg-indigo-100 transition inline-flex items-center gap-1">
            <i class="fas fa-download"></i> Download SVG
        </a>
    </div>
</div>

<!-- Chart Section -->
<div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm">
    <h3 class="font-bold text-lg text-slate-800 mb-6"><i class="fas fa-chart-area text-indigo-500 mr-2"></i> Timeline Klik (30 Hari Terakhir)</h3>
    
    @if($clickData->count() > 0)
        <div class="w-full h-[300px]">
            <canvas id="clicksChart"></canvas>
        </div>
    @else
        <div class="h-[200px] flex items-center justify-center flex-col text-slate-400 border-2 border-dashed border-slate-100 rounded-2xl">
            <i class="fas fa-chart-line text-3xl mb-3 opacity-50"></i>
            <p>Belum ada data klik yang cukup untuk memuat grafik.</p>
        </div>
    @endif
</div>
@endsection

@section('scripts')
@if($clickData->count() > 0)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('clicksChart').getContext('2d');
        
        // Gradient fill
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(99, 102, 241, 0.5)'); // Indigo-500
        gradient.addColorStop(1, 'rgba(99, 102, 241, 0.0)');
        
        const data = @json($clickData);
        
        const labels = data.map(item => {
            const date = new Date(item.date);
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
        });
        
        const counts = data.map(item => item.count);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Klik',
                    data: counts,
                    borderColor: '#6366f1',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#6366f1',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4 // Smooth curves
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b', // slate-800
                        padding: 12,
                        titleFont: { size: 13, family: "'Plus Jakarta Sans', sans-serif" },
                        bodyFont: { size: 14, weight: 'bold', family: "'Plus Jakarta Sans', sans-serif" },
                        displayColors: false,
                        cornerRadius: 8,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            font: { family: "'Plus Jakarta Sans', sans-serif" }
                        },
                        grid: {
                            color: '#f1f5f9', // slate-100
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            font: { family: "'Plus Jakarta Sans', sans-serif" },
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
            }
        });
    });
</script>
@endif
@endsection
