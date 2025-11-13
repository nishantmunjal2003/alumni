@if($alumni->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($alumni as $alumnus)
            <div class="bg-white border rounded-lg p-4 hover:shadow-lg transition">
                <div class="flex items-center space-x-4">
                    <img src="{{ $alumnus->profile_image ? asset('storage/' . $alumnus->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($alumnus->name) }}" alt="{{ $alumnus->name }}" class="w-16 h-16 rounded-full">
                    <div class="flex-1">
                        <a href="{{ route('alumni.show', $alumnus->id) }}" class="font-semibold text-indigo-600 hover:text-indigo-800">{{ $alumnus->name }}</a>
                        @if($alumnus->current_position)
                            <p class="text-sm text-gray-600">{{ $alumnus->current_position }}</p>
                        @endif
                        @if($alumnus->company)
                            <p class="text-sm text-gray-500">{{ $alumnus->company }}</p>
                        @endif
                        @if($alumnus->graduation_year)
                            <p class="text-xs text-gray-400">{{ $alumnus->graduation_year }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-6">
        {{ $alumni->links() }}
    </div>
@else
    <p class="text-gray-500 text-center py-8">No alumni found.</p>
@endif




