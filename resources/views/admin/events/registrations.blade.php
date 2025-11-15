@extends('layouts.admin')

@section('title', 'Event Registrations')

@section('content')
<div class="space-y-6 px-4 sm:px-0">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $event->title }}</h1>
            <p class="text-sm text-gray-500 mt-1">Event Registrations</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.events.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                Back to Events
            </a>
            @if($registrations->count() > 0)
                <a href="{{ route('admin.events.email', $event->id) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    Email All
                </a>
                <a href="{{ route('admin.events.export', $event->id) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    Download CSV
                </a>
            @endif
        </div>
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

    <!-- Event Details Card -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Event Details</h3>
                <div class="space-y-2 text-sm">
                    <p><span class="font-medium">Date:</span> {{ $event->event_start_date->format('M d, Y') }}</p>
                    @if($event->event_end_date)
                        <p><span class="font-medium">End Date:</span> {{ $event->event_end_date->format('M d, Y') }}</p>
                    @endif
                    <p><span class="font-medium">Venue:</span> {{ $event->venue }}</p>
                    <p><span class="font-medium">Status:</span> 
                        <span class="px-2 py-1 text-xs rounded-full {{ $event->status == 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($event->status) }}
                        </span>
                    </p>
                </div>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Registration Statistics</h3>
                <div class="space-y-2 text-sm">
                    <p><span class="font-medium">Total Registrations:</span> {{ $registrations->count() }}</p>
                    <p><span class="font-medium">Needs Stay:</span> {{ $registrations->where('needs_stay', true)->count() }}</p>
                    <p><span class="font-medium">Coming With Family:</span> {{ $registrations->where('coming_with_family', true)->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Registrations Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        @if($registrations->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Arrival Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Travel Mode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered At</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($registrations as $registration)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $registration->user->name }}</div>
                                    @if($registration->user->company)
                                        <div class="text-sm text-gray-500">{{ $registration->user->company }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $registration->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $registration->user->phone ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $registration->user->course ?? 'N/A' }}</div>
                                    @if($registration->user->passing_year)
                                        <div class="text-sm text-gray-500">Batch {{ $registration->user->passing_year }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($registration->arrival_date)
                                        <div class="text-sm text-gray-900">{{ $registration->arrival_date->format('M d, Y') }}</div>
                                        @if($registration->arrival_time)
                                            <div class="text-sm text-gray-500">{{ $registration->arrival_time->format('h:i A') }}</div>
                                        @endif
                                    @else
                                        <span class="text-sm text-gray-400">Not specified</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $registration->travel_mode ?? 'N/A' }}</div>
                                    @if($registration->coming_from_city)
                                        <div class="text-sm text-gray-500">From {{ $registration->coming_from_city }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $registration->created_at->format('M d, Y') }}</div>
                                    <div class="text-sm text-gray-500">{{ $registration->created_at->format('h:i A') }}</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No registrations</h3>
                <p class="mt-1 text-sm text-gray-500">No one has registered for this event yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection

