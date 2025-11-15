@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold">Admin Dashboard</h1>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white shadow rounded-lg p-6 hover:shadow-lg transition-shadow">
            <h3 class="text-lg font-semibold text-gray-500">Total Users</h3>
            <p class="text-3xl font-bold text-indigo-600">{{ $stats['total_users'] }}</p>
            <a href="{{ route('admin.users.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 mt-2 inline-block">View All →</a>
        </div>
        <div class="bg-white shadow rounded-lg p-6 hover:shadow-lg transition-shadow">
            <h3 class="text-lg font-semibold text-gray-500">Total Events</h3>
            <p class="text-3xl font-bold text-indigo-600">{{ $stats['total_events'] }}</p>
            <a href="{{ route('admin.events.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 mt-2 inline-block">Manage Events →</a>
        </div>
        <div class="bg-white shadow rounded-lg p-6 hover:shadow-lg transition-shadow">
            <h3 class="text-lg font-semibold text-gray-500">Total Campaigns</h3>
            <p class="text-3xl font-bold text-indigo-600">{{ $stats['total_campaigns'] }}</p>
            <a href="{{ route('admin.campaigns.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 mt-2 inline-block">Manage Campaigns →</a>
        </div>
        <a href="{{ route('admin.profiles.pending') }}" class="bg-white shadow rounded-lg p-6 hover:shadow-lg transition-shadow">
            <h3 class="text-lg font-semibold text-gray-500">Pending Profiles</h3>
            <p class="text-3xl font-bold text-indigo-600">{{ $stats['pending_profiles'] ?? 0 }}</p>
            <p class="text-sm text-indigo-600 mt-2">Review & Approve →</p>
        </a>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.events.create') }}" class="flex items-center gap-3 p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition-colors">
                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <div>
                    <h3 class="font-semibold text-gray-900">Create Event</h3>
                    <p class="text-sm text-gray-500">Add a new event</p>
                </div>
            </a>
            <a href="{{ route('admin.campaigns.create') }}" class="flex items-center gap-3 p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition-colors">
                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <div>
                    <h3 class="font-semibold text-gray-900">Create Campaign</h3>
                    <p class="text-sm text-gray-500">Start a new campaign</p>
                </div>
            </a>
            <a href="{{ route('admin.profiles.pending') }}" class="flex items-center gap-3 p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition-colors">
                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="font-semibold text-gray-900">Approve Profiles</h3>
                    <p class="text-sm text-gray-500">Review pending profiles</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Active Campaigns -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Active Campaigns</h2>
            <a href="{{ route('admin.campaigns.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">View All →</a>
        </div>
        @if($activeCampaigns->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($activeCampaigns as $campaign)
                    <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                        @if($campaign->image)
                            <div class="relative h-32 overflow-hidden">
                                <img src="{{ asset('storage/' . $campaign->image) }}" alt="{{ $campaign->title }}" 
                                    class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="h-32 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                <svg class="w-12 h-12 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                        @endif
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 mb-2 line-clamp-1">
                                <a href="{{ route('admin.campaigns.edit', $campaign->id) }}" class="hover:text-indigo-600 transition-colors">
                                    {{ $campaign->title }}
                                </a>
                            </h3>
                            <p class="text-xs text-gray-500 mb-2">
                                <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $campaign->start_date->format('M d') }} - {{ $campaign->end_date->format('M d, Y') }}
                            </p>
                            <p class="text-sm text-gray-600 line-clamp-2 mb-3">
                                {{ Str::limit($campaign->description, 100) }}
                            </p>
                            <div class="flex items-center justify-between">
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 font-medium">Active</span>
                                <a href="{{ route('admin.campaigns.edit', $campaign->id) }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">Edit →</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-center py-4">No active campaigns at the moment</p>
        @endif
    </div>

    <!-- Recent Registrations -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Recent Registrations</h2>
            <a href="{{ route('admin.users.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">View All →</a>
        </div>
        <div class="space-y-2">
            @forelse($stats['recent_registrations'] as $user)
                <div class="flex items-center justify-between py-2 border-b">
                    <div class="flex items-center gap-3">
                        <span class="font-medium">{{ $user->name }}</span>
                        <span class="text-sm text-gray-500">{{ $user->email }}</span>
                        @if($user->profile_status === 'pending')
                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                        @elseif($user->profile_status === 'approved')
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Approved</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500">{{ $user->created_at->diffForHumans() }}</span>
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="text-sm text-indigo-600 hover:text-indigo-800">Edit</a>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">No recent registrations</p>
            @endforelse
        </div>
    </div>
</div>
@endsection



