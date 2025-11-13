@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold">Admin Dashboard</h1>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-500">Total Users</h3>
            <p class="text-3xl font-bold text-indigo-600">{{ $stats['total_users'] }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-500">Total Events</h3>
            <p class="text-3xl font-bold text-indigo-600">{{ $stats['total_events'] }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-500">Total Campaigns</h3>
            <p class="text-3xl font-bold text-indigo-600">{{ $stats['total_campaigns'] }}</p>
        </div>
        <a href="{{ route('admin.profiles.pending') }}" class="bg-white shadow rounded-lg p-6 hover:shadow-lg transition-shadow">
            <h3 class="text-lg font-semibold text-gray-500">Pending Profiles</h3>
            <p class="text-3xl font-bold text-indigo-600">{{ $stats['pending_profiles'] ?? 0 }}</p>
            <p class="text-sm text-gray-500 mt-2">Click to review</p>
        </a>
    </div>
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Recent Registrations</h2>
        <div class="space-y-2">
            @foreach($stats['recent_registrations'] as $user)
                <div class="flex items-center justify-between py-2 border-b">
                    <span>{{ $user->name }}</span>
                    <span class="text-sm text-gray-500">{{ $user->created_at->diffForHumans() }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection



