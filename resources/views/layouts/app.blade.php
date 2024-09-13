<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title', 'Budgetify')</title>

        {{-- Include shared assets --}}
        @include('inc.asset')

        {{-- Page-specific styles --}}
        @stack('styles')

        <style>
        .rainbow_text_animated {
            background: linear-gradient(to right, #6666ff, #0099ff, #00ff00, #ff3399, #6666ff);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: rainbow_animation 6s ease-in-out infinite;
            background-size: 400% 100%;
        }
        @keyframes rainbow_animation {
            0%, 100% { background-position: 0 0; }
            50% { background-position: 100% 0; }
        }
        </style>
    </head>
    <body>

        {{-- Include the navigation bar --}}
        @include('layouts.navbar')

        {{-- Main content section --}}
        <div>
            @yield('content')
        </div>

        {{-- Page-specific scripts --}}
        @stack('scripts')
    </body>
</html>
