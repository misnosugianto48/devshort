<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'DevShort' }} - DevShort</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900 font-sans min-h-screen flex flex-col items-center justify-center p-4">

    {{-- Logo --}}
    <a href="/" class="flex items-center gap-2 mb-8">
        <div class="w-10 h-10 gradient-bg rounded-xl flex items-center justify-center text-white shadow-lg">
            <i class="fas fa-link text-xl"></i>
        </div>
        <span class="text-2xl font-extrabold tracking-tight text-indigo-600">DevShort</span>
    </a>

    {{-- Auth Card --}}
    <div class="w-full max-w-md">
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
            {{ $slot }}
        </div>
    </div>

    {{-- Footer --}}
    <p class="mt-8 text-sm text-slate-400">&copy; {{ date('Y') }} DevShort. All rights reserved.</p>

</body>
</html>
