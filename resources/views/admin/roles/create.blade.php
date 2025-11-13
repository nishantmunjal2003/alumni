@extends('layouts.admin')

@section('title', 'Create Role')

@section('content')
<div class="max-w-md mx-auto">
    <h1 class="text-3xl font-bold mb-6">Create Role</h1>
    <form method="POST" action="{{ route('admin.roles.store') }}" class="bg-white shadow rounded-lg p-6">
        @csrf
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Role Name *</label>
            <input type="text" name="name" id="name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>
        <div class="mt-6">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">Create Role</button>
        </div>
    </form>
</div>
@endsection




