<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'POS Terminal' }} - {{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    {{ $scripts ?? '' }}
    <style>
        body {
            overflow: hidden;
            height: 100vh;
        }
        .pos-header {
            height: 56px;
            background: #0f172a;
            color: #ffffff;
        }
        .pos-container {
            height: calc(100vh - 56px);
        }
    </style>
</head>
<body class="bg-body-tertiary">
    
    <!-- POS Header -->
    <header class="pos-header px-4 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-light border-secondary text-white rounded-circle p-2" style="width:36px; height:36px;" title="Exit POS to Admin">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div class="fw-bold fs-5 text-white d-flex align-items-center gap-2">
                <i class="fa-solid fa-cash-register text-indigo me-1" style="color: #818cf8;"></i>
                <span>POS Retail Terminal</span>
            </div>
        </div>

        <div class="d-flex align-items-center gap-4">
            <!-- Active Warehouse -->
            <div class="text-white-50 small d-none d-md-block">
                <i class="fa-solid fa-warehouse text-indigo me-1" style="color: #818cf8;"></i> Store: <span class="text-white fw-bold">{{ $warehouse->name ?? 'Default' }}</span>
            </div>

            <!-- Clock -->
            <div class="font-monospace text-white small bg-dark px-3 py-1 rounded-pill border border-secondary border-opacity-50">
                <i class="fa-regular fa-clock me-1 text-warning"></i> <span id="pos-clock">--:--:--</span>
            </div>

            <!-- Cashier -->
            <div class="d-flex align-items-center gap-2">
                <div class="avatar-sm rounded-circle text-white d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width:32px; height:32px; background: #6366f1;">
                    {{ strtoupper(substr(auth('admin')->user()?->name ?? 'C', 0, 1)) }}
                </div>
                <span class="text-white fw-semibold small d-none d-sm-inline">{{ auth('admin')->user()?->name ?? 'Cashier' }}</span>
            </div>
        </div>
    </header>

    <main class="pos-container container-fluid p-0">
        {{ $slot }}
    </main>

    <script>
        function updateClock() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString();
            const clockEl = document.getElementById('pos-clock');
            if (clockEl) clockEl.textContent = timeStr;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>
</html>
