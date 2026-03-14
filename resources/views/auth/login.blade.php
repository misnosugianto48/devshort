<x-layouts.guest :title="'Masuk'">
    <h2 class="text-2xl font-bold text-center mb-2">Selamat Datang Kembali</h2>
    <p class="text-slate-500 text-center mb-8">Masuk ke dashboard Anda.</p>

    {{-- Session Status --}}
    @if (session('status'))
        <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-xl border border-green-100 text-sm font-medium">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Email</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
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
                autocomplete="current-password"
                class="w-full p-3 border rounded-xl focus:ring-2 ring-indigo-500 outline-none @error('password') border-red-500 @enderror"
                placeholder="Password Anda"
            >
            @error('password')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember Me & Forgot Password --}}
        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" class="w-4 h-4 accent-indigo-600 rounded">
                <span class="text-sm text-slate-600">Ingat saya</span>
            </label>
            <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 font-bold hover:underline">
                Lupa password?
            </a>
        </div>

        {{-- Submit --}}
        <button type="submit" class="w-full gradient-bg text-white py-3 rounded-xl font-bold text-lg shadow-lg shadow-indigo-200 hover:opacity-95 transition">
            Masuk
        </button>
    </form>

    <p class="text-center text-sm text-slate-500 mt-6">
        Belum punya akun?
        <a href="{{ route('register') }}" class="text-indigo-600 font-bold hover:underline">Daftar</a>
    </p>
</x-layouts.guest>
