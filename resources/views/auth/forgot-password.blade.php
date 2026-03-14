<x-layouts.guest :title="'Lupa Password'">
    <h2 class="text-2xl font-bold text-center mb-2">Lupa Password?</h2>
    <p class="text-slate-500 text-center mb-8">Masukkan email Anda dan kami akan mengirimkan link untuk mereset password.</p>

    {{-- Session Status --}}
    @if (session('status'))
        <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-xl border border-green-100 text-sm font-medium">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
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

        {{-- Submit --}}
        <button type="submit" class="w-full gradient-bg text-white py-3 rounded-xl font-bold text-lg shadow-lg shadow-indigo-200 hover:opacity-95 transition">
            Kirim Link Reset
        </button>
    </form>

    <p class="text-center text-sm text-slate-500 mt-6">
        <a href="{{ route('login') }}" class="text-indigo-600 font-bold hover:underline">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke halaman login
        </a>
    </p>
</x-layouts.guest>
