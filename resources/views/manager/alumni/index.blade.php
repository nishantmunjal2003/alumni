@extends('layouts.app')

@section('title', 'Manage Alumni')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold">Manage Alumni</h1>
        <a href="{{ route('manager.dashboard') }}" class="text-indigo-600 hover:text-indigo-800">Back to Dashboard</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search and Filters -->
    <div class="bg-white shadow rounded-lg p-6">
        <form method="GET" action="{{ route('manager.alumni.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Name, Email, Course, Company..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div>
                    <label for="profile_status" class="block text-sm font-medium text-gray-700">Profile Status</label>
                    <select name="profile_status" id="profile_status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">All Profile Statuses</option>
                        <option value="pending" {{ request('profile_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('profile_status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="blocked" {{ request('profile_status') === 'blocked' ? 'selected' : '' }}>Blocked</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Filter</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Alumni Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Course</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Company</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Profile Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->course ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->company ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->status === 'active')
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->profile_status === 'pending')
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                            @elseif($user->profile_status === 'approved')
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Approved</span>
                            @elseif($user->profile_status === 'blocked')
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Blocked</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex gap-2">
                                <a href="{{ route('manager.alumni.show', $user->id) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">View</a>
                                @if($user->status === 'active')
                                    <form method="POST" action="{{ route('manager.alumni.deactivate', $user->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm" onclick="return confirm('Are you sure you want to deactivate this account?')">Deactivate</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('manager.alumni.activate', $user->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-800 text-sm" onclick="return confirm('Are you sure you want to activate this account?')">Activate</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No alumni found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection

