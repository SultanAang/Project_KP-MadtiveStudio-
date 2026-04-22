    <div>
        {{-- Header & Button Create --}}
        <x-header title="Manajemen User" subtitle="Kelola data staff internal">
            <x-slot:middle class="!justify-end">
                <x-input icon="o-magnifying-glass" placeholder="Search..." />
            </x-slot:middle>
            <x-slot:actions>
                <x-button icon="o-plus" class="btn-primary" wire:click="create" label="Tambah User" />
            </x-slot:actions>
        </x-header>

        {{-- Alert Message --}}
        @if (session()->has('message'))
            <x-alert icon="o-check" class="alert-success mb-4">
                {{ session('message') }}
            </x-alert>
        @endif

        {{-- Tabel List --}}
        <x-card>
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>User Info</th>
                            <th>Role</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    {{-- TEMPEL KODE BARU INI --}}
                                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('img/download.jpg') }}" alt="{{ $user->name }}" class="size-12 flex-none rounded-full object-cover border border-gray-200 shadow-sm" />
                                    <div>
                                        <div class="font-bold">{{ $user->name }}</div>
                                        <div class="text-sm opacity-50">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <x-badge value="{{ $user->role }}" class="badge-primary" />
                            </td>
                            <td class="text-right">
                                <x-button icon="o-pencil" wire:click="edit({{ $user->id }})" class="btn-ghost btn-sm text-blue-500" />
                                <x-button icon="o-trash" wire:click="delete({{ $user->id }})" 
                                        wire:confirm="Yakin hapus?" 
                                        class="btn-ghost btn-sm text-red-500" />
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center font-bold text-gray-400 py-10">
                                Tidak ada data user.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </x-card>

        {{-- ==================================================== --}}
        {{-- MODAL COMPONENT (Sesuai Permintaan Anda) --}}
        {{-- ==================================================== --}}
        
        <x-modal wire:model="myModal2" title="{{ $userIdToEdit ? 'Edit User' : 'Tambah User Baru' }}" subtitle="Isi form berikut dengan lengkap">
            
            {{-- Kita pakai x-form agar tombol aksi rapi di bawah --}}
            <x-form wire:submit="save">
                
                <x-input label="Nama Lengkap" wire:model="name" icon="o-user" placeholder="Nama lengkap..." />
                
                {{-- Menggunakan Options array dari PHP --}}
                <x-select label="Jabatan / Role" icon="o-briefcase" :options="$roles" wire:model="role" placeholder="-- Pilih Jabatan --" />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-input label="Username" wire:model="username" icon="o-at-symbol" />
                    <x-input label="Email" type="email" wire:model="email" icon="o-envelope" />
                </div>

                <x-input label="Password" type="password" wire:model="password" icon="o-key" 
                        hint="{{ $userIdToEdit ? 'Kosongkan jika tidak ingin mengubah password' : 'Wajib diisi untuk user baru' }}" />

                {{-- Slot Actions: Tombol Batal & Simpan --}}
                <x-slot:actions>
                    {{-- Tombol Cancel: Langsung ubah variabel modal jadi false via Alpine --}}
                    <x-button label="Batal" @click="$wire.myModal2 = false" />
                    
                    {{-- Tombol Submit --}}
                    <x-button label="Simpan" class="btn-primary" type="submit" spinner="save" />
                </x-slot:actions>
            
            </x-form>
        
        </x-modal>

    </div>