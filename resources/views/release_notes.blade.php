<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    {{-- ... Head ... --}}
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>FAQ - Madtive Docs</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="icon" href="{{ asset('img/logo_madtive.jpg') }}" type="image/png">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter-sans:400,500,600" rel="stylesheet" />
    </head>
    <body class="font-sans antialiased bg-gray-50">
        
        <x-main full-width>

            {{-- PANGGIL SIDEBAR DISINI --}}
            <x-slot:sidebar class="bg-white border-r border-gray-200 w-72 h-screen overflow-y-auto hidden lg:block custom-scrollbar">
                
                @include('sidebar_Release',[
                    ])  {{-- <--- CUKUP SATU BARIS INI --}}
            
            </x-slot:sidebar>

            {{-- KONTEN --}}
            <x-slot:content class="bg-white min-h-screen !p-0">
                @livewire('release_notes')
            </x-slot:content>

        </x-main>

    </body>
</html>