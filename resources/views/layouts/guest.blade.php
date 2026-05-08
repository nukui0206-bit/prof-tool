<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ProfTool') }}</title>

        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </head>
    <body class="d-flex flex-column min-vh-100 justify-content-center align-items-center py-5">
        <div class="mb-4">
            <a href="/" class="text-decoration-none">
                <x-application-logo />
            </a>
        </div>

        <div class="card shadow-sm w-100" style="max-width: 28rem;">
            <div class="card-body p-4">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
