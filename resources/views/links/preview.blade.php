<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pratinjau Tautan - DevShort</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex items-center justify-center p-4">
    <div class="fixed top-0 left-0 w-full p-6 text-center z-10 hidden md:block">
        <div class="inline-flex items-center gap-2 font-black text-2xl tracking-tighter">
            <div class="w-8 h-8 rounded-lg gradient-bg flex items-center justify-center">
                <i class="fas fa-link text-white text-sm"></i>
            </div>
            <span>DevShort</span>
        </div>
    </div>

    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl overflow-hidden relative border border-slate-100">
        <div class="p-8 pb-6 border-b border-slate-100 flex items-center justify-between">
            <h1 class="text-xl font-bold text-slate-800">Pratinjau Tautan</h1>
            <span class="bg-indigo-50 text-indigo-600 text-xs font-bold px-3 py-1 rounded-full border border-indigo-100">Aman</span>
        </div>
        
        <div class="p-8">
            <div class="mb-8">
                <h2 class="text-lg font-bold text-slate-900 mb-1">{{ $link->title ?? 'Tujuan Tautan' }}</h2>
                <div class="flex items-center gap-2 text-slate-500 text-sm mb-4">
                    <i class="fas fa-calendar-alt w-4"></i>
                    <span>Dibuat pada {{ $link->created_at->format('d M Y') }}</span>
                </div>
                
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 break-all">
                    <p class="text-xs text-slate-400 font-semibold mb-1 uppercase tracking-wider">Tujuan URL</p>
                    <a href="{{ $link->original_url }}" target="_blank" rel="noopener noreferrer" class="text-indigo-600 hover:text-indigo-700 font-medium transition line-clamp-3">
                        {{ $link->original_url }}
                    </a>
                </div>
            </div>
            
            @if($link->password)
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-8 flex gap-3 text-amber-800 text-sm">
                <i class="fas fa-lock mt-0.5 text-amber-500"></i>
                <p><strong>Perhatian:</strong> Tautan ini dilindungi oleh password. Anda akan diminta memasukkan password sebelum diarahkan ke tujuan.</p>
            </div>
            @endif

            <div class="flex flex-col gap-3">
                <a href="{{ url('/' . $shortCode) }}" class="gradient-bg text-white w-full py-4 rounded-xl font-bold hover:opacity-90 shadow-lg shadow-indigo-200 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2 text-center">
                    Lanjutkan ke Tujuan <i class="fas fa-external-link-alt text-sm"></i>
                </a>
                <a href="{{ url('/') }}" class="w-full py-4 rounded-xl font-bold text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition text-center border border-transparent">
                    Batal & Kembali
                </a>
            </div>
        </div>
        
        <div class="bg-slate-50 p-4 text-center border-t border-slate-100">
            <p class="text-xs text-slate-400">Ditenagai oleh <a href="{{ url('/') }}" class="font-bold text-slate-500 hover:text-indigo-600">DevShort</a></p>
        </div>
    </div>
</body>
</html>
