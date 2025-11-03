@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold mb-2">Admin Dashboard</h1>
    <p class="text-gray-600">Manage alumni, campaigns, and events</p>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <a href="{{ route('alumni.index') }}" class="bg-gradient-to-r from-purple-600 to-purple-700 text-white p-6 rounded-lg shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <div class="flex items-center mb-3">
            <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <h2 class="text-xl font-bold">Alumni Directory</h2>
        </div>
        <p class="text-sm opacity-90">View and manage all alumni profiles</p>
    </a>
    
    <a href="{{ route('admin.users') }}" class="bg-gradient-to-r from-indigo-600 to-indigo-700 text-white p-6 rounded-lg shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <div class="flex items-center mb-3">
            <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            <h2 class="text-xl font-bold">User Management</h2>
        </div>
        <p class="text-sm opacity-90">Manage users and assign roles</p>
    </a>
    
    <a href="{{ route('admin.roles') }}" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-6 rounded-lg shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <div class="flex items-center mb-3">
            <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
            <h2 class="text-xl font-bold">Roles</h2>
        </div>
        <p class="text-sm opacity-90">Create and manage roles</p>
    </a>
    
    <a href="{{ route('admin.campaigns.create') }}" class="bg-gradient-to-r from-green-600 to-green-700 text-white p-6 rounded-lg shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <div class="flex items-center mb-3">
            <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
            </svg>
            <h2 class="text-xl font-bold">Create Campaign</h2>
        </div>
        <p class="text-sm opacity-90">Create a new campaign</p>
    </a>
    
    <a href="{{ route('admin.events.create') }}" class="bg-gradient-to-r from-pink-600 to-pink-700 text-white p-6 rounded-lg shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <div class="flex items-center mb-3">
            <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <h2 class="text-xl font-bold">Create Event</h2>
        </div>
        <p class="text-sm opacity-90">Create event and send invites</p>
    </a>
    
    <a href="{{ route('messages.index') }}" class="bg-gradient-to-r from-teal-600 to-teal-700 text-white p-6 rounded-lg shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <div class="flex items-center mb-3">
            <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
            </svg>
            <h2 class="text-xl font-bold">Messages</h2>
        </div>
        <p class="text-sm opacity-90">Communicate with alumni</p>
    </a>
</div>

@endsection

