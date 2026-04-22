<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title ?? 'Reviewer Internal' }}</title>
        <link rel="icon" href="{{ asset('img/logo_madtive.jpg') }}" type="image/png">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-50">
        {{-- HAPUS baris @livewire('reviewer_base') --}}
        {{-- GANTI dengan: --}}
        
        {{ $slot }}

    </body>
</html>