@if($alumni->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($alumni as $alumnus)
            <a href="{{ route('alumni.show', $alumnus->id) }}" class="group bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5 hover:shadow-md hover:border-indigo-300 dark:hover:border-indigo-600 transition-all">
                <div class="flex items-start gap-4">
                    @if($alumnus->profile_image)
                        <img src="{{ asset('storage/' . $alumnus->profile_image) }}" alt="{{ $alumnus->name }}" class="w-14 h-14 rounded-full ring-2 ring-gray-200 dark:ring-gray-700 group-hover:ring-indigo-500 transition-all flex-shrink-0 object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="w-14 h-14 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center ring-2 ring-gray-200 dark:ring-gray-700 group-hover:ring-indigo-500 transition-all flex-shrink-0 hidden">
                            <span class="text-indigo-600 dark:text-indigo-400 font-semibold text-sm">{{ getUserInitials($alumnus->name) }}</span>
                        </div>
                    @else
                        <div class="w-14 h-14 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center ring-2 ring-gray-200 dark:ring-gray-700 group-hover:ring-indigo-500 transition-all flex-shrink-0">
                            <span class="text-indigo-600 dark:text-indigo-400 font-semibold text-sm">{{ getUserInitials($alumnus->name) }}</span>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors truncate">{{ $alumnus->name }}</h3>
                        @if($alumnus->passing_year || $alumnus->course)
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                @if($alumnus->passing_year && $alumnus->course)
                                    Batch {{ $alumnus->passing_year }} • {{ $alumnus->course }}
                                @elseif($alumnus->passing_year)
                                    Batch {{ $alumnus->passing_year }}
                                @elseif($alumnus->course)
                                    {{ $alumnus->course }}
                                @endif
                            </p>
                        @endif
                        @if($alumnus->current_position)
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 truncate">{{ $alumnus->current_position }}</p>
                        @endif
                        @if($alumnus->company)
                            <p class="text-sm text-gray-500 dark:text-gray-500 mt-1 truncate">{{ $alumnus->company }}</p>
                        @endif
                    </div>
                </div>
            </a>
        @endforeach
    </div>
    <div class="mt-8">
        {{ $alumni->links() }}
    </div>
@else
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        <p class="mt-4 text-gray-500 dark:text-gray-400 font-medium">No alumni found</p>
        <p class="mt-1 text-sm text-gray-400 dark:text-gray-500">Try adjusting your search criteria</p>
    </div>
@endif




