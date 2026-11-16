<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Griots Lounge Publishing' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400..800;1,400..800&family=Jost:ital,wght@0,300..700;1,300..700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/dashlite.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        serif: ['"EB Garamond"', 'Georgia', 'serif'],
                        sans: ['"Jost"', 'Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            dark: '#131313',
                            emerald: '#00382B',
                            emeraldHover: '#00271E',
                            sage: '#96BBA4',
                            sageHover: '#82A891',
                            teal: '#009688',
                            tealHover: '#00796B',
                            terracotta: '#00382B', // Backward compatible alias
                            terracottaHover: '#00271E',
                            accent: '#96BBA4',
                            accentHover: '#82A891',
                            surface: '#FFFFFF',
                            bg: '#FCFCFC',
                            linen: '#FDF7F2'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Jost', 'Inter', sans-serif; background-color: #FCFCFC; }
        .font-serif { font-family: 'EB Garamond', Georgia, serif; }
    </style>
    @stack('styles')
</head>
<body class="antialiased">
    @yield('content')
    <script src="{{ asset('assets/js/form-submit-spinner.js') }}"></script>
    @stack('scripts')
</body>
</html>
