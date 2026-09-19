<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'POS Terminal' }}</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    {{ $scripts ?? '' }}
</head>
<body class="bg-body-tertiary overflow-hidden" style="height: 100vh;">
    
    <!-- POS requires a full-height flex layout -->
    <div class="d-flex flex-column h-100">
        @if(isset($header))
            <header class="border-bottom bg-body flex-shrink-0">
                {{ $header }}
            </header>
        @endif

        <main class="d-flex flex-grow-1 overflow-hidden">
            {{ $slot }}
        </main>
    </div>

    <script>
        const theme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', theme);
    </script>
</body>
</html>
