@extends('layouts.app')

@section('title', 'Link Saya')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">Link Saya</h1>
        <p class="text-slate-500">Kelola semua tautan yang telah Anda perpendek.</p>
    </div>
    <!-- Reusing the Alpine modal trigger from app.blade.php/dashboard via a shared button if needed, 
         but for here we can link back to dashboard, or include the modal here too. For MVP, back to Dashboard is fine -->
    <a href="{{ route('dashboard') }}" class="gradient-bg text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:opacity-90 flex items-center gap-2 transition transform hover:-translate-y-1">
        <i class="fas fa-plus"></i> Buat Link Baru
    </a>
</div>

<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        @if($links->count() > 0)
            <table class="w-full text-left whitespace-nowrap">
                <thead class="bg-slate-50 text-slate-400 text-xs uppercase font-semibold">
                    <tr>
                        <th class="px-6 py-4">Tautan</th>
                        <th class="px-6 py-4">Short Link</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Dibuat</th>
                        <th class="px-6 py-4">Klik</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($links as $link)
                        <tr class="hover:bg-slate-50/50 transition duration-150">
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-slate-900 truncate max-w-[200px]" title="{{ $link->title ?? $link->original_url }}">
                                    {{ $link->title ?? 'Tanpa Judul' }}
                                </div>
                                <div class="text-xs text-slate-500 truncate max-w-[200px] hover:text-indigo-600 transition" title="{{ $link->original_url }}">
                                    <a href="{{ $link->original_url }}" target="_blank">{{ $link->original_url }}</a>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ url($link->short_code) }}" target="_blank" class="text-indigo-600 font-bold hover:underline flex items-center gap-1 group bg-indigo-50 px-3 py-1.5 rounded-lg w-max">
                                    {{ request()->getHost() }}/{{ $link->short_code }}
                                    <i class="fas fa-external-link-alt text-xs opacity-0 group-hover:opacity-100 transition"></i>
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                @if($link->is_active)
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">Aktif</span>
                                @else
                                    <span class="bg-slate-100 text-slate-500 px-3 py-1 rounded-full text-xs font-bold">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $link->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-700">
                                {{ number_format($link->clicks_count) }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2" x-data="{ copied: false }">
                                    <button @click="navigator.clipboard.writeText('{{ url($link->short_code) }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                                            class="p-2 bg-slate-100 hover:bg-indigo-100 hover:text-indigo-600 rounded-lg text-slate-500 transition" 
                                            :title="copied ? 'Disalin!' : 'Salin URL'">
                                        <i class="fas fa-copy" x-show="!copied"></i>
                                        <i class="fas fa-check text-green-500" x-show="copied" x-cloak></i>
                                    </button>
                                    <a href="{{ route('links.show', $link) }}" class="p-2 bg-slate-100 hover:bg-purple-100 hover:text-purple-600 rounded-lg text-slate-500 transition" title="Lihat Analitik">
                                        <i class="fas fa-chart-pie"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="p-16 text-center flex flex-col items-center justify-center">
                <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-400 mb-6">
                    <i class="fas fa-link text-3xl"></i>
                </div>
                <h4 class="text-xl font-bold text-slate-700 mb-2">Belum ada tautan yang dibuat</h4>
                <p class="text-slate-500 max-w-md mx-auto mb-8">Anda belum menyingkatkan URL apapun. Buat tautan pertama Anda sekarang untuk mulai memantau analitik.</p>
                <a href="{{ route('dashboard') }}" class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                    <i class="fas fa-plus mr-2"></i> Buat Tautan Baru
                </a>
            </div>
        @endif
    </div>

    @if($links->hasPages())
    <div class="p-6 border-t border-slate-100">
        {{ $links->links() }}
    </div>
    @endif
</div>
@endsection
