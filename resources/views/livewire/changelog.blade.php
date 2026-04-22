<div class="max-w-4xl mx-auto px-4 py-12">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-indigo-600 mb-2">Changelog</h1>
            <p class="text-gray-500">Update terbaru dan perjalanan pengembangan aplikasi kami.</p>
        </div>
        <div class="space-y-12">
            @foreach($releases as $release)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-sm font-semibold">
                                {{ $release->version }}
                            </span>
                            <span class="ml-2 text-gray-400 text-sm">
                                {{ $release->published_at->format('d M Y') }}
                            </span>
                        </div>
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">
                            {{ $release->group }}
                        </span>
                    </div>

                    <h2 class="text-2xl font-bold mb-4">{{ $release->title }}</h2>
                    
                    <div class="prose text-gray-600 mb-8">
                        {!! $release->intro_text !!}
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($release->features)
                            @foreach($release->features as $feature)
                            <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-xl">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm text-indigo-500">
                                        ⚡ </div>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $feature['title'] ?? 'Fitur' }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">{{ $feature['description'] ?? '' }}</p>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-12 text-gray-400 text-sm">
            &copy; {{ date('Y') }} Madtive Studio. All rights reserved.
        </div>
    </div>