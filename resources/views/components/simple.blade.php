<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        @stack('meta')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:main>
            {{ $slot }}
        </flux:main>
        @fluxScripts
    </body>
</html>
