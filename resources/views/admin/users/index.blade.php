@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold mb-2">User Management</h1>
    <p class="text-gray-600">Search and manage users, assign roles with toggle switches</p>
</div>

<!-- Search Bar -->
<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <form method="GET" action="{{ route('admin.users') }}" class="flex gap-4">
        <input 
            type="text" 
            name="search" 
            value="{{ request('search') }}" 
            placeholder="Search by name or email..."
            class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300"
        >
        <button type="submit" class="bg-purple-600 text-white px-8 py-3 rounded-lg hover:bg-purple-700 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 font-semibold">
            Search
        </button>
        @if(request('search'))
            <a href="{{ route('admin.users') }}" class="bg-gray-500 text-white px-8 py-3 rounded-lg hover:bg-gray-600 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 font-semibold">
                Clear
            </a>
        @endif
    </form>
</div>

<!-- Users Table -->
<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-purple-700">
        <h2 class="text-xl font-bold text-white">Users ({{ $users->total() }})</h2>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Roles</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assign Roles</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($user->profile_image)
                                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full mr-3">
                            @else
                                <div class="w-10 h-10 rounded-full bg-purple-500 flex items-center justify-center text-white font-bold mr-3">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                @if($user->graduation_year)
                                    <div class="text-xs text-gray-500">Class of {{ $user->graduation_year }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $user->email }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-2">
                            @forelse($user->roles as $role)
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                    {{ $role->name }}
                                </span>
                            @empty
                                <span class="text-xs text-gray-400 italic">No roles assigned</span>
                            @endforelse
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <form action="{{ route('admin.users.updateRoles', $user) }}" method="POST" id="role-form-{{ $user->id }}" class="space-y-2">
                            @csrf
                            <div class="space-y-2">
                                @foreach($roles as $role)
                                    <label class="flex items-center space-x-2 cursor-pointer group">
                                        <input 
                                            type="checkbox" 
                                            name="roles[]" 
                                            value="{{ $role->id }}"
                                            {{ $user->hasRole($role->name) ? 'checked' : '' }}
                                            onchange="document.getElementById('role-form-{{ $user->id }}').submit();"
                                            class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500 focus:ring-2 transition-all"
                                        >
                                        <span class="text-sm text-gray-700 group-hover:text-purple-600 transition-colors">
                                            {{ ucfirst($role->name) }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </form>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('messages.show', $user) }}" 
                            class="inline-block bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 text-sm font-semibold transition-colors">
                            Message
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <p class="text-lg font-medium">No users found</p>
                            @if(request('search'))
                                <p class="text-sm mt-2">Try a different search term</p>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($users->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    @endif
</div>

<div class="mt-6">
    <a href="{{ route('admin.dashboard') }}" class="text-purple-600 hover:text-purple-700 font-semibold">
        ← Back to Dashboard
    </a>
</div>
@endsection

