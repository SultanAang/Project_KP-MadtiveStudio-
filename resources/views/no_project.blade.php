<!DOCTYPE html>
<html>
<head>
    <title>Tidak Ada Akses Project</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md max-w-md text-center">
        <div class="mb-4 text-yellow-500">
            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <h2 class="text-2xl font-bold mb-2">Belum Ada Project</h2>
        <p class="text-gray-600 mb-6">
            Halo, <b>{{ auth()->user()->name }}</b>. <br>
            Akun Anda aktif, tetapi Administrator belum menetapkan Project apapun untuk Anda.
        </p>
        <p class="text-sm text-gray-500">
            Silakan hubungi Admin untuk meminta akses.
        </p>
        
        <div class="mt-6 border-t pt-4">
            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="text-red-500 hover:underline text-sm font-semibold">Sign Out</button>
            </form>
        </div>
    </div>
</body>
</html>