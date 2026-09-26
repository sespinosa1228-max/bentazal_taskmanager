<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Task Manager') · Night Shift</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="app-shell">
        <header class="topbar">
            <a class="brand" href="{{ route('tasks.index') }}" aria-label="Night Shift task manager home">
                <span class="brand-mark" aria-hidden="true">N</span>
                <span class="brand-copy">
                    <span class="brand-kicker">PERSONAL OPERATIONS</span>
                    <span class="brand-name">Night Shift</span>
                </span>
            </a>
            <div class="topbar-note"><span class="signal-dot"></span> ALL SYSTEMS CLEAR</div>
        </header>

        @yield('content')

        <footer class="site-footer">
            <span>ONE THING AT A TIME.</span>
            <span>STAY SHARP <span class="footer-star">✳</span></span>
        </footer>
    </div>
</body>
</html>