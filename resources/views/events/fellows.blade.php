@extends('layouts.app')

@section('title', 'Fellows - ' . $event->title)

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Fellows Attending {{ $event->title }}</h1>
    
    <!-- Search Form -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 border border-gray-200 dark:border-gray-700">
        <form id="search-form" method="GET" action="{{ route('events.fellows', $event->id) }}">
            <div class="flex gap-4">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" name="search" id="search" placeholder="Search by name, email, major, company, city..." class="pl-11 pr-4 py-3 w-full border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:focus:border-indigo-400 dark:focus:ring-indigo-800 transition-all" value="{{ request('search') }}" autocomplete="off">
                </div>
                <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 transition-all font-medium shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Search
                    </span>
                </button>
                @if(request('search'))
                    <a href="{{ route('events.fellows', $event->id) }}" class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-6 py-3 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-all font-medium">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Grouped Results -->
    <div class="space-y-8">
        @forelse($sortedGroups as $passingYear => $yearRegistrations)
            <div class="space-y-4">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white border-b-2 border-indigo-500 pb-2">
                    @if($passingYear === $currentUserPassingYear)
                        Passing Year: {{ $passingYear }} <span class="text-sm font-normal text-indigo-600 dark:text-indigo-400">(Your Batch)</span>
                    @else
                        Passing Year: {{ $passingYear }}
                    @endif
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($yearRegistrations as $registration)
                        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center space-x-4 mb-4">
                                @if($registration->user->profile_image)
                                    <img src="{{ asset('storage/' . $registration->user->profile_image) }}" alt="{{ $registration->user->name }}" class="w-16 h-16 rounded-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="w-16 h-16 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center hidden">
                                        <span class="text-indigo-600 dark:text-indigo-400 font-semibold text-base">{{ getUserInitials($registration->user->name) }}</span>
                                    </div>
                                @else
                                    <div class="w-16 h-16 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                        <span class="text-indigo-600 dark:text-indigo-400 font-semibold text-base">{{ getUserInitials($registration->user->name) }}</span>
                                    </div>
                                @endif
                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ $registration->user->name }}</h3>
                                    @if($registration->user->passing_year || $registration->user->course)
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            @if($registration->user->passing_year && $registration->user->course)
                                                {{ $registration->user->passing_year }} • {{ $registration->user->course }}
                                            @elseif($registration->user->passing_year)
                                                {{ $registration->user->passing_year }}
                                            @elseif($registration->user->course)
                                                {{ $registration->user->course }}
                                            @endif
                                        </p>
                                    @endif
                                    @if($registration->coming_from_city)
                                        <p class="text-sm text-gray-600 dark:text-gray-400">From {{ $registration->coming_from_city }}</p>
                                    @endif
                                </div>
                            </div>
                            @if($registration->photos->count() > 0)
                                <div class="grid grid-cols-2 gap-2 mt-4">
                                    @foreach($registration->photos->take(4) as $photo)
                                        <img src="{{ asset('storage/' . $photo->photo_path) }}" alt="Photo" class="w-full h-24 object-cover rounded">
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <p class="text-gray-600 dark:text-gray-400 text-lg">No fellows found{{ request('search') ? ' matching your search.' : '.' }}</p>
            </div>
        @endforelse
    </div>
</div>

<script>
(function() {
    const searchInput = document.getElementById('search');
    let searchTimeout = null;

    // Real-time search on input change with debouncing
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                document.getElementById('search-form').submit();
            }, 500); // Wait 500ms after user stops typing
        });
    }
})();
</script>
@endsection




