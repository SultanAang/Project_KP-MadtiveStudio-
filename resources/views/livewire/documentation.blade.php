<div>
    {{-- Layout Utama: Sidebar (Menu) + Content (Isi) --}}
    <x-main full-width>
        
        {{-- SIDEBAR KIRI --}}
        <x-slot:sidebar class="bg-white border-r border-gray-200 w-80">
            
            {{-- Header Sidebar --}}
            <div class="p-6 sticky top-0 bg-white z-10">
                <div class="flex items-center gap-3 mb-6">
                    {{-- Logo Perusahaan (Ganti src gambar) --}}
                    <div class="avatar placeholder">
                        <div class="bg-indigo-600 text-white rounded w-10">
                            <span class="text-xl font-bold">M</span>
                        </div>
                    </div>
                    <div>
                        <h2 class="font-bold text-gray-800 leading-tight">Madtive Docs</h2>
                        <p class="text-[10px] uppercase font-bold text-indigo-500 tracking-wider">Client Portal</p>
                    </div>
                </div>

                {{-- Search Bar --}}
                <x-input 
                    wire:model.live="search" 
                    placeholder="Cari topik..." 
                    icon="o-magnifying-glass" 
                    class="bg-gray-50 border-transparent focus:bg-white transition" 
                    clearable 
                />
            </div>

            {{-- Menu Navigasi --}}
            <x-menu class="px-3 pb-20">
                @foreach($this->menu as $item)
                    @if(isset($item['subs']))
                        {{-- Menu Dropdown --}}
                        <x-menu-sub title="{{ $item['title'] }}" icon="{{ $item['icon'] }}">
                            @foreach($item['subs'] as $sub)
                                <x-menu-item 
                                    title="{{ $sub['title'] }}" 
                                    link="{{ route('docs', $sub['slug']) }}"
                                    class="text-sm {{ $slug === $sub['slug'] ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600' }}"
                                />
                            @endforeach
                        </x-menu-sub>
                    @else
                        {{-- Menu Tunggal --}}
                        <x-menu-item 
                            title="{{ $item['title' ?? 'Tahu Bulat'] }}" 
                            icon="{{ $item['icon'] }}" 
                            link="{{ route('docs', $item['slug']) }}"
                            class="{{ $slug === $item['slug'] ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600' }}"
                        />
                    @endif
                @endforeach

                @if(empty($this->menu))
                    <div class="text-center text-sm text-gray-400 py-4 mt-4 bg-gray-50 rounded-lg border border-dashed">
                        Topik tidak ditemukan.
                    </div>
                @endif
            </x-menu>
        </x-slot:sidebar>


        {{-- KONTEN KANAN --}}
        <x-slot:content class="bg-gray-50/50 min-h-screen !p-0">
            
            {{-- Navbar Mobile (Hanya muncul di HP) --}}
            <div class="lg:hidden p-4 bg-white border-b flex items-center justify-between sticky top-0 z-20 shadow-sm">
                <div class="flex items-center gap-2">
                    <label for="main-drawer" class="btn btn-square btn-ghost btn-sm">
                        <x-icon name="o-bars-3" class="w-6 h-6" />
                    </label>
                    <span class="font-bold text-gray-800">Dokumentasi</span>
                </div>
                {{-- Tombol Logout Mobile --}}
                {{-- {{ route('logout') }} --}}
                <a href="" class="btn btn-ghost btn-sm text-red-500">Keluar</a>
            </div>

            {{-- Wrapper Konten --}}
            <div class="max-w-4xl mx-auto p-6 lg:p-12">
                
                {{-- Breadcrumb --}}
                <div class="text-xs font-medium text-gray-400 mb-8 uppercase tracking-wide flex items-center gap-2">
                    <span>Docs</span>
                    <x-icon name="o-chevron-right" class="w-3 h-3" />
                    <span class="text-indigo-600">{{ $this->content['title'] }}</span>
                </div>

                {{-- Artikel --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 lg:p-12">
                    
                    <header class="mb-10 pb-8 border-b border-gray-100">
                        <h1 class="text-3xl lg:text-4xl font-extrabold text-gray-900 mb-4 tracking-tight">
                            {{ $this->content['title'] }}
                        </h1>
                        <p class="text-lg text-gray-500 font-light">
                            {{ $this->content['desc'] }}
                        </p>
                    </header>

                    {{-- Isi HTML --}}
                    {{-- Pastikan install @tailwindcss/typography agar class 'prose' bekerja --}}
                    <article class="prose prose-indigo prose-lg max-w-none text-gray-600 leading-loose">
                        {!! $this->content['content'] !!}
                    </article>

                </div>

                {{-- Footer Konten --}}
                <div class="mt-12 flex justify-between items-center text-sm text-gray-500 border-t pt-8">
                    <div>&copy; {{ date('Y') }} Madtive Studio.</div>
                    
                    {{-- Tombol Navigasi Bantuan --}}
                    <div class="flex gap-4">
                        {{-- <a href="{{ route('docs.index', 'faq') }}" class="hover:text-indigo-600 transition">FAQ</a> --}}
                        {{-- <a href="{{ route('docs.index', 'support') }}" class="hover:text-indigo-600 transition">Hubungi Support</a> --}}
                    </div>
                </div>

            </div>

        </x-slot:content>
    </x-main>
</div>