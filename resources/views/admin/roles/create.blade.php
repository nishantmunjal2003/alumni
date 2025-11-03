@extends('layouts.app')

@section('title', 'Create Role')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold mb-2">Create New Role</h1>
    <p class="text-gray-600">Add a new role to the system</p>
</div>

<div class="max-w-2xl">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label for="name" class="block text-gray-700 font-semibold mb-2">
                    Role Name <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name') }}" 
                    required
                    placeholder="e.g., moderator, editor, manager"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300"
                    pattern="[a-z0-9_]+"
                    title="Role name must be lowercase letters, numbers, or underscores only"
                >
                <p class="mt-2 text-sm text-gray-500">
                    Use lowercase letters, numbers, or underscores only (e.g., "moderator", "content_editor")
                </p>
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            <strong>Note:</strong> After creating the role, you can assign it to users from the User Management page.
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-8 py-3 rounded-lg shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 font-semibold">
                    Create Role
                </button>
                <a href="{{ route('admin.roles') }}" class="bg-gray-500 text-white px-8 py-3 rounded-lg shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 font-semibold">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.roles') }}" class="text-purple-600 hover:text-purple-700 font-semibold">
            ← Back to Roles
        </a>
    </div>
</div>

<script>
document.getElementById('name').addEventListener('input', function(e) {
    e.target.value = e.target.value.toLowerCase().replace(/[^a-z0-9_]/g, '');
});
</script>
@endsection


