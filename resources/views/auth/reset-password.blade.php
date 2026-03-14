<x-layouts.guest :title="'Reset Password'">
    <h2 class="text-2xl font-bold text-center mb-2">Reset Password</h2>
    <p class="text-slate-500 text-center mb-8">Buat password baru untuk akun Anda.</p>

    <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Email</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email', $request->email) }}"
                required
                autofocus
                autocomplete="email"
                class="w-full p-3 border rounded-xl focus:ring-2 ring-indigo-500 outline-none @error('email') border-red-500 @enderror"
            >
            @error('email')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Password Baru</label>
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
                placeholder="Ulangi password baru"
            >
        </div>

        {{-- Submit --}}
        <button type="submit" class="w-full gradient-bg text-white py-3 rounded-xl font-bold text-lg shadow-lg shadow-indigo-200 hover:opacity-95 transition">
            Reset Password
        </button>
    </form>
</x-layouts.guest>
