@extends('layouts.app')

@section('title', 'Manage Roles')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold">Manage Roles</h1>
        <a href="{{ route('admin.roles.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Create Role</a>
    </div>
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Permissions</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($roles as $role)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $role->name }}</td>
                        <td class="px-6 py-4">
                            @foreach($role->permissions as $permission)
                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">{{ $permission->name }}</span>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($role->name != 'admin' && $role->name != 'user')
                                <form method="POST" action="{{ route('admin.roles.destroy', $role->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection




