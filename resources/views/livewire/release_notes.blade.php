<div class="bg-white min-h-screen font-sans">
    
    {{-- Tambahkan Style Animasi Custom --}}
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0; /* Mulai dari invisible agar tidak flicker */
        }
        /* Delay utility classes jika tidak ingin pakai inline style */
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
    </style>

    {{-- Mobile Header --}}
    <div class="lg:hidden p-4 border-b flex items-center sticky top-0 bg-white/90 backdrop-blur-md z-20 shadow-sm transition-all">
        <label for="main-drawer" class="btn btn-square btn-ghost btn-sm mr-2">
            <x-icon name="o-bars-3" class="w-6 h-6" />
        </label>
        <span class="font-bold text-gray-800">Release Notes</span>
    </div>

    <div class="max-w-4xl mx-auto py-12 px-8 lg:px-16">

        {{-- Header & Dropdown (Animasi Masuk Awal) --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12 border-b border-gray-100 pb-8 animate-fade-in-up">
            <div>
                <h1 class="text-4xl font-extrabold text-gray-900 mb-2 tracking-tight">Changelog</h1>
                <p class="text-gray-500">Pilih versi untuk melihat detail perubahan.</p>
            </div>

            {{-- === DROPDOWN PEMILIH VERSI === --}}
            <div class="w-full md:w-64">
                <x-select 
                    label="Pilih Versi"
                    icon="o-tag"
                    :options="$versionList"
                    wire:model.live="selectedVersionId"
                    option-label="version"
                    option-value="id"
                    class="font-bold shadow-sm"
                />
            </div>
        </div>

        {{-- AREA KONTEN --}}
        @if($selectedRelease)
            {{-- 
                PENTING: wire:key ini memastikan DOM di-reset saat versi berubah,
                sehingga animasi 'animate-fade-in-up' akan berputar ulang (replay)
            --}}
            <div wire:key="release-{{ $selectedRelease->id }}">
                
                {{-- Header Rilis Single --}}
                <div class="mb-8 bg-gray-50 rounded-2xl p-6 border border-gray-100 flex items-center justify-between animate-fade-in-up shadow-sm">
                    <div>
                        <div class="text-sm text-gray-500 uppercase font-bold tracking-wider mb-1">
                            Current Version
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="font-black text-4xl text-indigo-600 tracking-tight">{{ $selectedRelease->version }}</span>
                            {{-- Badge Terbaru (Opsional) --}}
                            {{-- LOGIKA BARU: Cek apakah ID rilis ini sama dengan ID rilis pertama di list --}}
                            @if($versionList->first()['id'] == $selectedRelease->id) 
                                <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-2 py-1 rounded-full animate-pulse">LATEST</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs text-gray-400 font-bold uppercase mb-1">Released Date</div>
                        <div class="font-semibold text-gray-700">
                            {{ $selectedRelease->published_at->format('d F Y') }}
                        </div>
                    </div>
                </div>

                {{-- Intro Text --}}
                <div class="prose prose-lg prose-indigo text-gray-600 leading-relaxed mb-12 max-w-none animate-fade-in-up delay-100">
                    {!! $selectedRelease->intro_text !!}
                </div>

                {{-- Grid Fitur --}}
                @if($selectedRelease->features)
                    <div class="animate-fade-in-up delay-200">
                        <h3 class="font-bold text-gray-900 text-xl mb-6 flex items-center gap-2">
                            <x-icon name="o-sparkles" class="w-6 h-6 text-yellow-500 animate-bounce" style="animation-duration: 2s;" />
                            What's New
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-12">
                            @foreach($selectedRelease->features as $index => $feature)
                                {{-- 
                                    STAGGERED ANIMATION:
                                    Kita gunakan $loop->index (atau $index) untuk menambah delay pada setiap kartu.
                                    Kartu 1 muncul 0ms, Kartu 2 muncul 100ms, dst.
                                --}}
                                <div 
                                    class="flex items-start gap-4 p-5 rounded-xl border border-gray-100 bg-white shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 group animate-fade-in-up"
                                    style="animation-delay: {{ ($index + 3) * 100 }}ms; opacity: 0; animation-fill-mode: forwards;"
                                >
                                    <div class="bg-indigo-50 group-hover:bg-indigo-600 transition-colors duration-300 p-2 rounded-lg text-indigo-600 group-hover:text-white shrink-0">
                                        <x-icon name="o-check" class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900 text-base group-hover:text-indigo-700 transition-colors">
                                            {{ $feature['title'] ?? 'Fitur Baru' }}
                                        </h3>
                                        <p class="text-sm text-gray-500 mt-2 leading-relaxed">
                                            {{ $feature['description'] ?? '' }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        @else
            {{-- State Kosong (Animated) --}}
            <div class="text-center py-20 bg-gray-50 rounded-xl border-dashed border-2 border-gray-200 animate-fade-in-up">
                <x-icon name="o-face-frown" class="w-12 h-12 text-gray-300 mx-auto mb-4 animate-pulse" />
                <p class="text-gray-500 font-bold">Belum ada rilis yang dipublish.</p>
            </div>
        @endif

        {{-- Footer Copyright --}}
        <div class="mt-12 text-center text-xs text-gray-400 animate-fade-in-up delay-300">
            &copy; 2026 Madtive Studio.
        </div>

    </div>
</div>