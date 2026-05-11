<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SIPJATI') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <link rel="stylesheet" href="{{ asset('css/app.css') }}">
            <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
            <script src="{{ asset('js/app.js') }}" defer></script>
            <style>
                body{font-family:ui-sans-serif,system-ui,-apple-system,'Segoe UI',Roboto,Arial;background:#f3f4f6;margin:0;color:#111}
                .card{background:#fff;padding:16px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.04)}
                a{color:var(--primary);text-decoration:none}
            </style>
        @endif
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center bg-gray-100">
            <!-- logo removed on auth pages per design request -->
            <div class="auth-center">
                <!-- auth content centered vertically -->
                <div class="w-full sm:max-w-4xl px-6 py-4 bg-transparent">
                    <div class="card" style="display:flex;gap:24px;align-items:stretch">
                        {{ $slot }}
                    </div>
                </div>
            </div>
</html>
