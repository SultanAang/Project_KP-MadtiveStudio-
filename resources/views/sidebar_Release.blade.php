<div>
    {{-- BAGIAN 1: BRANDING / LOGO --}}
    @php
    // KITA AMBIL LANGSUNG DARI SUMBERNYA (Karena Model Login.php sudah benar)
    $project = auth()->user()->client?->projects->first();

    $client = auth()->user()->client;

    // 2. Ambil SEMUA Project (Untuk isi Dropdown)
    $allProjects = $client?->projects ?? collect();

    // 3. Cek apakah ada request ganti project di URL? (?project_id=2)
    $reqId = request()->get('project_id');

    // 4. Tentukan Project Mana yang Aktif
    if ($reqId) {
        // Kalau ada request ID, cari project yang ID-nya cocok
        $project = $allProjects->where('id', $reqId)->first();
    } 
    
    // Kalau tidak ada request (atau ID salah), pakai project PERTAMA sebagai default
    if (empty($project)) {
        $project = $allProjects->first();
    }
    @endphp
    <div class="p-4">
    <label class="text-xs text-gray-500 font-bold uppercase">Pilih Project</label>
    
    {{-- Debug: Jika ingin memaksa muncul, ganti > 1 menjadi > 0 sementara --}}
    @if($allProjects->count() > 1)
    
        <select 
            class="w-full mt-1 p-2 border rounded-md bg-white text-gray-700 text-sm focus:ring-indigo-500"
            {{-- Gunakan url()->current() agar tetap di halaman yang sama --}}
            onchange="window.location.href='{{ url()->current() }}?project_id=' + this.value"
        >
            @foreach($allProjects as $p)
                <option value="{{ $p->id }}" {{ (isset($project) && $project->id == $p->id) ? 'selected' : '' }}>
                    {{ $p->name }}
                </option>
            @endforeach
        </select>

    @else
        {{-- Tampil jika project cuma 1 atau 0 --}}
        <div class="font-bold text-lg mt-1 text-indigo-700">
            {{ $project->name ?? 'Belum Ada Project' }}
        </div>
    @endif
    </div>
    <div class="p-6 pt-8">
        <a href="/" wire:navigate class="font-bold text-xl text-indigo-700 flex items-center gap-3 hover:opacity-80 transition">
            
            {{-- Bagian Logo Project --}}
            @if($project && $project->logo) 
                {{-- Jika project punya logo, tampilkan logonya --}}
                <div class="mask mask-squircle w-12 h-12 bg-gray-50 border border-gray-200 flex items-center justify-center overflow-hidden shrink-0 shadow-sm">
                    <img src="{{ asset('storage/' . $project->logo) }}" alt="Logo {{ $project->name }}" class="w-full h-full object-cover">
                </div>
            @else
                {{-- Fallback: Jika tidak ada logo, kembalikan ke icon buku --}}
                <div class="mask mask-squircle w-12 h-12 bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 shadow-sm">
                    <x-icon name="o-book-open" class="w-6 h-6" />
                </div>
            @endif
            
            {{-- Nama Project --}}
            <span class="truncate">{{ $project->name ?? 'Belum Ada Project' }}</span>
        </a>
    </div>

    {{-- BAGIAN 2: MENU NAVIGASI --}}
    <x-menu class="px-3 pb-10">
        
        {{-- Label Group (Opsional, biar rapi) --}}
        <x-menu-separator title="Product Updates" class="mt-2 mb-2 text-xs font-bold text-gray-400 uppercase tracking-wider" />

        {{-- RELEASE NOTES --}}
        {{-- Logic: Jika URL mengandung 'release-notes', beri warna background --}}
        <x-menu-item 
            title="Release Notes" 
            icon="o-rocket-launch" 
            link="/release-notes" 
            class="mb-1 {{ request()->is('release-notes*') ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-gray-600' }}" 
        />

        {{-- ROADMAP --}}
        <x-menu-item 
            title="Roadmap" 
            icon="o-map" 
            link="/roadmap" 
            class="mb-1 {{ request()->is('roadmap*') ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-gray-600' }}" 
        />

        <x-menu-separator title="Support Center" class="mt-6 mb-2 text-xs font-bold text-gray-400 uppercase tracking-wider" />

        {{-- KNOWLEDGE BASE --}}
        <x-menu-item 
            title="Panduan" 
            icon="o-academic-cap" 
            link="/knowledge" 
            class="mb-1 {{ request()->is('knowledge*') ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-gray-600' }}" 
        />

        {{-- FAQ --}}
        <x-menu-item 
            title="F.A.Q" 
            icon="o-question-mark-circle" 
            link="/faq" 
            class="mb-1 {{ request()->is('faq*') ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-gray-600' }}" 
        />
        
        {{-- BAGIAN 3: FOOTER MENU (Keluar / Kembali) --}}
        <x-menu-separator title="Report" class="mt-6 mb-2 text-xs font-bold text-gray-400 uppercase tracking-wider" />
        <x-menu-item 
            title="Laporkan Bug"
            icon="o-bug-ant" 
            link="/report" 
            class="mb-1 {{ request()->is('reportbug*') ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-gray-600' }}" 
        />
        <x-menu-separator class="my-4" />

        {{-- <x-menu-item 
            title="Kembali ke Aplikasi" 
            icon="o-arrow-left-start-on-rectangle" 
            link="/" 
            class="text-gray-500 hover:text-gray-800 hover:bg-gray-100"
        /> --}}
        @auth
        {{-- Tampilkan Nama User --}}
        <div class="px-4 py-2 mb-2 text-xs font-bold text-gray-400 uppercase tracking-wider">
            Logged in as: {{ auth()->user()->name }}
        </div>

        {{-- 1. Siapkan FORM TERSEMBUNYI (Hidden) --}}
        {{-- Kita beri ID unik 'logout-form' agar mudah dipanggil --}}
        <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
            @csrf
        </form>

        {{-- 2. Tombol Menu Normal (Bukan tipe submit, tapi Link biasa) --}}
        {{-- Saat diklik, Javascript akan mencari form diatas dan mengirimnya --}}
        <x-menu-item 
            title="Log Out" 
            icon="o-power" 
            link="#" 
            class="text-red-500 hover:bg-red-50 hover:text-red-700 font-bold"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
        />
    @endauth
    </x-menu>
    {{-- <div class="text-[10px] bg-red-100 p-2 mt-2 rounded text-red-800">
    <p>Debug Info:</p>
    <p>User: {{ auth()->user()->name }}</p>
    <p>Role: {{ auth()->user()->role }}</p>
    <p>Is Client?: {{ auth()->user()->client ? 'YA' : 'TIDAK' }}</p>
    <p>Jml Project: {{ auth()->user()->client?->projects->count() ?? 0 }}</p>
</div>
</div> --}}