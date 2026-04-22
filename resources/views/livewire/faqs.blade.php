<div class="bg-gray-50 min-h-screen">
    
    {{-- Custom Animation Styles --}}
    <style>
        @keyframes fadeDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-down {
            animation: fadeDown 0.8s ease-out forwards;
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
            opacity: 0; /* Start invisible */
        }
    </style>

    {{-- Mobile Header --}}
    <div class="lg:hidden p-4 border-b flex items-center sticky top-0 bg-white/90 backdrop-blur-md z-20 transition-all shadow-sm">
        <label for="main-drawer" class="btn btn-square btn-ghost btn-sm mr-2">
            <x-icon name="o-bars-3" class="w-6 h-6" />
        </label>
        <span class="font-bold text-gray-800">F.A.Q</span>
    </div>

    <div class="max-w-3xl mx-auto py-12 px-6">
        
        {{-- Header & Search (Animate Fade Down) --}}
        <div class="text-center mb-10 animate-fade-down">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-4 tracking-tight">Pusat Bantuan</h1>
            <p class="text-gray-500 mb-8 text-lg">Cari jawaban untuk pertanyaan yang sering diajukan.</p>

            {{-- Kolom Pencarian --}}
            <div class="relative max-w-lg mx-auto group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <x-icon name="o-magnifying-glass" class="w-5 h-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors duration-300" />
                </div>
                <input 
                    wire:model.live.debounce.300ms="search" 
                    type="text" 
                    class="block w-full pl-11 pr-4 py-3.5 border-gray-200 rounded-xl leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-300 shadow-sm hover:shadow-md" 
                    placeholder="Cari pertanyaan... (misal: pembayaran)"
                >
                {{-- Spinner Loading Search --}}
                <div wire:loading wire:target="search" class="absolute inset-y-0 right-4 flex items-center">
                    <x-icon name="o-arrow-path" class="w-5 h-5 text-indigo-500 animate-spin" />
                </div>
            </div>
        </div>

        {{-- List FAQ (Staggered Animation) --}}
        {{-- Add wire:loading.class to dim the list while searching --}}
        <div class="space-y-4 transition-opacity duration-300" wire:loading.class="opacity-50">
            @forelse($faqs as $index => $faq)
                {{-- Item Accordion --}}
                <div 
                    x-data="{ open: false }" 
                    class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-lg hover:border-indigo-200 transition-all duration-300 animate-fade-in-up"
                    style="animation-delay: {{ $index * 100 }}ms; animation-fill-mode: forwards;"
                >
                    
                    {{-- Tombol Pertanyaan --}}
                    <button 
                        @click="open = !open" 
                        class="w-full text-left px-6 py-5 flex justify-between items-start focus:outline-none bg-white transition-colors duration-200"
                        :class="{'bg-indigo-50/50': open}"
                    >
                        <div class="pr-4">
                            {{-- Badge Kategori --}}
                            @if($faq->category)
                                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 mb-1.5 block">
                                    {{ $faq->category }}
                                </span>
                            @endif
                            {{-- Question Title - Change color on open --}}
                            <h3 class="text-lg font-bold text-gray-800 transition-colors duration-200" :class="{'text-indigo-700': open}">
                                {{ $faq->question }}
                            </h3>
                        </div>
                        
                        {{-- Icon Panah --}}
                        <span 
                            class="transform transition-transform duration-300 text-gray-400 shrink-0 mt-1"
                            :class="{'rotate-180 text-indigo-500': open}"
                        >
                            <x-icon name="o-chevron-down" class="w-5 h-5" />
                        </span>
                    </button>

                    {{-- Isi Jawaban (Smooth Collapse Animation) --}}
                    <div 
                        x-show="open" 
                        x-collapse 
                        x-cloak 
                        class="border-t border-gray-100 bg-gray-50/50"
                    >
                        <div class="px-6 pb-6 pt-4 text-gray-600 prose prose-indigo max-w-none leading-relaxed">
                            {!! $faq->answer !!}
                        </div>
                    </div>
                </div>
            @empty
                {{-- State Kosong --}}
                <div class="text-center py-12 animate-fade-in-up">
                    <div class="inline-block p-4 rounded-full bg-gray-100 mb-4 animate-bounce" style="animation-duration: 3s;">
                        <x-icon name="o-question-mark-circle" class="w-12 h-12 text-gray-400" />
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Tidak ditemukan</h3>
                    <p class="text-gray-500">Kami tidak dapat menemukan jawaban untuk pencarian Anda.</p>
                </div>
            @endforelse
        </div>

        {{-- Footer Kontak (Fade in last) --}}
        <div class="mt-16 text-center animate-fade-in-up delay-300" style="animation-delay: 500ms; animation-fill-mode: forwards; opacity: 0;">
            <p class="text-gray-500">
                Masih butuh bantuan? 
                <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500 hover:underline transition-all">Hubungi Support</a>
            </p>
        </div>

    </div>
</div>