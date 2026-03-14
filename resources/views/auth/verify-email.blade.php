<x-layouts.guest :title="'Verifikasi Email'">
    <div class="text-center">
        <div class="w-16 h-16 gradient-bg rounded-2xl flex items-center justify-center text-white mx-auto mb-6">
            <i class="fas fa-envelope text-2xl"></i>
        </div>

        <h2 class="text-2xl font-bold mb-2">Verifikasi Email Anda</h2>
        <p class="text-slate-500 mb-8">
            Kami telah mengirimkan link verifikasi ke email Anda. Silakan periksa inbox (dan folder spam) untuk melanjutkan.
        </p>

        @if (session('status') === 'verification-link-sent')
            <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-xl border border-green-100 text-sm font-medium">
                Link verifikasi baru telah dikirim ke email Anda.
            </div>
        @endif

        <div class="flex flex-col gap-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full gradient-bg text-white py-3 rounded-xl font-bold shadow-lg shadow-indigo-200 hover:opacity-95 transition">
                    Kirim Ulang Email Verifikasi
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full py-3 rounded-xl border-2 border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition">
                    Keluar
                </button>
            </form>
        </div>
    </div>
</x-layouts.guest>
