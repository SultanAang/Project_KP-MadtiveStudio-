<div class="bg-white min-h-screen relative">
    {{-- Custom Animations --}}
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0; 
        }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
        .delay-400 { animation-delay: 400ms; }
    </style>

    {{-- Mobile Header --}}
    <div class="lg:hidden p-4 border-b flex items-center sticky top-0 bg-white/95 backdrop-blur-sm z-20 shadow-sm transition-all">
        <label for="main-drawer" class="btn btn-square btn-ghost btn-sm mr-2">
            <x-icon name="o-bars-3" class="w-6 h-6" />
        </label>
        <span class="font-bold">Lapor Bug</span>
    </div>

    <div class="max-w-4xl mx-auto py-12 px-8 lg:px-16">

        {{-- Alert Sukses --}}
        @if (session('success'))
            <div class="mb-8 p-5 rounded-xl bg-green-50 text-green-800 border border-green-200 flex items-start gap-4 shadow-sm animate-fade-in-up">
                <div class="bg-green-100 p-2 rounded-lg text-green-600 shrink-0">
                    <x-icon name="o-check-circle" class="w-6 h-6" /> 
                </div>
                <div>
                    <h3 class="font-bold text-green-900">Sukses!</h3>
                    <p class="text-sm mt-1">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        {{-- ========================================== --}}
        {{-- VIEW 1: FORM PELAPORAN BUG --}}
        {{-- ========================================== --}}
        @if($isFormView)
            <div class="mb-12 border-b border-gray-100 pb-8 animate-fade-in-up">
                <h1 class="text-4xl font-extrabold text-gray-900 mb-2 tracking-tight">Lapor Bug / Error</h1>
                <p class="text-gray-500 text-lg leading-relaxed">
                    Temukan kendala teknis? Laporkan di sini agar tim developer kami segera memperbaikinya.
                </p>
            </div>

            <form wire:submit="save" class="space-y-8">
                
                {{-- Input Judul --}}
                <div class="animate-fade-in-up delay-100 group" style="animation-fill-mode: forwards;">
                    <label class="block mb-3 text-sm font-bold text-gray-700 uppercase tracking-wider group-focus-within:text-indigo-600 transition-colors">Judul Masalah</label>
                    <input type="text" wire:model.blur="title" class="w-full bg-white border border-gray-200 text-gray-900 text-base rounded-xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 block p-4 shadow-sm placeholder-gray-400 transition-all duration-300 focus:shadow-md" placeholder="Contoh: Tidak bisa upload foto profil...">
                    @error('title') <p class="mt-2 text-sm text-red-600 animate-pulse">{{ $message }}</p> @enderror
                </div>

                {{-- Input Prioritas --}}
                <div class="animate-fade-in-up delay-200" style="animation-fill-mode: forwards;">
                    <label class="block mb-3 text-sm font-bold text-gray-700 uppercase tracking-wider">Tingkat Prioritas</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach(['low' => ['label' => 'Ringan', 'color' => 'bg-green-100 text-green-600'], 'medium' => ['label' => 'Sedang', 'color' => 'bg-yellow-100 text-yellow-600'], 'high' => ['label' => 'Penting', 'color' => 'bg-orange-100 text-orange-600'], 'critical' => ['label' => 'Kritis', 'color' => 'bg-red-100 text-red-600']] as $value => $props)
                            <label class="cursor-pointer relative group">
                                <input type="radio" wire:model="priority" value="{{ $value }}" class="peer sr-only">
                                <div class="p-4 rounded-xl border border-gray-200 bg-white shadow-sm hover:shadow-md transition-all duration-200 peer-checked:border-indigo-500 peer-checked:ring-2 peer-checked:ring-indigo-500 peer-checked:bg-indigo-50/30 peer-checked:scale-105 flex flex-col items-center justify-center gap-2 h-full">
                                    <div class="{{ $props['color'] }} p-2 rounded-lg shrink-0 transition-transform group-hover:scale-110">
                                        <div class="w-3 h-3 rounded-full bg-current"></div>
                                    </div>
                                    <span class="font-bold text-gray-700 peer-checked:text-indigo-900 transition-colors">{{ $props['label'] }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('priority') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Input Deskripsi --}}
                <div class="animate-fade-in-up delay-300 group" style="animation-fill-mode: forwards;">
                    <label class="block mb-3 text-sm font-bold text-gray-700 uppercase tracking-wider group-focus-within:text-indigo-600 transition-colors">Kronologi / Detail</label>
                    <textarea wire:model.blur="description" rows="6" class="w-full bg-white border border-gray-200 text-gray-900 text-base rounded-xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 block p-4 shadow-sm placeholder-gray-400 leading-relaxed transition-all duration-300 focus:shadow-md" placeholder="Jelaskan langkah-langkah yang Anda lakukan sebelum error muncul..."></textarea>
                    @error('description') <p class="mt-2 text-sm text-red-600 animate-pulse">{{ $message }}</p> @enderror
                </div>

                {{-- Input Bukti Screenshot --}}
                <div class="animate-fade-in-up delay-400" style="animation-fill-mode: forwards;">
                    <label class="block mb-3 text-sm font-bold text-gray-700 uppercase tracking-wider">Bukti Screenshot (Opsional)</label>
                    <div class="w-full">
                        <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-2xl cursor-pointer bg-gray-50 hover:bg-gray-100 hover:border-indigo-400 transition-all duration-300 group relative overflow-hidden">
                            @if ($screenshot)
                                <img src="{{ $screenshot->temporaryUrl() }}" class="w-full h-full object-contain rounded-xl shadow-sm z-10">
                                <div class="absolute inset-0 bg-white/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity z-20">
                                    <span class="bg-white px-4 py-2 rounded-full text-sm font-bold shadow-lg">Ganti Gambar</span>
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 text-gray-400 group-hover:text-indigo-600 transition-colors z-10">
                                    <x-icon name="o-photo" class="w-12 h-12 mb-3 group-hover:scale-110 transition-transform duration-300" />
                                    <p class="mb-2 text-sm font-semibold">Klik untuk upload bukti error</p>
                                    <p class="text-xs">PNG, JPG (MAX. 2MB)</p>
                                </div>
                            @endif
                            <input id="dropzone-file" type="file" wire:model="screenshot" class="hidden" accept="image/*" />
                        </label>
                    </div>
                    <div wire:loading wire:target="screenshot" class="mt-2 text-indigo-600 text-sm font-medium animate-pulse">Sedang mengupload gambar...</div>
                    @error('screenshot') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="border-t border-gray-100 my-8"></div>

                {{-- Tombol Submit & Cancel --}}
                <div class="flex justify-end gap-3 animate-fade-in-up" style="animation-delay: 500ms; animation-fill-mode: forwards;">
                    {{-- Tombol Batal HANYA muncul jika klien sudah punya laporan lain --}}
                    @if($hasReports)
                        <button type="button" wire:click="cancelReport" class="w-full md:w-auto px-6 py-4 rounded-xl font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors">
                            Batal
                        </button>
                    @endif
                    
                    <button type="submit" wire:loading.attr="disabled" class="w-full md:w-auto text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200 font-bold rounded-xl text-lg px-8 py-4 shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-3 disabled:opacity-50">
                        <span wire:loading.remove>Kirim Laporan</span>
                        <span wire:loading>Mengirim...</span>
                    </button>
                </div>
            </form>

        {{-- ========================================== --}}
        {{-- VIEW 2: LIST RIWAYAT LAPORAN BUG --}}
        {{-- ========================================== --}}
        @else
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4 animate-fade-in-up">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Riwayat Laporan</h1>
                    <p class="text-gray-500 mt-1">Pantau status laporan kendala teknis Anda di sini.</p>
                </div>
                <button wire:click="createNewReport" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-xl shadow-md transition-all flex items-center gap-2 transform hover:-translate-y-1">
                    <x-icon name="o-plus" class="w-5 h-5" /> Buat Laporan Baru
                </button>
            </div>

            <div class="space-y-4 animate-fade-in-up delay-100" style="animation-fill-mode: forwards;">
                @forelse($reports as $report)
                    <div class="p-6 rounded-2xl border border-gray-200 bg-white hover:shadow-md transition-shadow flex flex-col md:flex-row justify-between items-start md:items-center gap-4 group">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <span class="text-xs font-bold px-3 py-1 rounded-full uppercase
                                    {{ $report->status === 'resolved' ? 'bg-green-100 text-green-700' : 
                                      ($report->status === 'process' ? 'bg-blue-100 text-blue-700' : 
                                      ($report->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700')) }}">
                                    {{ $report->status }}
                                </span>
                                <span class="text-sm text-gray-400">{{ $report->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $report->title }}</h3>
                        </div>
                        <button wire:click="viewDetails({{ $report->id }})" class="text-indigo-600 bg-indigo-50 hover:bg-indigo-100 px-5 py-2.5 rounded-lg font-semibold text-sm transition-colors shrink-0">
                            Lihat Detail
                        </button>
                    </div>
                @empty
                    <div class="text-center py-12 bg-gray-50 rounded-2xl border border-gray-200 border-dashed">
                        <p class="text-gray-500">Belum ada riwayat laporan bug.</p>
                    </div>
                @endforelse
            </div>
        @endif

        {{-- Footer --}}
        <div class="mt-12 text-center text-xs text-gray-400">
            &copy; 2026 Madtive Studio.
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- VIEW 3: MODAL DETAIL TRACKING --}}
    {{-- ========================================== --}}
    @if($showModal && $selectedBug)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6">
            {{-- Backdrop --}}
            <div wire:click="closeModal" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>
            
            {{-- Modal Content --}}
            <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] animate-fade-in-up">
                
                {{-- Header Modal --}}
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white z-10">
                    <h2 class="text-xl font-bold text-gray-900">Detail Tiket #{{ $selectedBug->id }}</h2>
                    <button wire:click="closeModal" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-colors">
                        <x-icon name="o-x-mark" class="w-6 h-6" />
                    </button>
                </div>

                {{-- Body Modal --}}
                <div class="p-6 overflow-y-auto">
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $selectedBug->title }}</h3>
                        <p class="text-gray-600 whitespace-pre-line leading-relaxed">{{ $selectedBug->description }}</p>
                    </div>

                    {{-- Kotak Tanggapan Admin --}}
                    <div class="mb-6 p-5 rounded-2xl border {{ $selectedBug->admin_note ? 'border-indigo-200 bg-indigo-50' : 'border-gray-200 bg-gray-50' }}">
                        <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <x-icon name="o-shield-check" class="w-5 h-5 text-indigo-500" /> 
                            Status & Tanggapan Tim
                        </h4>
                        
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-sm font-bold text-gray-700">Status Saat Ini:</span>
                            <span class="text-xs font-bold px-3 py-1 rounded-full uppercase
                                {{ $selectedBug->status === 'resolved' ? 'bg-green-100 text-green-700' : 
                                  ($selectedBug->status === 'process' ? 'bg-blue-100 text-blue-700' : 
                                  ($selectedBug->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-gray-200 text-gray-700')) }}">
                                {{ $selectedBug->status }}
                            </span>
                        </div>

                        @if($selectedBug->admin_note)
                            <div class="mt-4 pt-4 border-t border-indigo-200/50">
                                <span class="text-sm font-bold text-gray-700 block mb-1">Catatan Developer:</span>
                                <p class="text-gray-800 italic">"{{ $selectedBug->admin_note }}"</p>
                            </div>
                        @else
                            <p class="text-gray-500 text-sm italic mt-2">Belum ada tanggapan dari tim developer. Tiket Anda sedang dalam antrean pengecekan.</p>
                        @endif
                    </div>

                    {{-- Lampiran Gambar --}}
                    @if($selectedBug->screenshot_path)
                        <div>
                            <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3">Lampiran Screenshot</h4>
                            <img src="{{ asset('storage/' . $selectedBug->screenshot_path) }}" alt="Bug Screenshot" class="w-full rounded-xl border border-gray-200 shadow-sm">
                        </div>
                    @endif
                </div>

                {{-- Footer Modal --}}
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end sticky bottom-0">
                    <button wire:click="closeModal" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-100 transition-colors shadow-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>