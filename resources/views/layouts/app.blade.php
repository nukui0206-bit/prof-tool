<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ProfTool') }}</title>

        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </head>
    <body class="d-flex flex-column min-vh-100">
        @include('layouts.navigation')

        @isset($header)
            <header class="bg-white border-bottom shadow-sm">
                <div class="container py-3">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="flex-grow-1 py-4">
            <div class="container">
                {{ $slot }}
            </div>
        </main>

        <footer class="border-top bg-white py-3 mt-auto">
            <div class="container small text-muted text-center">
                © {{ date('Y') }} ProfTool
            </div>
        </footer>

        @stack('scripts')
    </body>
</html>
