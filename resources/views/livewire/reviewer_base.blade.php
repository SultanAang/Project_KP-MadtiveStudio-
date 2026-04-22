<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

    {{-- Custom Styles & Animation --}}
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(-10px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.4s ease-out forwards;
            opacity: 0;
        }
        .animate-slide-in {
            animation: slideInRight 0.3s ease-out forwards;
        }
        /* Transisi halus untuk semua properti */
        .transition-all-smooth {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>

    {{-- HEADER DASHBOARD (Animate Entry) --}}
    <div class="mb-8 border-b border-gray-200 pb-4 flex flex-col md:flex-row md:justify-between md:items-end gap-4 animate-slide-in">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2 group cursor-default">
                <span class="transform group-hover:scale-110 transition-transform duration-300"></span> Ruang Persetujuan
            </h1>
            <p class="text-gray-600 mt-2">
                Dashboard validasi dokumentasi untuk: 
                <span class="font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded transition-colors duration-300">{{ ucfirst($activeTab) }}</span>
            </p>
        </div>

        {{-- BAGIAN USER & LOGOUT --}}
        <div class="flex items-center gap-3">
            {{-- Info User --}}
            <div class="text-sm text-gray-500 bg-gray-50 px-4 py-2 rounded-lg border border-gray-200 hover:border-indigo-200 hover:shadow-sm transition-all duration-300">
                Halo, <span class="font-bold text-gray-800">{{ auth()->user()->name }}</span>
            </div>

            {{-- Tombol Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button 
                    type="submit" 
                    class="flex items-center justify-center p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-all duration-200 transform hover:scale-105 active:scale-95"
                    title="Keluar Aplikasi"
                >
                    <x-icon name="o-power" class="w-6 h-6" />
                </button>
            </form>
        </div>
    </div>

    {{-- ALERT NOTIFIKASI (Bounce In) --}}
    @if (session()->has('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r shadow-sm flex items-center gap-3 animate-fade-in-up">
            <div class="bg-green-100 p-1.5 rounded-full">
                <x-icon name="o-check-circle" class="w-6 h-6 text-green-600" />
            </div>
            <div>
                <p class="font-bold">Berhasil!</p>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    {{-- TABS NAVIGASI --}}
    <div class="flex space-x-1 bg-gray-100 p-1 rounded-xl mb-6 overflow-x-auto shadow-inner scrollbar-hide animate-fade-in-up delay-100" style="animation-delay: 100ms; animation-fill-mode: forwards;">
        @php
            $tabs = [
                'release' => 'Release Notes', 
                'roadmap' => 'Roadmap', 
                'faq' => 'FAQ', 
                'knowledge' => 'Panduan',
                'history' => 'Riwayat'
            ];
        @endphp

        @foreach($tabs as $key => $label)
            <button 
                wire:click="setTab('{{ $key }}')"
                class="flex-1 py-2.5 px-4 rounded-lg text-sm font-medium transition-all duration-300 flex items-center justify-center gap-2 whitespace-nowrap relative overflow-hidden group
                {{ $activeTab === $key 
                    ? 'bg-white text-indigo-700 shadow-md ring-1 ring-gray-200 scale-[1.02]' 
                    : 'text-gray-500 hover:text-gray-700 hover:bg-gray-200/80' }}"
            >
                {{-- Icon with spin/bounce effect on hover --}}
                <div class="{{ $activeTab === $key ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600' }} transition-colors">
                    @if($key == 'release') <x-icon name="o-rocket-launch" class="w-4 h-4" />
                    @elseif($key == 'roadmap') <x-icon name="o-map" class="w-4 h-4" />
                    @elseif($key == 'faq') <x-icon name="o-question-mark-circle" class="w-4 h-4" />
                    @else <x-icon name="o-book-open" class="w-4 h-4" />
                    @endif
                </div>

                <span class="relative z-10">{{ $label }}</span>
                
                {{-- Counter Badge (Pulse Effect) --}}
                {{-- Counter Badge (Pulse Effect) --}}
                @if(isset($counts[$key]) && $counts[$key] > 0)
                    <span class="inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1.5 text-xs font-bold text-red-100 bg-red-600 rounded-full transition-transform duration-300 {{ $activeTab !== $key ? 'scale-90 group-hover:scale-100' : '' }}">
                        {{ $counts[$key] }}
                    </span>
                @endif
            </button>
        @endforeach
    </div>

    {{-- LIST ITEM ANTRIAN --}}
    {{-- Container ini akan transparan saat loading (ganti tab) --}}
    <div class="space-y-4 min-h-[300px] transition-opacity duration-300" wire:loading.class="opacity-50">
        @forelse($items as $index => $item)
            
            @if($activeTab == 'history')
                {{-- TAMPILAN CARD KHUSUS RIWAYAT --}}
                <div wire:key="history-{{ $item->id }}" wire:click="showDetail('{{ $item->document_type ?? 'history' }}', {{ $item->id }})" class="group bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all cursor-pointer relative overflow-hidden mb-4 animate-fade-in-up">
                    {{-- Garis warna status (Hijau = Disetujui, Merah = Ditolak) --}}
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $item->is_approve === 'published' ? 'bg-green-500' : 'bg-red-500' }}"></div>

                    <div class="p-5 pl-7 flex flex-col md:flex-row gap-6 items-center">
                        <div class="flex-1 w-full">
                            <div class="flex items-center gap-3 mb-2 text-xs text-gray-500">
                                <span class="px-2 py-0.5 rounded font-bold bg-gray-100 text-gray-600 uppercase">{{ $item->document_type ?? 'Dokumen' }}</span>
                                <span><x-icon name="o-cube" class="w-3 h-3 inline"/> {{ $item->project->name ?? 'Project Umum' }}</span>
                                <span><x-icon name="o-clock" class="w-3 h-3 inline"/> {{ $item->updated_at->format('d M Y') }}</span>
                            </div>

                            <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors">{{ $item->title ?? $item->question ?? $item->task_name }}</h3>

                            @if($item->is_approve === 'rejected')
                                <div class="mt-2 text-sm text-red-600 bg-red-50 px-3 py-2 rounded-lg border border-red-100 max-w-2xl">
                                    <span class="font-bold">Alasan Penolakan:</span> {{ $item->rejection_note }}
                                </div>
                            @endif
                        </div>

                        <div class="flex-shrink-0 min-w-[140px] text-right">
                            @if($item->is_approve === 'published')
                                <span class="px-3 py-2 rounded-lg text-sm font-bold bg-green-50 text-green-700 border border-green-200"><x-icon name="o-check" class="w-4 h-4 inline mr-1"/> Disetujui</span>
                            @else
                                <span class="px-3 py-2 rounded-lg text-sm font-bold bg-red-50 text-red-700 border border-red-200"><x-icon name="o-x-mark" class="w-4 h-4 inline mr-1"/> Ditolak</span>
                            @endif
                        </div>
                    </div>
                </div>

            @else
                {{-- KODE ASLI KAMU (MODE DRAFT) --}}
                {{-- Staggered Animation Item --}}
                <div 
                    wire:key="item-{{ $item->id }}" 
                    wire:click="showDetail('{{ $activeTab }}', {{ $item->id }})"
                    class="group bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-lg hover:border-indigo-300 transition-all-smooth cursor-pointer relative overflow-hidden transform hover:-translate-y-1 animate-fade-in-up"
                    style="animation-delay: {{ $index * 50 + 200 }}ms; animation-fill-mode: forwards;"
                >
                    {{-- Strip Status Kiri (Expand on Hover) --}}
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-gray-200 group-hover:bg-indigo-500 group-hover:w-1.5 transition-all duration-300"></div>

                    <div class="p-5 pl-7 flex flex-col md:flex-row gap-6">
                        
                        {{-- KONTEN UTAMA --}}
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                {{-- Badge Project --}}
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100 group-hover:bg-blue-100 transition-colors">
                                    <x-icon name="o-cube" class="w-3 h-3 mr-1"/>
                                    {{ $item->project->name ?? 'Project Umum' }}
                                </span>
                                {{-- Badge Drafter (BARU) --}}
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-50 text-gray-600 border border-gray-200">
                                    <x-icon name="o-user" class="w-3 h-3 mr-1"/>
                                    {{ $item->author->name ?? 'Sistem' }}
                                </span>
                                <span class="text-xs text-gray-400 flex items-center gap-1">
                                    <x-icon name="o-clock" class="w-3 h-3"/>
                                    {{ $item->created_at->diffForHumans() }}
                                </span>
                            </div>

                            {{-- Judul Item --}}
                            <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors duration-300">
                                @if($activeTab == 'release') 
                                    <span class="bg-indigo-100 text-indigo-700 px-1.5 py-0.5 rounded text-sm mr-1 font-mono transition-colors group-hover:bg-indigo-200">v{{ $item->version }}</span> 
                                    {{ $item->title ?? 'Release Note Tanpa Judul' }}
                                @elseif($activeTab == 'faq') 
                                    <span class="text-gray-400 font-normal mr-1 group-hover:text-indigo-400">Q:</span> {{ $item->question }}
                                @elseif($activeTab == 'roadmap') 
                                    {{ $item->task_name ?? $item->title }}
                                @else 
                                    {{ $item->title }}
                                @endif
                            </h3>

                            {{-- Cuplikan Isi --}}
                            <div class="prose prose-sm text-gray-500 max-w-none line-clamp-2 leading-relaxed group-hover:text-gray-600 transition-colors">
                                {!! Str::limit(strip_tags($item->description ?? $item->answer ?? $item->content ?? $item->intro_text ?? ''), 220) !!}
                            </div>

                            {{-- "Buka detail" dengan animasi panah --}}
                            <div class="mt-3 text-xs text-indigo-600 font-semibold flex items-center opacity-0 transform translate-y-2 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300">
                                Buka detail selengkapnya <x-icon name="o-arrow-right" class="w-3 h-3 ml-1 group-hover:translate-x-1 transition-transform"/>
                            </div>
                        </div>

                        {{-- TOMBOL AKSI CEPAT (Hover Reveal/Highlight) --}}
                        <div class="flex flex-row md:flex-col gap-2 justify-center items-center min-w-[140px] border-t md:border-t-0 md:border-l border-gray-100 pt-4 md:pt-0 md:pl-6 opacity-90 group-hover:opacity-100 transition-opacity">
                            <button 
                                wire:click.stop="approve('{{ $activeTab }}', {{ $item->id }})"
                                wire:confirm="Yakin ingin menerbitkan konten ini sekarang?"
                                class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-all shadow-sm hover:shadow-md transform active:scale-95"
                            >
                                <x-icon name="o-check" class="w-4 h-4 mr-2"/> Setujui
                            </button>
                            
                            <button 
                                wire:click.stop="confirmReject('{{ $activeTab }}', '{{ $item->id }}')"
                                class="w-full flex items-center justify-center px-4 py-2 bg-white text-gray-700 border border-gray-300 text-sm font-medium rounded-lg hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition-all transform active:scale-95"
                            >
                                <x-icon name="o-x-mark" class="w-4 h-4 mr-2"/> Tolak
                            </button>
                        </div>
                    </div>
                </div>
            @endif

        @empty
            {{-- Empty State (Fade In) --}}
            <div class="flex flex-col items-center justify-center py-20 bg-white rounded-xl border-2 border-dashed border-gray-200 animate-fade-in-up">
                <div class="bg-gray-50 p-4 rounded-full mb-4 animate-bounce" style="animation-duration: 3s;">
                    <x-icon name="{{ $activeTab == 'history' ? 'o-clock' : 'o-check-badge' }}" class="w-12 h-12 {{ $activeTab == 'history' ? 'text-indigo-400' : 'text-green-500' }}" />
                </div>
                <h3 class="text-lg font-medium text-gray-900">
                    {{ $activeTab == 'history' ? 'Riwayat Kosong' : 'Semua Bersih!' }}
                </h3>
                <p class="text-gray-500 text-sm mt-1">
                    {{ $activeTab == 'history' ? 'Belum ada dokumen yang disetujui atau ditolak.' : 'Tidak ada dokumen ' . ucfirst($activeTab) . ' yang perlu direview.' }}
                </p>
            </div>
        @endforelse
    </div>

    {{-- ================================================= --}}
    {{-- MODAL 1: DETAIL KONTEN (READ MODE) --}}
    {{-- ================================================= --}}
    {{-- Pastikan komponen x-modal mendukung transition classes atau gunakan library modal bawaan yang sudah ada animasinya --}}
    {{-- ================================================= --}}
    {{-- MODAL 1: DETAIL KONTEN --}}
    {{-- ================================================= --}}
    <x-modal wire:model="showDetailModal" title="Tinjau Dokumen" class="backdrop-blur-sm transition-opacity duration-300" box-class="w-11/12 max-w-5xl transform transition-all duration-300 scale-95">
        @if($selectedItem)
            @php
                // Cek ini dokumen apa berdasarkan class modelnya (Faq, Release, Roadmap, dll)
                $modelClass = class_basename($selectedItem); 
            @endphp

            <div class="flex flex-col h-full animate-fade-in-up" style="animation-duration: 0.3s">
                
                {{-- Banner Status (Hanya muncul saat buka lewat History) --}}
                @if($activeTab == 'history')
                    <div class="mb-4 p-4 rounded-lg flex items-start gap-3 border {{ $selectedItem->is_approve === 'published' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800' }}">
                        <x-icon name="{{ $selectedItem->is_approve === 'published' ? 'o-check-circle' : 'o-x-circle' }}" class="w-6 h-6 flex-shrink-0" />
                        <div>
                            <h4 class="font-bold">
                                {{ $selectedItem->is_approve === 'published' ? 'Dokumen Telah Disetujui' : 'Dokumen Ditolak / Dikembalikan' }}
                            </h4>
                            @if($selectedItem->is_approve === 'rejected')
                                <p class="text-sm mt-1 font-medium">Catatan: {{ $selectedItem->rejection_note }}</p>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Header Detail --}}
                <div class="flex justify-between items-start border-b border-gray-100 pb-4 mb-4">
                    <div>
                        <span class="text-xs font-bold tracking-wider text-gray-400 uppercase mb-1 block">
                            {{ $activeTab == 'history' ? $modelClass : ucfirst($activeTab) }}
                        </span>
                        <h2 class="text-2xl font-bold text-gray-900 leading-tight">
                            @if($modelClass == 'Release') 
                                <span class="text-indigo-600 mr-2">v{{ $selectedItem->version }}</span> {{ $selectedItem->title }}
                            @elseif($modelClass == 'Faq') 
                                {{ $selectedItem->question }}
                            @elseif($modelClass == 'Roadmap') 
                                {{ $selectedItem->task_name ?? $selectedItem->title }}
                            @else 
                                {{ $selectedItem->title }}
                            @endif
                        </h2>
                    </div>
                    <div class="text-right pl-4">
                        <div class="bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1 rounded-full inline-block mb-1">
                            {{ $selectedItem->project->name ?? 'Global' }}
                        </div>
                        <div class="text-xs text-gray-500 font-medium mt-1 flex items-center justify-end gap-1">
                            <x-icon name="o-pencil-square" class="w-3.5 h-3.5"/>
                            Oleh: <span class="text-gray-800 font-bold">{{ $selectedItem->author->name ?? 'Sistem' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Body Detail --}}
                <div class="flex-1 overflow-y-auto max-h-[60vh] pr-2 custom-scrollbar">
                    <div class="bg-gray-50/50 p-6 rounded-xl border border-gray-100">
                        
                        @if($modelClass == 'Faq')
                            <strong class="block text-gray-900 mb-2">Jawaban:</strong>
                            <div class="prose prose-sm max-w-none text-gray-600">
                                {!! $selectedItem->answer !!}
                            </div>
                            
                        @elseif($modelClass == 'Release')
                            {{-- Tampilan Khusus Release --}}
                            <div class="mb-6 text-gray-700 leading-relaxed prose prose-indigo max-w-none">
                                {!! $selectedItem->intro_text !!}
                            </div>

                            @if(is_array($selectedItem->features) && count($selectedItem->features) > 0)
                                <div class="border-t border-gray-200 pt-6 mt-6">
                                    <h4 class="font-bold text-gray-900 mb-4 uppercase tracking-wider text-sm flex items-center gap-2">
                                        <x-icon name="o-sparkles" class="w-5 h-5 text-indigo-500" />
                                        Yang Baru & Perbaikan
                                    </h4>
                                    <div class="grid grid-cols-1 gap-4">
                                        @foreach($selectedItem->features as $feature)
                                            <div class="flex gap-4 bg-white p-4 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                                                <div class="text-indigo-600 bg-indigo-50 p-2.5 rounded-lg h-fit shrink-0">
                                                    <x-icon name="{{ $feature['icon'] ?? 'o-check-circle' }}" class="w-6 h-6" />
                                                </div>
                                                <div>
                                                    <strong class="block text-gray-900 text-base mb-1">{{ $feature['title'] ?? '' }}</strong>
                                                    <p class="text-sm text-gray-500 leading-relaxed">{{ $feature['description'] ?? '' }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                        @elseif($modelClass == 'Roadmap')
                            {{-- Tampilan Khusus Roadmap --}}
                            <div class="grid grid-cols-3 gap-4 mb-6 not-prose">
                                <div class="bg-white p-3 rounded-lg border border-gray-100">
                                    <span class="text-xs text-gray-500 block uppercase font-bold">ETA Target</span>
                                    <span class="text-gray-900 font-semibold">{{ $selectedItem->eta ?? '-' }}</span>
                                </div>
                                <div class="bg-white p-3 rounded-lg border border-gray-100">
                                    <span class="text-xs text-gray-500 block uppercase font-bold">Progress</span>
                                    <span class="text-gray-900 font-semibold">{{ $selectedItem->progress ?? '0' }}%</span>
                                </div>
                                <div class="bg-white p-3 rounded-lg border border-gray-100">
                                    <span class="text-xs text-gray-500 block uppercase font-bold">Prioritas</span>
                                    <span class="text-gray-900 font-semibold capitalize">{{ $selectedItem->priority ?? 'Normal' }}</span>
                                </div>
                            </div>
                            <div class="prose prose-sm max-w-none text-gray-600">
                                {!! $selectedItem->description ?? $selectedItem->content !!}
                            </div>

                        @else
                            {{-- Tampilan Default (Knowledge Base / Lainnya) --}}
                            <div class="prose prose-sm max-w-none text-gray-600">
                                {!! $selectedItem->description ?? $selectedItem->content !!}
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        @endif
        
        <x-slot:actions>
            <div class="flex justify-between w-full pt-4 border-t border-gray-100">
                <x-button label="Tutup" @click="$wire.showDetailModal = false" class="btn-ghost hover:bg-gray-100 transition-colors" />
                
                @if($selectedItem && $activeTab !== 'history')
                    <div class="flex gap-2">
                        <x-button label="Tolak Revisi" class="btn-outline btn-error hover:scale-105" icon="o-x-mark" wire:click="confirmReject('{{ $activeTab }}', '{{ $selectedItem->id }}')" />
                        <x-button label="Setujui Dokumen" class="bg-green-600 hover:bg-green-700 text-white border-none hover:scale-105 shadow-md" icon="o-check" wire:click="approve('{{ $activeTab }}', {{ $selectedItem->id }})" />
                    </div>
                @endif
            </div>
        </x-slot:actions>
    </x-modal>

    {{-- ================================================= --}}
    {{-- MODAL 2: FORM REJECT (INPUT ALASAN) --}}
    {{-- ================================================= --}}
    <x-modal 
        wire:model="showRejectModal" 
        title="Konfirmasi Penolakan" 
        subtitle="Mohon berikan alasan yang jelas agar drafter dapat memperbaikinya."
    >
        <x-form wire:submit="submitReject" class="animate-fade-in-up" style="animation-duration: 0.3s">
            
            <div class="bg-red-50 p-4 rounded-lg mb-4 border border-red-100 flex items-start gap-3 animate-pulse">
                <x-icon name="o-exclamation-triangle" class="w-6 h-6 text-red-500 flex-shrink-0" />
                <div class="text-sm text-red-700">
                    <p class="font-bold">Perhatian</p>
                    <p>Dokumen ini akan dikembalikan ke status <b>Draft</b> dan Drafter akan menerima notifikasi revisi.</p>
                </div>
            </div>

            <x-textarea 
                label="Catatan Revisi / Alasan Penolakan" 
                wire:model="rejectionNote" 
                placeholder="Contoh: Screenshot pada langkah ke-3 tidak muncul, mohon diperbaiki..." 
                rows="5"
                class="bg-white focus:ring-red-500 focus:border-red-500 transition-shadow"
                hint="Minimal 5 karakter."
            />
    
            <x-slot:actions>
                <x-button label="Batal" @click="$wire.showRejectModal = false" class="hover:bg-gray-100 transition-colors" />
                <x-button 
                    label="Kirim Penolakan" 
                    class="btn-error text-white hover:bg-red-700 hover:shadow-lg transition-all" 
                    type="submit" 
                    spinner="submitReject" 
                    icon="o-paper-airplane"
                />
            </x-slot:actions>
        </x-form>
    </x-modal>

</div>