<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ECC CHURCH APP</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>

    @include('layouts.partials.navbar')

    {{-- Mobile Offcanvas --}}
    @include('layouts.partials.offcanvas')

    <div class="d-flex">
        @include('layouts.partials.sidebar')

        <main class="app-content">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Theme toggle (simple, persists in localStorage)
        (function() {
            const btn = document.getElementById('themeToggle');
            const icon = btn && btn.querySelector('i');
            const current = localStorage.getItem('ecc-theme') || 'light';
            if (current === 'dark') {
                document.documentElement.classList.add('dark');
                if (icon) icon.className = 'fa fa-moon';
            }
            btn && btn.addEventListener('click', function() {
                const isDark = document.documentElement.classList.toggle('dark');
                localStorage.setItem('ecc-theme', isDark ? 'dark' : 'light');
                if (icon) icon.className = isDark ? 'fa fa-moon' : 'fa fa-sun';
            });
        })();
    </script>

</body>

</html>
