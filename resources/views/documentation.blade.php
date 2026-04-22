<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'Create User' }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter-sans:400,500,600" rel="stylesheet" />
    </head>
    <body>
        {{-- <livewire:Users/> --}}
        @livewire('documentation')
        {{-- {{ $slot }} --}}
    </body>
</html>
