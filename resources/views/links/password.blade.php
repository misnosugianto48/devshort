<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tautan Dilindungi Password - DevShort</title>
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

    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl overflow-hidden relative">
        <div class="h-2 w-full gradient-bg"></div>
        <div class="p-8 sm:p-10 text-center relative">
            <div class="w-20 h-20 bg-indigo-50 text-indigo-500 rounded-full flex items-center justify-center mx-auto mb-6 relative z-10 shadow-sm border border-indigo-100">
                <i class="fas fa-lock text-3xl"></i>
            </div>
            
            <h1 class="text-2xl font-extrabold text-slate-900 mb-2 relative z-10">Tautan Terkunci</h1>
            <p class="text-slate-500 mb-8 relative z-10 text-sm">
                Pemilik tautan ini melindunginya dengan password. Masukkan password di bawah ini untuk mengakses <strong>{{ $link->title ?? $link->short_code }}</strong>.
            </p>
            
            <form action="{{ route('link.password.verify', $link->id) }}" method="POST">
                @csrf
                <div class="mb-6 relative text-left">
                    <input type="password" name="password" required autofocus placeholder="Masukkan Password" class="w-full p-4 pl-12 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition text-slate-700 bg-slate-50 focus:bg-white @error('password') border-red-500 @enderror">
                    <i class="fas fa-key absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    @error('password')
                        <p class="text-xs text-red-500 mt-2 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
                
                <button type="submit" class="gradient-bg text-white w-full py-4 rounded-xl font-bold hover:opacity-90 shadow-lg shadow-indigo-200 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    Buka Tautan <i class="fas fa-arrow-right text-sm"></i>
                </button>
            </form>
        </div>
        <div class="bg-slate-50 p-4 text-center border-t border-slate-100">
            <p class="text-xs text-slate-400">Ditenagai secara aman oleh <a href="{{ url('/') }}" class="font-bold text-slate-500 hover:text-indigo-600">DevShort</a></p>
        </div>
    </div>
</body>
</html>
