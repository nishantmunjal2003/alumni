@extends('layouts.app')

@section('title', 'Manager Dashboard')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold">Manager Dashboard</h1>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white shadow rounded-lg p-6 hover:shadow-lg transition-shadow">
            <h3 class="text-lg font-semibold text-gray-500">Total Users</h3>
            <p class="text-3xl font-bold text-indigo-600">{{ $stats['total_users'] }}</p>
            <a href="{{ route('manager.alumni.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 mt-2 inline-block">View All →</a>
        </div>
        <div class="bg-white shadow rounded-lg p-6 hover:shadow-lg transition-shadow">
            <h3 class="text-lg font-semibold text-gray-500">Active Users</h3>
            <p class="text-3xl font-bold text-green-600">{{ $stats['active_users'] }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6 hover:shadow-lg transition-shadow">
            <h3 class="text-lg font-semibold text-gray-500">Inactive Users</h3>
            <p class="text-3xl font-bold text-red-600">{{ $stats['inactive_users'] }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6 hover:shadow-lg transition-shadow">
            <h3 class="text-lg font-semibold text-gray-500">Pending Profiles</h3>
            <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending_profiles'] ?? 0 }}</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('manager.alumni.index') }}" class="flex items-center gap-3 p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition-colors">
                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-3-3h-4a3 3 0 00-3 3v2h5zM9 10a3 3 0 100-6 3 3 0 000 6zM9 10v10m0-10h12m-12 0h12"></path>
                </svg>
                <div>
                    <h3 class="font-semibold text-gray-900">View Alumni</h3>
                    <p class="text-sm text-gray-500">Browse and manage alumni accounts</p>
                </div>
            </a>
            <a href="{{ route('manager.alumni.index', ['status' => 'inactive']) }}" class="flex items-center gap-3 p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition-colors">
                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div>
                    <h3 class="font-semibold text-gray-900">Inactive Accounts</h3>
                    <p class="text-sm text-gray-500">View and activate inactive accounts</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Registrations -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Recent Registrations</h2>
            <a href="{{ route('manager.alumni.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">View All →</a>
        </div>
        <div class="space-y-2">
            @forelse($stats['recent_registrations'] as $user)
                <div class="flex items-center justify-between py-2 border-b">
                    <div class="flex items-center gap-3">
                        <span class="font-medium">{{ $user->name }}</span>
                        <span class="text-sm text-gray-500">{{ $user->email }}</span>
                        @if($user->status === 'active')
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Inactive</span>
                        @endif
                        @if($user->profile_status === 'pending')
                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                        @elseif($user->profile_status === 'approved')
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Approved</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500">{{ $user->created_at->diffForHumans() }}</span>
                        <a href="{{ route('manager.alumni.show', $user->id) }}" class="text-sm text-indigo-600 hover:text-indigo-800">View</a>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">No recent registrations</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

