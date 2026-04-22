<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>FAQ - Madtive Docs</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="icon" href="{{ asset('img/logo_madtive.jpg') }}" type="image/png">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter-sans:400,500,600" rel="stylesheet" />
    </head>
    <body class="font-sans antialiased">
        
        <x-main full-width>

            {{-- 1. Sidebar (Sama seperti Release Note) --}}
            <x-slot:sidebar class="bg-gray-50 border-r border-gray-200 w-72 h-screen overflow-y-auto hidden lg:block">
                @include('sidebar_Release')
            </x-slot:sidebar>

            {{-- 2. Konten Utama (Memanggil Livewire FAQ Page) --}}
            <x-slot:content class="bg-white min-h-screen !p-0">
                @livewire('faqs')
            </x-slot:content>

        </x-main>

    </body>
</html>