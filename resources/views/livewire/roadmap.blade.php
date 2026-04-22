<div class="bg-gray-50 min-h-screen">
    
    {{-- Tambahkan Style Animasi Custom --}}
    <style>
        /* Animasi Garis Timeline Tumbuh */
        @keyframes growDown {
            from { height: 0; }
            to { height: 100%; }
        }
        .animate-grow-down {
            animation: growDown 1s ease-out forwards;
        }

        /* Animasi Fade In Up */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0; /* Mulai invisible */
        }
    </style>

    {{-- Mobile Header --}}
    <div class="lg:hidden p-4 border-b flex items-center sticky top-0 bg-white/90 backdrop-blur-md z-20 shadow-sm transition-all">
        <label for="main-drawer" class="btn btn-square btn-ghost btn-sm mr-2">
            <x-icon name="o-bars-3" class="w-6 h-6" />
        </label>
        <span class="font-bold">Roadmap</span>
    </div>

    <div class="max-w-4xl mx-auto py-12 px-6">
        
        {{-- HEADER (Animasi Masuk) --}}
        <div class="text-center mb-10 animate-fade-in-up">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-4 tracking-tight">Peta Jalan Produk</h1>
            <p class="text-gray-500 text-lg">Lihat apa yang sedang kami kerjakan dan apa yang akan datang.</p>
        </div>

        {{-- TABS FILTER --}}
        <div class="flex flex-wrap justify-center gap-2 mb-10 animate-fade-in-up delay-100" style="animation-delay: 100ms; animation-fill-mode: forwards;">
            @foreach($tabs as $key => $label)
                <button 
                    wire:click="setFilter('{{ $key }}')"
                    class="px-5 py-2 rounded-full text-sm font-bold transition-all duration-300 transform active:scale-95
                    {{ $filter === $key 
                        ? 'bg-indigo-600 text-white shadow-lg ring-2 ring-offset-2 ring-indigo-500 scale-105' 
                        : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 hover:border-indigo-200' 
                    }}"
                >
                    {{ $label }}
                </button>
            @endforeach
        </div>

        {{-- LIST ROADMAP --}}
        {{-- wire:loading.class menambhakan opacity saat loading filter --}}
        <div class="space-y-6 relative transition-opacity duration-300" wire:loading.class="opacity-50">
            
            {{-- Garis Vertikal (Timeline effect - Animated) --}}
            <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200 hidden md:block overflow-hidden rounded-full">
                {{-- Inner Line yang tumbuh --}}
                <div class="w-full bg-indigo-200 animate-grow-down origin-top"></div>
            </div>

            @forelse($roadmaps as $index => $item)
                {{-- Gunakan $loop->index untuk staggered animation --}}
                <div 
                    wire:key="item-{{ $item->id }}" 
                    class="relative pl-0 md:pl-12 group animate-fade-in-up"
                    style="animation-delay: {{ ($index * 100) + 200 }}ms; animation-fill-mode: forwards;"
                >
                    
                    {{-- Dot Timeline (Animated on Hover) --}}
                    <div class="absolute left-0 top-6 w-8 h-8 rounded-full border-4 border-white shadow-sm flex items-center justify-center z-10 hidden md:flex transition-all duration-300 group-hover:scale-110 group-hover:shadow-md
                        {{ $item->status == 'done' ? 'bg-green-500 ring-4 ring-green-100' : ($item->status == 'in_progress' ? 'bg-yellow-500 ring-4 ring-yellow-100' : 'bg-gray-400') }}">
                        @if($item->status == 'done')
                            <x-icon name="o-check" class="w-4 h-4 text-white" />
                        @elseif($item->status == 'in_progress')
                            <x-icon name="o-arrow-path" class="w-4 h-4 text-white animate-spin" style="animation-duration: 3s;" />
                        @else
                            <div class="w-2 h-2 bg-white rounded-full"></div>
                        @endif
                    </div>

                    {{-- Card --}}
                    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 transform group-hover:-translate-y-1 group-hover:border-indigo-100">
                        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                            
                            {{-- Konten --}}
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    {{-- Badge Status Mobile (Only) --}}
                                    <span class="md:hidden px-2 py-0.5 rounded text-xs font-bold uppercase
                                        {{ $item->status == 'done' ? 'bg-green-100 text-green-700' : ($item->status == 'in_progress' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-600') }}">
                                        {{ $tabs[$item->status] ?? $item->status }}
                                    </span>
                                    
                                    <h3 class="text-xl font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $item->title }}</h3>
                                </div>
                                <p class="text-gray-600 leading-relaxed">{{ $item->description }}</p>
                            </div>

                            {{-- Tanggal ETA --}}
                            <div class="text-right shrink-0">
                                @if($item->eta)
                                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wide">Estimasi</span>
                                    <span class="text-sm font-semibold text-gray-800 bg-gray-50 px-3 py-1 rounded-lg inline-block mt-1 group-hover:bg-indigo-50 group-hover:text-indigo-700 transition-colors">
                                        {{ \Carbon\Carbon::parse($item->eta)->format('M Y') }}
                                    </span>
                                @else
                                    <span class="text-xs font-bold text-gray-300 uppercase">TBA</span>
                                @endif
                            </div>

                        </div>
                        
                        {{-- Badge Status Desktop (Bawah Card) --}}
                        <div class="mt-4 pt-4 border-t border-gray-50 md:flex items-center hidden">
                             <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide flex items-center gap-2 transition-colors
                                {{ $item->status == 'done' ? 'bg-green-50 text-green-700 group-hover:bg-green-100' : ($item->status == 'in_progress' ? 'bg-yellow-50 text-yellow-700 group-hover:bg-yellow-100' : 'bg-gray-50 text-gray-600 group-hover:bg-gray-100') }}">
                                
                                {{-- Icon kecil di badge --}}
                                @if($item->status == 'in_progress') <x-icon name="o-clock" class="w-3 h-3 animate-pulse" /> @endif
                                @if($item->status == 'done') <x-icon name="o-check-circle" class="w-3 h-3" /> @endif
                                
                                {{ $tabs[$item->status] ?? $item->status }}
                            </span>
                        </div>

                    </div>
                </div>
            @empty
                <div class="text-center py-20 bg-white rounded-2xl border-dashed border-2 border-gray-200 animate-fade-in-up">
                    <x-icon name="o-map" class="w-12 h-12 text-gray-300 mx-auto mb-3 animate-bounce" style="animation-duration: 2s;" />
                    <p class="text-gray-500 font-bold">Belum ada item untuk status ini.</p>
                </div>
            @endforelse

        </div>

        <div class="mt-12 text-center text-gray-400 text-sm animate-fade-in-up" style="animation-delay: 500ms; animation-fill-mode: forwards; opacity: 0;">
            Roadmap dapat berubah sewaktu-waktu sesuai prioritas tim.
        </div>

    </div>
</div>