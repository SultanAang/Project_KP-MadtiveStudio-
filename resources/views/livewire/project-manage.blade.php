<div>
    {{-- HEADER --}}
    <x-header title="Manajemen Project" subtitle="Wadah dokumentasi aplikasi client">
        <x-slot:middle class="!justify-end">
            <x-input icon="o-magnifying-glass" wire:model.live.debounce.300ms="search" placeholder="Cari project..." />
        </x-slot:middle>
        <x-slot:actions>
            <x-button icon="o-plus" class="btn-primary" wire:click="create" label="Buat Project" />
        </x-slot:actions>
    </x-header>

    {{-- ALERT SUKSES --}}
    @if (session()->has('message'))
        <x-alert icon="o-check" class="alert-success mb-4">
            {{ session('message') }}
        </x-alert>
    @endif

    {{-- ALERT PASSWORD BARU (INFO CLIENT BARU) --}}
    @if (session()->has('new_account_info'))
        <div role="alert" class="alert alert-info mb-4 shadow-lg">
            <x-icon name="o-information-circle" class="w-6 h-6" />
            <div>
                <h3 class="font-bold">Akun Client Baru Dibuat!</h3>
                <div class="text-xs">{{ session('new_account_info') }}</div>
                <div class="text-xs mt-1 bg-white/20 p-1 rounded inline-block">
                    Simpan password ini dan kirimkan ke client.
                </div>
            </div>
        </div>
    @endif

    {{-- TABEL PROJECT --}}
    <x-card>
        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>Project</th>
                        <th>Client / Perusahaan</th>
                        <th>Status</th>
                        <th class="hidden md:table-cell">Deadline</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects as $project)
                    <tr wire:key="{{ $project->id }}">
                        {{-- KOLOM 1: INFO PROJECT --}}
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="avatar">
                                    <div class="mask mask-squircle w-12 h-12 bg-base-200 border border-base-300">
                                        @if($project->logo) {{-- Sesuaikan nama kolom dengan database Anda, misalnya $project->logo --}}
                                            <img src="{{ asset('storage/' . $project->logo) }}" alt="Logo" class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                        @else
                                            {{-- Tampilan jika tidak ada logo --}}
                                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 border border-gray-200">
                                                <x-icon name="o-photo" class="w-5 h-5" />
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <div class="font-bold">{{ $project->name }}</div>
                                    <div class="text-xs opacity-50">{{ $project->slug }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- KOLOM 2: INFO CLIENT (Perbaikan Disini) --}}
                        <td>
                            {{-- Menampilkan Company Name dari tabel clients --}}
                            <div class="font-medium">
                                {{ $project->client->company_name ?? 'Tanpa Perusahaan' }}
                            </div>
                            
                            {{-- Menampilkan Email User dari relasi client->user --}}
                            <div class="text-xs opacity-50">
                                {{-- Gunakan optional() untuk mencegah error jika user terhapus --}}
                                <x-icon name="o-envelope" class="w-3 h-3 inline" />
                                {{ $project->client?->user?->email ?? '-' }}
                            </div>
                        </td>

                        {{-- KOLOM 3: STATUS --}}
                        <td>
                            @php
                                $color = match($project->status) {
                                    'pending' => 'warning',  // MaryUI color names
                                    'ongoing' => 'info',
                                    'finished' => 'success',
                                    default => 'neutral'
                                };
                            @endphp
                            <x-badge :value="ucfirst($project->status)" class="badge-{{ $color }}" />
                        </td>

                        {{-- KOLOM 4: DEADLINE --}}
                        <td class="hidden md:table-cell">
                            @if($project->deadline)
                                <div class="flex items-center gap-1">
                                    <x-icon name="o-calendar" class="w-4 h-4 text-gray-400" />
                                    {{ \Carbon\Carbon::parse($project->deadline)->format('d M Y') }}
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        {{-- KOLOM 5: AKSI --}}
                        <td class="text-right">
                            <div class="flex justify-end gap-1">
                                <x-button icon="o-pencil" wire:click="edit({{ $project->id }})" class="btn-ghost btn-sm text-blue-500" tooltip="Edit Project" />
                                <x-button icon="o-trash" wire:click="delete({{ $project->id }})" 
                                          wire:confirm="Yakin hapus project ini? Client yang tidak memiliki project lain juga akan dihapus." 
                                          class="btn-ghost btn-sm text-red-500" tooltip="Hapus" />
                            </div>
                        </td>
                    </tr>
                    @empty
                    {{-- EMPTY STATE --}}
                    <tr>
                        <td colspan="5">
                            <div class="text-center py-10">
                                <x-icon name="o-folder-open" class="w-12 h-12 text-gray-300 mx-auto mb-3" />
                                <div class="text-gray-500 font-bold">Belum ada project</div>
                                <div class="text-sm text-gray-400">Silakan buat project baru untuk memulai.</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- PAGINATION --}}
        <div class="mt-4">
            {{ $projects->links() }}
        </div>
    </x-card>

    {{-- MODAL FORM --}}
    {{-- ... bagian atas sama ... --}}

<x-modal wire:model="myModal" title="{{ $projectIdToEdit ? 'Edit Project' : 'Project Baru' }}" separator>
    
    <x-form wire:submit="save">
        
        @if(!$projectIdToEdit)
        <div class="bg-blue-50 p-3 rounded-lg text-sm text-blue-800 mb-2 flex gap-2">
            <x-icon name="o-light-bulb" class="w-5 h-5 shrink-0" />
            <span>
                Jika email belum terdaftar, sistem akan otomatis membuatkan <b>Akun Login</b>.
            </span>
        </div>
        @endif

        {{-- Input Email --}}
        <x-input label="Email Client" 
                 wire:model="client_email" 
                 icon="o-envelope" 
                 placeholder="contoh: client@perusahaan.com" 
                 hint="Email ini digunakan untuk login client nantinya." />

        {{-- [BARU] Input Nama Perusahaan --}}
        <x-input label="Nama Perusahaan" 
                 wire:model="company_name" 
                 icon="o-building-office" 
                 placeholder="Contoh: PT. Madtive Studio" />

        {{-- Input Nama Project --}}
        <x-input label="Nama Project" wire:model="name" icon="o-cube" />

        {{-- Upload Logo --}}
        <x-file label="Logo Project" wire:model="logo" accept="image/png, image/jpeg, image/jpg">
            
        </x-file>

        <x-textarea label="Deskripsi" wire:model="description" placeholder="Catatan singkat..." rows="3" />

        <x-slot:actions>
            <x-button label="Batal" @click="$wire.myModal = false" />
            <x-button label="Simpan" class="btn-primary" type="submit" spinner="save" />
        </x-slot:actions>
    </x-form>

</x-modal>
</div>