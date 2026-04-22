<!DOCTYPE html>
<html lang="id" data-theme="light"> {{-- Pastikan ada data-theme --}}
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Madtive Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('img/logo_madtive.jpg') }}" type="image/png">
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen">

    {{-- Notifikasi Toast --}}
    <x-toast />

    {{-- Wrapper Utama: Flex Row --}}
    <div class="flex h-screen overflow-hidden">
        
        {{-- SIDEBAR --}}
        <aside class="w-64 bg-indigo-900 text-white flex flex-col shadow-xl flex-shrink-0">
            <div class="h-16 flex items-center justify-center border-b border-indigo-800 font-bold text-xl">
                Madtive Admin
            </div>

            <nav class="flex-1 px-2 py-4 space-y-2 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" wire:navigate class="flex items-center px-4 py-2 hover:bg-indigo-800 rounded transition">
                    Dashboard
                </a>
                <a href="{{ route('manageUser') }}" wire:navigate class="flex items-center px-4 py-2 hover:bg-indigo-800 rounded transition">
                    Kelola User
                </a>
                <a href="{{ route('admin.projects') }}" wire:navigate class="flex items-center px-4 py-2 hover:bg-indigo-800 rounded transition">
                    Kelola Project
                </a>
                {{-- Logout --}}
                <div class="mt-auto pt-4 border-t border-indigo-800">
                    {{-- {{ route('logout') }} --}}
                     <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 hover:bg-red-600 rounded transition text-red-200 hover:text-white">
                            Logout
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        {{-- KONTEN UTAMA --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            {{-- Header Atas --}}
            <header class="bg-white shadow h-16 flex items-center justify-between px-6 z-10">
                <h2 class="font-semibold text-xl text-gray-800">Admin Area</h2>
                <div class="text-sm text-gray-500">Halo, {{ Auth::user()->name ?? 'User' }}</div>
            </header>

            {{-- Area Scrollable --}}
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                {{ $slot }}
            </main>
        </div>

    </div>

</body>
</html>