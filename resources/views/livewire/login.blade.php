<div class="bg-gray-100 lg:h-screen flex items-center justify-center p-4">
    
    {{-- Tambahkan Style Khusus untuk Animasi Masuk --}}
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        .animate-delay-100 { animation-delay: 0.1s; }
        .animate-delay-200 { animation-delay: 0.2s; }
    </style>

    {{-- Container Utama dengan Animasi Masuk --}}
    <div class="max-w-6xl w-full bg-white [box-shadow:0_2px_10px_-3px_rgba(6,81,237,0.3)] p-4 lg:p-5 rounded-md animate-fade-in-up">
        <div class="grid md:grid-cols-2 items-center gap-y-8">
            
            {{-- Bagian Form --}}
            <form wire:submit="login" class="max-w-md mx-auto w-full p-4 md:p-6">
                @csrf
                
                {{-- Logo --}}
                <div class="mb-8 text-center md:text-left">
                    <a href="javascript:void(0)" class="inline-block transition-transform hover:scale-105 duration-300">
                        <img src="{{ asset('img/logo_madtive.jpg') }}" alt="Your Company" class="h-10 w-auto" />
                    </a>
                    <h2 class="mt-4 text-2xl font-bold tracking-tight text-gray-900">Login</h2>
                </div>

                {{-- Alert Success --}}
                @if (session('success'))
                    <div class="p-4 mb-6 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200 animate-fade-in-up" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Alert Status Login --}}
                @if (auth()->check())
                    <div class="p-4 mb-6 text-sm text-blue-800 rounded-lg bg-blue-50 border border-blue-200 animate-fade-in-up" role="alert">
                        <span class="font-medium">Status:</span> Kamu berhasil login sebagai ({{ auth()->user()->name }}).
                    </div>
                @endif
                
                {{-- Alert Error Validation (Jika menggunakan $this->addError('login', '...')) --}}
                @error('login')
                    <div class="p-4 mb-6 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200 animate-fade-in-up flex items-start gap-3" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-red-600 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <div>
                            <span class="font-medium block mb-1">Akses Ditolak</span>
                            {{ $message }}
                        </div>
                    </div>
                @enderror


                <div class="space-y-6">
                    {{-- Input Username --}}
                    <div class="group">
                        <label for="username" class="text-slate-900 text-sm font-medium mb-2 block transition-colors group-focus-within:text-blue-600">Username</label>
                        <div class="relative flex items-center">
                            <input 
                                wire:model="username" 
                                id="username" 
                                type="text" 
                                class="w-full text-sm text-slate-900 bg-slate-100 focus:bg-white pl-4 pr-10 py-3 rounded-md border border-slate-100 focus:border-blue-600 outline-none transition-all duration-300 focus:shadow-md focus:scale-[1.01] @error('username') border-red-500 bg-red-50 @enderror" 
                                placeholder="Enter username" 
                            />
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-[18px] h-[18px] absolute right-4 text-gray-400 transition-colors group-focus-within:text-blue-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A7.5 7.5 0 014.501 20.118z" />
                            </svg>
                        </div>
                        @error('username')
                            <p class="mt-1 text-xs text-red-600 animate-pulse">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input Password --}}
                    <div class="group">
                        <label for="password" class="text-slate-900 text-sm font-medium mb-2 block transition-colors group-focus-within:text-blue-600">Password</label>
                        <div class="relative flex items-center">
                            <input 
                                wire:model="password" 
                                id="password" 
                                type="password" 
                                class="w-full text-sm text-slate-900 bg-slate-100 focus:bg-white pl-4 pr-10 py-3 rounded-md border border-slate-100 focus:border-blue-600 outline-none transition-all duration-300 focus:shadow-md focus:scale-[1.01] @error('password') border-red-500 bg-red-50 @enderror" 
                                placeholder="Enter password" 
                            />
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-[18px] h-[18px] absolute right-4 cursor-pointer text-gray-400 transition-colors group-focus-within:text-blue-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                        </div>
                        @error('password')
                            <p class="mt-1 text-xs text-red-600 animate-pulse">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex flex-wrap items-center gap-4 justify-between">
                        <div class="flex items-center">
                            <input id="remember-me" name="remember-me" type="checkbox" class="shrink-0 h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded-md cursor-pointer transition-transform hover:scale-110" />
                            <label for="remember-me" class="ml-3 block text-sm text-slate-900 cursor-pointer select-none">
                                Remember me
                            </label>
                        </div>
                        {{-- <div class="text-sm">
                            <a href="javascript:void(0);" class="text-blue-600 font-medium hover:underline hover:text-blue-700 transition-colors">
                                Forgot password?
                            </a>
                        </div> --}}
                    </div>
                </div>

                <div class="mt-12">
                    {{-- Button Submit --}}
                    <button type="submit" wire:loading.attr="disabled" class="w-full shadow-xl py-2 px-4 text-[15px] tracking-wide font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none cursor-pointer flex justify-center items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed transition-all duration-300 transform hover:-translate-y-1 hover:shadow-2xl active:scale-95">
                        <svg wire:loading wire:target="login" class="w-5 h-5 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor" fill-opacity="0.3"/>
                            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                        </svg>
                        <span wire:loading.remove wire:target="login">Sign in</span>
                        <span wire:loading wire:target="login">Memproses...</span>
                    </button>
                    {{-- <p class="text-sm mt-6 text-center text-slate-600">Don't have an account? <a href="javascript:void(0);" class="text-blue-600 font-medium tracking-wide hover:underline ml-1 hover:text-blue-700 transition-colors">Register here</a></p> --}}
                </div>
            </form>

            {{-- Bagian Gambar/Overlay dengan Animasi Zoom Halus --}}
            <div class="w-full h-full hidden md:block animate-fade-in-up animate-delay-200">
                <div class="aspect-square bg-gray-900 relative rounded-md overflow-hidden w-full h-full group shadow-lg">
                    {{-- Gambar Background --}}
                    {{-- <img 
                        src="https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=1472&auto=format&fit=crop" 
                        class="w-full h-full object-cover opacity-60 group-hover:scale-110 transition-transform duration-[2000ms] ease-out" 
                        alt="Digital Network" 
                    /> --}}
                    
                    {{-- Overlay Gradient --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent"></div>

                    <div class="absolute inset-0 m-auto max-w-sm p-8 flex flex-col justify-end pb-16">
                        <div class="text-left transform transition-transform duration-500 group-hover:-translate-y-2">
                            {{-- Logo Kecil --}}
                            <div class="flex items-center gap-2 mb-4 opacity-80">
                                <div class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></div>
                                <span class="text-blue-200 text-xs font-bold tracking-widest uppercase">Madtive Studio</span>
                            </div>

                            <h1 class="text-white text-3xl font-bold mb-3 leading-tight">
                                Digital Solutions.
                            </h1>
                            
                            <p class="text-gray-300 text-sm font-light leading-relaxed mb-6">
                                "We turn business flows into strategic value. Access your integrated system to optimize performance from upstream to downstream."
                            </p>

                            {{-- Statistik --}}
                            {{-- <div class="flex items-center gap-6 border-t border-gray-700 pt-6">
                                <div class="group/stat">
                                    <p class="text-2xl font-bold text-white group-hover/stat:text-blue-400 transition-colors">100+</p>
                                    <p class="text-[10px] text-gray-400 uppercase tracking-wider">Happy Clients</p>
                                </div>
                                <div class="group/stat">
                                    <p class="text-2xl font-bold text-white group-hover/stat:text-blue-400 transition-colors">99.9%</p>
                                    <p class="text-[10px] text-gray-400 uppercase tracking-wider">System Uptime</p>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>