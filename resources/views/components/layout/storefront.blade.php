<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'E-commerce') }}</title>

    <!-- Vite Assets -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    {{ $scripts ?? '' }}
</head>
<body class="bg-body text-body">
    
    <!-- Header Slot or default -->
    @if(isset($header))
        {{ $header }}
    @endif

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer Slot -->
    @if(isset($footer))
        {{ $footer }}
    @endif

    <script>
        // Theme init
        const theme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', theme);
    </script>
</body>
</html>
