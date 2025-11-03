<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="alumni-grid">
    @forelse($alumni as $alumnus)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    @if($alumnus->profile_image)
                        <img src="{{ asset('storage/' . $alumnus->profile_image) }}" alt="{{ $alumnus->name }}" class="w-16 h-16 rounded-full object-cover mr-4">
                    @else
                        <div class="w-16 h-16 rounded-full bg-purple-500 flex items-center justify-center text-white text-2xl font-bold mr-4">
                            {{ substr($alumnus->name, 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <h3 class="text-xl font-semibold">{{ $alumnus->name }}</h3>
                        @if($alumnus->graduation_year)
                            <p class="text-gray-500">Class of {{ $alumnus->graduation_year }}</p>
                        @endif
                    </div>
                </div>
                
                @if($alumnus->major)
                    <p class="text-gray-600 mb-2"><strong>Major:</strong> {{ $alumnus->major }}</p>
                @endif
                
                @if($alumnus->current_position)
                    <p class="text-gray-600 mb-2"><strong>Position:</strong> {{ $alumnus->current_position }}</p>
                @endif
                
                @if($alumnus->company)
                    <p class="text-gray-600 mb-4"><strong>Company:</strong> {{ $alumnus->company }}</p>
                @endif
                
                <a href="{{ route('alumni.show', $alumnus) }}" class="text-purple-600 hover:underline">
                    View Profile →
                </a>
            </div>
        </div>
    @empty
        <div class="col-span-3 text-center py-12">
            <p class="text-gray-500 text-lg">No alumni found.</p>
        </div>
    @endforelse
</div>

