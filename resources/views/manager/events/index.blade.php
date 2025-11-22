@extends('layouts.manager')

@section('title', 'Manage Events')

@section('content')
<div class="space-y-6 px-4 sm:px-0">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-3xl font-bold text-gray-900">Manage Events</h1>
        <a href="{{ route('manager.events.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
            Create Event
        </a>
    </div>
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg" role="alert">
            {{ session('error') }}
        </div>
    @endif
    
    <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg">
        <p class="text-sm"><strong>Note:</strong> Only events with status "Published" and a future start date will be visible on the public events page. You can only edit/delete your own events, but you can view all events and their registrations.</p>
    </div>
    
    <!-- Desktop Table View -->
    <div class="hidden md:block bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registrations</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($events as $event)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $event->title }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ ($event->event_start_date ?? $event->event_date ?? null)?->format('M d, Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full {{ $event->status == 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('manager.events.registrations', $event->id) }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                    {{ $event->registrations_count }} {{ $event->registrations_count === 1 ? 'registration' : 'registrations' }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $event->creator->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-3 items-center">
                                    <a href="{{ route('manager.events.registrations', $event->id) }}" class="p-2 text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 rounded-lg transition-colors" title="View Registrations">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('manager.events.email', $event->id) }}" class="p-2 text-green-600 hover:text-green-900 hover:bg-green-50 rounded-lg transition-colors" title="Send Email to Alumni">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </a>
                                    @if($event->created_by === auth()->id())
                                        <a href="{{ route('manager.events.edit', $event->id) }}" class="p-2 text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit Event">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('manager.events.destroy', $event->id) }}" class="inline" onsubmit="return confirmDeleteEvent({{ json_encode($event->title) }})">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition-colors" title="Delete Event">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <span class="p-2 text-gray-400 cursor-not-allowed" title="You can only edit/delete your own events">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">No events found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $events->links() }}
        </div>
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden space-y-4">
        @forelse($events as $event)
            <div class="bg-white shadow rounded-lg p-4 space-y-4">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $event->title }}</h3>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ ($event->event_start_date ?? $event->event_date ?? null)?->format('M d, Y') }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">Created by: {{ $event->creator->name ?? 'N/A' }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full {{ $event->status == 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($event->status) }}
                    </span>
                </div>

                <div class="pt-2 border-t border-gray-200">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-medium text-gray-700">Registrations</span>
                        <a href="{{ route('manager.events.registrations', $event->id) }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                            {{ $event->registrations_count }} {{ $event->registrations_count === 1 ? 'registration' : 'registrations' }}
                        </a>
                    </div>
                </div>

                <div class="pt-2 border-t border-gray-200 flex gap-2">
                    <a href="{{ route('manager.events.registrations', $event->id) }}" class="flex-1 flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span>View</span>
                    </a>
                    <a href="{{ route('manager.events.email', $event->id) }}" class="flex-1 flex items-center justify-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span>Email</span>
                    </a>
                    @if($event->created_by === auth()->id())
                        <a href="{{ route('manager.events.edit', $event->id) }}" class="flex-1 flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <span>Edit</span>
                        </a>
                        <form method="POST" action="{{ route('manager.events.destroy', $event->id) }}" class="flex-1" onsubmit="return confirmDeleteEvent({{ json_encode($event->title) }})">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                <span>Delete</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white shadow rounded-lg p-8 text-center">
                <p class="text-gray-500">No events found</p>
            </div>
        @endforelse

        <!-- Pagination -->
        <div class="py-4">
            {{ $events->links() }}
        </div>
    </div>
</div>

<script>
    function confirmDeleteEvent(eventTitle) {
        let message = `Are you sure you want to delete "${eventTitle}"?`;
        message += '\n\nThis action cannot be undone.';
        return confirm(message);
    }
</script>
@endsection

