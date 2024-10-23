<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @vite(['resources/sass/app.scss'])
        @vite(['resources/js/vanilla.js'])
    </head>
    <body class="font-sans antialiased">

        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')
            @if(session()->has('error'))
                <div class="toast show float-end" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <i class="bi bi-emoji-tear-fill text-danger me-3"></i>
                        <strong class="me-auto">Error</strong>
                        <small>{{ date('Y-m-d H:i:s') }}</small>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body text-danger">
                        {{ session()->get('error') }}
                    </div>
                </div>
            @endif

            @if(session()->has('success'))
                <div class="toast show float-end" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <i class="bi bi-emoji-smile-fill text-success  me-3"></i>
                        <strong class="me-auto">Success</strong>
                        <small>{{ date('Y-m-d H:i:s') }}</small>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body text-success">
                        {{ session()->get('success') }}
                    </div>
                </div>
            @endif

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
