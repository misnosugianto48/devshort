<x-layouts.guest :title="'Daftar'">
    <h2 class="text-2xl font-bold text-center mb-2">Buat Akun Baru</h2>
    <p class="text-slate-500 text-center mb-8">Mulai singkatkan link Anda sekarang.</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        {{-- Name --}}
        <div>
            <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nama</label>
            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="name"
                class="w-full p-3 border rounded-xl focus:ring-2 ring-indigo-500 outline-none @error('name') border-red-500 @enderror"
                placeholder="Nama lengkap"
            >
            @error('name')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Email</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autocomplete="email"
                class="w-full p-3 border rounded-xl focus:ring-2 ring-indigo-500 outline-none @error('email') border-red-500 @enderror"
                placeholder="email@contoh.com"
            >
            @error('email')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Password</label>
            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                class="w-full p-3 border rounded-xl focus:ring-2 ring-indigo-500 outline-none @error('password') border-red-500 @enderror"
                placeholder="Minimal 8 karakter"
            >
            @error('password')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div>
            <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-2">Konfirmasi Password</label>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                class="w-full p-3 border rounded-xl focus:ring-2 ring-indigo-500 outline-none"
                placeholder="Ulangi password"
            >
        </div>

        {{-- Submit --}}
        <button type="submit" class="w-full gradient-bg text-white py-3 rounded-xl font-bold text-lg shadow-lg shadow-indigo-200 hover:opacity-95 transition">
            Daftar Sekarang
        </button>
    </form>

    <p class="text-center text-sm text-slate-500 mt-6">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="text-indigo-600 font-bold hover:underline">Masuk</a>
    </p>
</x-layouts.guest>
