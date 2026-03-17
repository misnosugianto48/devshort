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

<!-- Analytics Filters -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
    <h3 class="font-bold text-xl text-slate-800">Analitik Tautan</h3>
    
    <div class="flex flex-wrap items-center gap-3">
        <div class="flex bg-white rounded-lg border border-slate-200 p-1 shadow-sm text-sm">
            <a href="{{ request()->fullUrlWithQuery(['period' => '7d']) }}" class="px-4 py-1.5 rounded-md {{ $period === '7d' ? 'bg-indigo-50 text-indigo-600 font-bold' : 'text-slate-500 hover:text-slate-700' }} transition">7 Hari</a>
            <a href="{{ request()->fullUrlWithQuery(['period' => '30d']) }}" class="px-4 py-1.5 rounded-md {{ $period === '30d' ? 'bg-indigo-50 text-indigo-600 font-bold' : 'text-slate-500 hover:text-slate-700' }} transition">30 Hari</a>
            <a href="{{ request()->fullUrlWithQuery(['period' => 'all']) }}" class="px-4 py-1.5 rounded-md {{ $period === 'all' ? 'bg-indigo-50 text-indigo-600 font-bold' : 'text-slate-500 hover:text-slate-700' }} transition">Semua Waktu</a>
        </div>
        
        <a href="{{ route('links.export', $link) }}" class="bg-white hover:bg-slate-50 text-slate-700 font-medium text-sm px-4 py-2.5 rounded-lg border border-slate-200 shadow-sm flex items-center gap-2 transition">
            <i class="fas fa-file-download text-green-600"></i> Export CSV
        </a>
    </div>
</div>

@if($clickData->count() > 0)
    <!-- Main Timeline Chart -->
    <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm mb-6">
        <h3 class="font-bold text-lg text-slate-800 mb-6"><i class="fas fa-chart-area text-indigo-500 mr-2"></i> Timeline Klik</h3>
        <div class="w-full h-[300px]">
            <canvas id="clicksChart"></canvas>
        </div>
    </div>

    <!-- 3-Column Grid for Devices, Browser, OS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Devices (Doughnut Chart) -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <h3 class="font-bold text-lg text-slate-800 mb-4"><i class="fas fa-desktop text-indigo-500 mr-2"></i> Perangkat</h3>
            <div class="w-full h-[200px] flex justify-center">
                <canvas id="deviceChart"></canvas>
            </div>
            <div class="mt-4 space-y-2">
                @foreach($deviceData as $device)
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-600 capitalize">{{ $device->device }}</span>
                        <span class="font-bold text-slate-800">{{ number_format($device->count) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Browsers (Bar/List) -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <h3 class="font-bold text-lg text-slate-800 mb-4"><i class="fas fa-compass text-indigo-500 mr-2"></i> Browser Utama</h3>
            <div class="space-y-4 mt-2">
                @php $maxBrowser = $browserData->max('count') ?: 1; @endphp
                @foreach($browserData as $browser)
                    <div>
                        <div class="flex justify-between items-center text-sm mb-1">
                            <span class="text-slate-600 font-medium">{{ $browser->browser }}</span>
                            <span class="font-bold text-slate-800">{{ number_format($browser->count) }}</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2">
                            <div class="bg-indigo-500 h-2 rounded-full" style="width: {{ ($browser->count / $maxBrowser) * 100 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- OS (Bar/List) -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <h3 class="font-bold text-lg text-slate-800 mb-4"><i class="fab fa-windows text-indigo-500 mr-2"></i> Sistem Operasi</h3>
            <div class="space-y-4 mt-2">
                @php $maxOs = $osData->max('count') ?: 1; @endphp
                @foreach($osData as $os)
                    <div>
                        <div class="flex justify-between items-center text-sm mb-1">
                            <span class="text-slate-600 font-medium">{{ $os->os }}</span>
                            <span class="font-bold text-slate-800">{{ number_format($os->count) }}</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2">
                            <div class="bg-indigo-500 h-2 rounded-full" style="width: {{ ($os->count / $maxOs) * 100 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- 2-Column Grid for Country & Referers -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Countries List -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <h3 class="font-bold text-lg text-slate-800 mb-4"><i class="fas fa-globe-americas text-indigo-500 mr-2"></i> Lokasi (Negara)</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-600">
                    <thead class="text-xs text-slate-500 uppercase bg-slate-50 rounded-t-lg">
                        <tr>
                            <th scope="col" class="px-4 py-3 rounded-tl-lg">Negara</th>
                            <th scope="col" class="px-4 py-3 text-right rounded-tr-lg">Klik</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($countryData as $country)
                            <tr class="border-b border-slate-50 last:border-0">
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $country->country === 'Unknown' ? 'Tidak Diketahui' : $country->country }}</td>
                                <td class="px-4 py-3 text-right font-bold">{{ number_format($country->count) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Referers List -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <h3 class="font-bold text-lg text-slate-800 mb-4"><i class="fas fa-link text-indigo-500 mr-2"></i> Sumber Trafik (Referer)</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-600">
                    <thead class="text-xs text-slate-500 uppercase bg-slate-50 rounded-t-lg">
                        <tr>
                            <th scope="col" class="px-4 py-3 rounded-tl-lg">Domain</th>
                            <th scope="col" class="px-4 py-3 text-right rounded-tr-lg">Klik</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($refererData as $referer)
                            <tr class="border-b border-slate-50 last:border-0">
                                <td class="px-4 py-3 font-medium text-slate-800 truncate max-w-[200px]">
                                    {{ $referer->referer ? $referer->referer : 'Direct / Unknown' }}
                                </td>
                                <td class="px-4 py-3 text-right font-bold">{{ number_format($referer->count) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@else
    <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm text-center py-16">
        <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4 text-4xl">
            <i class="fas fa-chart-bar"></i>
        </div>
        <h3 class="text-xl font-bold text-slate-700 mb-2">Belum Ada Data</h3>
        <p class="text-slate-500 max-w-md mx-auto">Klik pada tautan Anda belum tercatat untuk periode ini. Bagikan tautan Anda untuk mulai melihat analitik.</p>
    </div>
@endif
@endsection

@section('scripts')
@if($clickData->count() > 0)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Timeline Chart
        const ctxClicks = document.getElementById('clicksChart').getContext('2d');
        let gradientClicks = ctxClicks.createLinearGradient(0, 0, 0, 400);
        gradientClicks.addColorStop(0, 'rgba(99, 102, 241, 0.5)'); // Indigo-500
        gradientClicks.addColorStop(1, 'rgba(99, 102, 241, 0.0)');
        
        const clickData = @json($clickData);
        new Chart(ctxClicks, {
            type: 'line',
            data: {
                labels: clickData.map(item => new Date(item.date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' })),
                datasets: [{
                    label: 'Jumlah Klik',
                    data: clickData.map(item => item.count),
                    borderColor: '#6366f1',
                    backgroundColor: gradientClicks,
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#6366f1',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
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
                        backgroundColor: '#1e293b',
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
                        ticks: { precision: 0, font: { family: "'Plus Jakarta Sans', sans-serif" } },
                        grid: { color: '#f1f5f9', drawBorder: false }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { font: { family: "'Plus Jakarta Sans', sans-serif" }, maxRotation: 45, minRotation: 45 }
                    }
                },
                interaction: { intersect: false, mode: 'index' },
            }
        });

        // Device Doughnut Chart
        @if($deviceData->count() > 0)
        const ctxDevice = document.getElementById('deviceChart').getContext('2d');
        const deviceData = @json($deviceData);
        
        // Define colors based on device names (mobile, desktop, tablet)
        const deviceColors = deviceData.map(item => {
            if (item.device === 'mobile') return '#3b82f6'; // blue-500
            if (item.device === 'desktop') return '#8b5cf6'; // violet-500
            if (item.device === 'tablet') return '#10b981'; // emerald-500
            return '#94a3b8'; // slate-400
        });

        new Chart(ctxDevice, {
            type: 'doughnut',
            data: {
                labels: deviceData.map(item => item.device.charAt(0).toUpperCase() + item.device.slice(1)),
                datasets: [{
                    data: deviceData.map(item => item.count),
                    backgroundColor: deviceColors,
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        bodyFont: { size: 14, weight: 'bold', family: "'Plus Jakarta Sans', sans-serif" },
                        cornerRadius: 8,
                    }
                }
            }
        });
        @endif
    });
</script>
@endif
@endsection
