@extends('layouts.app')

@section('title', 'Link Saya')

@section('content')
<div x-data="linkManagement()" class="space-y-6">
    <!-- Header & Actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Link Saya</h1>
            <p class="text-slate-500">Kelola semua tautan yang telah Anda perpendek.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="gradient-bg text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:opacity-90 flex items-center gap-2 transition transform hover:-translate-y-1">
            <i class="fas fa-plus"></i> Buat Link Baru
        </a>
    </div>

    <!-- Search & Filters -->
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col md:flex-row gap-4 items-center justify-between">
        <form action="{{ route('links.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 w-full md:w-auto flex-grow" id="filterForm">
            <!-- Search -->
            <div class="relative w-full md:w-96">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, alias, atau tautan asli..." class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-slate-700 bg-slate-50">
                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
            </div>
            <!-- Filter Status -->
            <div class="w-full md:w-48 relative">
                <select name="status" onchange="document.getElementById('filterForm').submit()" class="w-full pl-10 pr-8 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-slate-700 bg-slate-50 appearance-none">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                <i class="fas fa-filter absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
            </div>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('links.index') }}" class="py-2 px-4 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-medium transition whitespace-nowrap text-center">Reset</a>
            @endif
        </form>
    </div>

    <!-- Bulk Actions Sticky Bar -->
    <div x-show="selectedIds.length > 0" x-cloak x-transition class="bg-indigo-50 border border-indigo-100 p-4 rounded-2xl flex flex-col sm:flex-row justify-between items-center gap-4 fixed bottom-6 left-1/2 -translate-x-1/2 shadow-2xl z-40 w-[95%] max-w-2xl">
        <div class="font-bold text-indigo-800">
            <span x-text="selectedIds.length"></span> tautan dipilih
        </div>
        <div class="flex gap-2 w-full sm:w-auto">
            <button @click="submitBulk('activate')" class="flex-1 sm:flex-none px-4 py-2 bg-white border border-indigo-200 text-indigo-600 hover:bg-indigo-100 font-bold rounded-lg transition text-xs">Aktifkan</button>
            <button @click="submitBulk('deactivate')" class="flex-1 sm:flex-none px-4 py-2 bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 font-bold rounded-lg transition text-xs">Nonaktifkan</button>
            <button @click="submitBulk('delete')" class="flex-1 sm:flex-none px-4 py-2 bg-red-500 text-white hover:bg-red-600 font-bold rounded-lg transition text-xs shadow-sm shadow-red-200">Hapus</button>
        </div>
    </div>

    <!-- Form for Bulk Actions -->
    <form id="bulkForm" action="{{ route('links.bulk') }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="action" id="bulkActionInput">
        <template x-for="id in selectedIds" :key="id">
            <input type="hidden" name="ids[]" :value="id">
        </template>
    </form>

    <!-- Links Table -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            @if($links->count() > 0)
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-slate-50 text-slate-400 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-4 w-12 text-center">
                                <input type="checkbox" @change="toggleAll" :checked="isAllSelected" class="w-4 h-4 accent-indigo-600 rounded cursor-pointer">
                            </th>
                            <th class="px-6 py-4">Tautan</th>
                            <th class="px-6 py-4">Short Link</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4">Klik</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($links as $link)
                            <tr class="hover:bg-slate-50/50 transition duration-150" :class="{ 'bg-indigo-50/30' : selectedIds.includes({{ $link->id }}) }">
                                <td class="px-6 py-4 text-center">
                                    <input type="checkbox" value="{{ $link->id }}" x-model.number="selectedIds" class="w-4 h-4 accent-indigo-600 rounded cursor-pointer">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-slate-900 truncate max-w-[200px]" title="{{ $link->title ?? $link->original_url }}">
                                        {{ $link->title ?? 'Tanpa Judul' }}
                                    </div>
                                    <div class="text-xs text-slate-500 truncate max-w-[200px] hover:text-indigo-600 transition" title="{{ $link->original_url }}">
                                        <a href="{{ $link->original_url }}" target="_blank">{{ $link->original_url }}</a>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ url($link->short_code) }}" target="_blank" class="text-indigo-600 font-bold hover:underline flex items-center gap-1 group bg-indigo-50 px-3 py-1.5 rounded-lg w-max relative">
                                        {{ request()->getHost() }}/{{ $link->short_code }}
                                        <i class="fas fa-external-link-alt text-xs opacity-0 group-hover:opacity-100 transition absolute right-2"></i>
                                        @if($link->password)
                                            <i class="fas fa-lock text-xs text-slate-400 ml-1" title="Terkunci"></i>
                                        @endif
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($link->is_active)
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] uppercase tracking-wider font-bold">Aktif</span>
                                    @else
                                        <span class="bg-slate-100 text-slate-500 px-3 py-1 rounded-full text-[10px] uppercase tracking-wider font-bold">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-700">
                                    {{ number_format($link->clicks_count) }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-1" x-data="{ copied: false, showQr: false }">
                                        <!-- Edit Action -->
                                        <button @click="openEditModal({{ $link->id }}, '{{ addslashes($link->title ?? '') }}', {{ $link->is_active ? 'true' : 'false' }})" class="p-2 text-slate-400 hover:text-blue-500 hover:bg-blue-50 transition rounded-lg" title="Edit Tautan">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <!-- QR Code Action -->
                                        <div class="relative">
                                            <button @click="showQr = !showQr" @click.away="showQr = false" class="p-2 text-slate-400 hover:text-orange-500 hover:bg-orange-50 transition rounded-lg" title="QR Code">
                                                <i class="fas fa-qrcode"></i>
                                            </button>
                                            <div x-show="showQr" x-transition.opacity x-cloak class="absolute bottom-full mb-2 right-1/2 translate-x-1/2 bg-white p-3 rounded-xl shadow-xl border border-slate-100 z-50 flex flex-col items-center">
                                                <div class="bg-white p-1 rounded-lg">
                                                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->color(79, 70, 229)->generate(url($link->short_code)) !!}
                                                </div>
                                                <a href="data:image/svg+xml;base64,{{ base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::size(500)->color(79, 70, 229)->generate(url($link->short_code))) }}" download="qr-{{ $link->short_code }}.svg" class="text-xs font-bold text-slate-500 hover:text-indigo-600 mt-2 flex items-center gap-1">
                                                    <i class="fas fa-download"></i> SVG
                                                </a>
                                                <div class="absolute -bottom-2 right-1/2 translate-x-1/2 w-4 h-4 bg-white border-b border-r border-slate-100 transform rotate-45"></div>
                                            </div>
                                        </div>
                                        
                                        <!-- Copy Action -->
                                        <button @click="navigator.clipboard.writeText('{{ url($link->short_code) }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                                                class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition rounded-lg" 
                                                :title="copied ? 'Disalin!' : 'Salin URL'">
                                            <i class="fas fa-copy" x-show="!copied"></i>
                                            <i class="fas fa-check text-green-500" x-show="copied" x-cloak></i>
                                        </button>

                                        <!-- Analytics Action -->
                                        <a href="{{ route('links.show', $link) }}" class="p-2 text-slate-400 hover:text-purple-600 hover:bg-purple-50 transition rounded-lg" title="Lihat Analitik">
                                            <i class="fas fa-chart-pie"></i>
                                        </a>

                                        <!-- Delete Action -->
                                        <button @click="confirmDelete('{{ route('links.destroy', $link) }}')" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 transition rounded-lg" title="Hapus Tautan">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-16 text-center flex flex-col items-center justify-center">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-4">
                        <i class="fas fa-search text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-700 mb-2">Tautan tidak ditemukan</h4>
                    <p class="text-slate-500 max-w-md mx-auto mb-6">Kami tidak dapat menemukan tautan yang cocok dengan kriteria pencarian atau filter Anda.</p>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('links.index') }}" class="px-6 py-2 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition">
                            Hapus Filter
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="px-6 py-2 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition">
                            Buat Tautan Baru
                        </a>
                    @endif
                </div>
            @endif
        </div>

        @if($links->hasPages())
        <div class="p-6 border-t border-slate-100 bg-slate-50/50">
            {{ $links->links() }}
        </div>
        @endif
    </div>

    <!-- Edit Modal -->
    <div x-show="editModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="editModalOpen = false"></div>
        <div x-show="editModalOpen" x-transition.scale.origin.bottom class="bg-white rounded-3xl shadow-2xl w-full max-w-md relative z-10 overflow-hidden" @click.stop>
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="font-bold text-lg text-slate-800">Edit Tautan</h3>
                <button @click="editModalOpen = false" class="text-slate-400 hover:bg-slate-200 hover:text-slate-700 rounded-full w-8 h-8 flex items-center justify-center transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form :action="'/links/' + editData.id" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Judul Tautan <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <input type="text" name="title" x-model="editData.title" class="w-full p-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition bg-slate-50 focus:bg-white text-slate-700" placeholder="Materi Rapat Q3...">
                    </div>
                    
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                        <label class="flex items-center justify-between cursor-pointer">
                            <div>
                                <span class="text-sm font-bold text-slate-800 block">Status Tautan</span>
                                <span class="text-xs text-slate-500 block">Tautan tidak bisa diakses pengunjung bila nonaktif.</span>
                            </div>
                            <div class="relative inline-block w-12 mr-2 align-middle select-none transition duration-200 ease-in">
                                <input type="checkbox" name="is_active" value="1" x-model="editData.is_active" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer border-slate-200 transition-transform duration-200 checked:translate-x-6 checked:border-indigo-500"/>
                                <label class="toggle-label block overflow-hidden h-6 rounded-full bg-slate-200 cursor-pointer pointer-events-none transition-colors duration-200" :class="{ 'bg-indigo-500': editData.is_active }"></label>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="p-6 border-t border-slate-100 flex justify-end gap-3 bg-slate-50">
                    <button type="button" @click="editModalOpen = false" class="px-5 py-2.5 rounded-xl font-bold text-slate-600 hover:bg-slate-200 transition">Batal</button>
                    <button type="submit" class="gradient-bg text-white px-5 py-2.5 rounded-xl font-bold shadow-md hover:opacity-90 transition transform hover:-translate-y-0.5">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Hidden Delete Form (Single Delete) -->
    <form id="deleteForm" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</div>

@endsection

@section('scripts')
<style>
    .toggle-checkbox:checked { right: 0; border-color: #6366f1; }
    .toggle-checkbox:checked + .toggle-label { background-color: #6366f1; }
</style>
<script>
    function linkManagement() {
        return {
            allIds: {{ Js::from($links->pluck('id')) }},
            selectedIds: [],
            editModalOpen: false,
            editData: {
                id: null,
                title: '',
                is_active: false
            },
            
            get isAllSelected() {
                return this.selectedIds.length === this.allIds.length && this.allIds.length > 0;
            },
            
            toggleAll() {
                if (this.isAllSelected) {
                    this.selectedIds = [];
                } else {
                    this.selectedIds = [...this.allIds];
                }
            },
            
            submitBulk(action) {
                if (this.selectedIds.length === 0) return;
                
                let confirmMessage = `Apakah Anda yakin ingin `;
                if (action === 'delete') confirmMessage += 'MENGHAPUS secara permanen ';
                else if (action === 'activate') confirmMessage += 'mengaktifkan ';
                else confirmMessage += 'menonaktifkan ';
                
                confirmMessage += `${this.selectedIds.length} tautan?`;

                if (confirm(confirmMessage)) {
                    document.getElementById('bulkActionInput').value = action;
                    document.getElementById('bulkForm').submit();
                }
            },

            openEditModal(id, title, isActive) {
                this.editData = { id, title, is_active: isActive };
                this.editModalOpen = true;
            },

            confirmDelete(actionUrl) {
                if(confirm('Tautan ini akan dihapus secara permanen. Lanjutkan?')) {
                    const form = document.getElementById('deleteForm');
                    form.action = actionUrl;
                    form.submit();
                }
            }
        }
    }
</script>
@endsection
