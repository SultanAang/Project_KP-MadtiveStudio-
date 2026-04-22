<div class="flex justify-center gap-10">
    {{-- BAGIAN KIRI: FORM --}}
    <div class="w-1/2 my-10">
        <div class="py-2">
            <div class="mx-auto">
                <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Buat Akun Baru</h2>
            </div>
            
            @if (session('success'))     
            <div class="p-4 mt-6 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                {{ session('success') }}
            </div>
            @endif

            <div class="mt-4">
                <form wire:submit="createNewUser" class="space-y-6">
                    
                    {{-- Input Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-900">Name</label>
                        <div class="mt-2">
                            <input wire:model='name' id="name" type="text" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" />
                            @error('name') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Input Username --}}
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-900">Username</label>
                        <div class="mt-2">
                            <input wire:model='username' id="username" type="text" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" />
                            @error('username') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Input Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-900">Email address</label>
                        <div class="mt-2">
                            <input wire:model="email" id="email" type="email" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" />
                            @error('email') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- === BAGIAN DROPDOWN ROLE (VERSI LIVEWIRE PURE) === --}}
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-900 mb-2">Role Access</label>
                        
                        <button wire:click="toggleDropdown" type="button"
                            class="inline-flex items-center justify-between w-full px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            
                            {{ $role ? ucwords(str_replace('_', ' ', $role)) : 'Pilih Role Pengguna' }} 
                            
                            @if($isDropdownOpen)
                                <svg class="w-4 h-4 ms-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                            @else
                                <svg class="w-4 h-4 ms-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            @endif
                        </button>
                    
                        @if($isDropdownOpen)
                        <div class="absolute z-10 w-full mt-2 bg-white border border-gray-200 rounded-md shadow-lg max-h-60 overflow-y-auto">
                            <ul class="p-3 space-y-2 text-sm text-gray-700">
                                
                                {{-- Pilihan 1: Admin --}}
                                <li wire:click="selectRole('admin')" 
                                    class="flex items-center p-2 rounded hover:bg-gray-100 cursor-pointer {{ $role === 'admin' ? 'bg-indigo-50 text-indigo-700' : '' }}">
                                    <span class="w-4 h-4 mr-2 border rounded-full flex items-center justify-center border-gray-300 {{ $role === 'admin' ? 'border-indigo-600 bg-indigo-600' : '' }}">
                                        @if($role === 'admin') <div class="w-2 h-2 bg-white rounded-full"></div> @endif
                                    </span>
                                    Admin System
                                </li>
                    
                                {{-- Pilihan 2: Client --}}
                                <li wire:click="selectRole('client')" 
                                    class="flex items-center p-2 rounded hover:bg-gray-100 cursor-pointer {{ $role === 'client' ? 'bg-indigo-50 text-indigo-700' : '' }}">
                                    <span class="w-4 h-4 mr-2 border rounded-full flex items-center justify-center border-gray-300 {{ $role === 'client' ? 'border-indigo-600 bg-indigo-600' : '' }}">
                                        @if($role === 'client') <div class="w-2 h-2 bg-white rounded-full"></div> @endif
                                    </span>
                                    Client (Klien)
                                </li>
                    
                                {{-- Pilihan 3: Tim Dokumentasi --}}
                                <li wire:click="selectRole('tim_dokumentasi')" 
                                    class="flex items-center p-2 rounded hover:bg-gray-100 cursor-pointer {{ $role === 'tim_dokumentasi' ? 'bg-indigo-50 text-indigo-700' : '' }}">
                                    <span class="w-4 h-4 mr-2 border rounded-full flex items-center justify-center border-gray-300 {{ $role === 'tim_dokumentasi' ? 'border-indigo-600 bg-indigo-600' : '' }}">
                                        @if($role === 'tim_dokumentasi') <div class="w-2 h-2 bg-white rounded-full"></div> @endif
                                    </span>
                                    Tim Dokumentasi
                                </li>
                    
                                {{-- Pilihan 4: Reviewer --}}
                                <li wire:click="selectRole('reviewer_internal')" 
                                    class="flex items-center p-2 rounded hover:bg-gray-100 cursor-pointer {{ $role === 'reviewer_internal' ? 'bg-indigo-50 text-indigo-700' : '' }}">
                                    <span class="w-4 h-4 mr-2 border rounded-full flex items-center justify-center border-gray-300 {{ $role === 'reviewer_internal' ? 'border-indigo-600 bg-indigo-600' : '' }}">
                                        @if($role === 'reviewer_internal') <div class="w-2 h-2 bg-white rounded-full"></div> @endif
                                    </span>
                                    Reviewer Internal
                                </li>

                            </ul>
                        </div>
                        @endif
                        
                        @error('role')
                            <p class="mt-2 text-xs text-red-600"><span class="font-medium">Error:</span> {{ $message }}</p>
                        @enderror
                    </div>
                    {{-- === END DROPDOWN === --}}

                    {{-- Input Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-900">Password</label>
                        <div class="mt-2">
                            <input wire:model="password" id="password" type="password" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" />
                            @error('password') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Upload Avatar --}}
                    <div class="col-span-full">
                        <label for="avatar" class="block text-sm font-medium text-gray-900">Profile Picture</label>
                        <div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-4">
                            <div class="text-center">
                                @if ($avatar)
                                    <img src="{{ $avatar->temporaryUrl() }}" class="mx-auto size-20 object-cover rounded-full mb-4">
                                @else
                                    <svg class="mx-auto size-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" /></svg>
                                @endif
                                <div class="mt-4 flex text-sm leading-6 text-gray-600 justify-center">
                                    <label for="avatar" class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                                        <span>Upload a file</span>
                                        <input wire:model="avatar" id="avatar" type="file" class="sr-only" accept="image/png, image/jpg, image/jpeg">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs leading-5 text-gray-600">PNG, JPG up to 3MB</p>
                            </div>
                        </div>
                        <div wire:loading wire:target="avatar" class="mt-2 text-xs text-indigo-600 text-center animate-pulse">Uploading...</div>
                        @error('avatar') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Submit Button --}}
                    <div>
                        <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            <span wire:loading.remove wire:target="createNewUser">Create User</span>
                            <span wire:loading wire:target="createNewUser">Processing...</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- BAGIAN KANAN: LIST USER --}}
    <div class="w-1/3 my-10">
        <div class="mx-auto mb-4">
            <h2 class="mt-12 text-center text-2xl/9 font-bold tracking-tight text-gray-900 mb-4">List User</h2>
        </div>
        <ul role="list" class="divide-y divide-gray-100">
            @foreach ( $users as $user )
            <li class="flex justify-between gap-x-6 py-5">
                <div class="flex min-w-0 gap-x-4">
                    <img src="{{ $user->avatar ?? asset('img/download.jpg') }}" alt="" class="size-12 flex-none rounded-full bg-gray-50 object-cover" />
                    <div class="min-w-0 flex-auto">
                        <p class="text-sm font-semibold leading-6 text-gray-900">{{ $user->name }}</p>
                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ $user->email }}</p>
                        <p class="text-xs text-indigo-600 font-medium">{{ ucwords(str_replace('_', ' ', $user->role)) }}</p>
                    </div>
                </div>
                <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end self-center">
                    <p class="mt-1 text-xs leading-5 text-gray-500">Last seen {{ $user->created_at->diffForHumans() }}</p>
                </div>
            </li>
            @endforeach
        </ul>
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>