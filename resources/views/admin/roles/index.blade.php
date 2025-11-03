@extends('layouts.app')

@section('title', 'Role Management')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold mb-2">Role Management</h1>
        <p class="text-gray-600">Create and manage user roles for the system</p>
    </div>
    <a href="{{ route('admin.roles.create') }}" class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-6 py-3 rounded-lg shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 font-semibold">
        <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Create New Role
    </a>
</div>

<!-- Roles Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($roles as $role)
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
            <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white">{{ ucfirst($role->name) }}</h3>
                    @if(in_array($role->name, ['admin', 'alumnus']))
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-400 text-yellow-900">
                            Default
                        </span>
                    @endif
                </div>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Users with this role</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $role->users_count ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                </div>
                
                <div class="pt-4 border-t border-gray-200">
                    @if(!in_array($role->name, ['admin', 'alumnus']))
                        <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this role? All users with this role will lose it.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-all duration-300 font-semibold">
                                Delete Role
                            </button>
                        </form>
                    @else
                        <button disabled class="w-full bg-gray-300 text-gray-500 px-4 py-2 rounded-lg cursor-not-allowed font-semibold">
                            Cannot Delete Default Role
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-3 bg-white rounded-lg shadow-lg p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
            <p class="text-xl font-medium text-gray-700 mb-2">No Roles Found</p>
            <p class="text-gray-500 mb-6">Get started by creating your first role</p>
            <a href="{{ route('admin.roles.create') }}" class="inline-block bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 shadow-md hover:shadow-lg transition-all duration-300 font-semibold">
                Create Role
            </a>
        </div>
    @endforelse
</div>

<div class="mt-6">
    <a href="{{ route('admin.dashboard') }}" class="text-purple-600 hover:text-purple-700 font-semibold">
        ← Back to Dashboard
    </a>
</div>
@endsection


