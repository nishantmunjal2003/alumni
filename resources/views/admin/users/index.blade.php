@extends('layouts.admin')

@section('title', 'Manage Users')

@section('content')
<div class="space-y-6 px-4 sm:px-0">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-3xl font-bold text-gray-900">Manage Users</h1>
        <div class="text-sm text-gray-500">
            Total: <span class="font-semibold">{{ $users->total() }}</span> users
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white shadow rounded-lg p-4 sm:p-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Search Input -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Users</label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="search" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Search by name, email, course, company..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        >
                        <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
                    <select 
                        id="status" 
                        name="status" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        onchange="this.form.submit()"
                    >
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-2">
                <button 
                    type="submit" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Search
                </button>
                @if(request('search') || request('status'))
                    <a 
                        href="{{ route('admin.users.index') }}" 
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                    >
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Users List - Desktop Table View -->
    <div class="hidden md:block bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Roles</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($user->profile_image)
                                            <img 
                                                class="h-10 w-10 rounded-full object-cover" 
                                                src="{{ asset('storage/' . $user->profile_image) }}" 
                                                alt="{{ $user->name }}"
                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                            >
                                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center hidden">
                                                <span class="text-indigo-600 font-semibold text-sm">{{ getUserInitials($user->name) }}</span>
                                            </div>
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                <span class="text-indigo-600 font-semibold text-sm">{{ getUserInitials($user->name) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        @if($user->passing_year || $user->course)
                                            <div class="text-sm text-gray-500">
                                                @if($user->passing_year && $user->course)
                                                    Batch {{ $user->passing_year }} • {{ $user->course }}
                                                @elseif($user->passing_year)
                                                    Batch {{ $user->passing_year }}
                                                @elseif($user->course)
                                                    {{ $user->course }}
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $isProtectedAdmin = $user->email === 'nishant@gkv.ac.in';
                                @endphp
                                <form method="POST" action="{{ route('admin.users.toggle-status', $user->id) }}" class="inline" id="status-form-{{ $user->id }}">
                                    @csrf
                                    <label class="relative inline-flex items-center {{ $isProtectedAdmin ? 'cursor-not-allowed' : 'cursor-pointer' }}">
                                        <input 
                                            type="checkbox" 
                                            class="sr-only peer" 
                                            id="status-toggle-{{ $user->id }}"
                                            {{ $user->status === 'active' ? 'checked' : '' }}
                                            {{ $isProtectedAdmin ? 'disabled' : '' }}
                                            onchange="{{ $isProtectedAdmin ? '' : "toggleUserStatus({$user->id})" }}"
                                        >
                                        <div class="w-11 h-6 {{ $isProtectedAdmin ? 'bg-gray-300 opacity-50' : 'bg-gray-200' }} peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                        <span class="ml-3 text-sm font-medium {{ $user->status === 'active' ? 'text-green-600' : 'text-gray-500' }}" id="status-label-{{ $user->id }}">
                                            {{ ucfirst($user->status) }}
                                            @if($isProtectedAdmin)
                                                <span class="text-xs text-gray-500 ml-1">(Protected)</span>
                                            @endif
                                        </span>
                                    </label>
                                </form>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-2" id="roles-container-{{ $user->id }}">
                                    @foreach($roles as $role)
                                        @php
                                            $isAdminRole = $role->name === 'admin';
                                            $isDisabled = $isProtectedAdmin && $isAdminRole;
                                            $hasRole = in_array($role->name, $userRoles[$user->id] ?? []);
                                        @endphp
                                        <label class="inline-flex items-center {{ $isDisabled ? 'cursor-not-allowed' : 'cursor-pointer' }}">
                                            <input 
                                                type="checkbox" 
                                                class="sr-only peer role-checkbox" 
                                                data-user-id="{{ $user->id }}"
                                                data-role-name="{{ $role->name }}"
                                                {{ $hasRole ? 'checked' : '' }}
                                                {{ $isDisabled ? 'disabled' : '' }}
                                                onchange="{{ $isDisabled ? '' : "updateUserRoles({$user->id})" }}"
                                            >
                                            <span class="px-3 py-1 text-xs rounded-full transition-colors {{ $hasRole ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-600' }} {{ $isDisabled ? 'opacity-50' : '' }} peer-checked:bg-indigo-100 peer-checked:text-indigo-800">
                                                {{ $role->name }}
                                                @if($isDisabled)
                                                    <span class="text-xs text-gray-500 ml-1">(Protected)</span>
                                                @endif
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                                <form method="POST" action="{{ route('admin.users.roles.update', $user->id) }}" id="roles-form-{{ $user->id }}" style="display: none;">
                                    @csrf
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-2 items-center">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="p-2 text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit User">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" class="inline" onsubmit="return confirmDelete({{ json_encode($user->name) }})">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition-colors" title="Delete User">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <span class="p-2 text-gray-400 cursor-not-allowed" title="Cannot delete your own account">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">No users found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Users List - Mobile Card View -->
    <div class="md:hidden space-y-4">
        @forelse($users as $user)
            <div class="bg-white shadow rounded-lg p-4 space-y-4">
                <!-- User Header -->
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        @if($user->profile_image)
                            <img 
                                class="h-12 w-12 rounded-full object-cover" 
                                src="{{ asset('storage/' . $user->profile_image) }}" 
                                alt="{{ $user->name }}"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                            >
                            <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center hidden">
                                <span class="text-indigo-600 font-semibold text-base">{{ getUserInitials($user->name) }}</span>
                            </div>
                        @else
                            <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center">
                                <span class="text-indigo-600 font-semibold text-base">{{ getUserInitials($user->name) }}</span>
                            </div>
                        @endif
                        <div>
                            <div class="font-semibold text-gray-900">{{ $user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                            @if($user->passing_year || $user->course)
                                <div class="text-sm text-gray-600">
                                    @if($user->passing_year && $user->course)
                                        Batch {{ $user->passing_year }} • {{ $user->course }}
                                    @elseif($user->passing_year)
                                        Batch {{ $user->passing_year }}
                                    @elseif($user->course)
                                        {{ $user->course }}
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Status Toggle -->
                <div class="flex items-center justify-between py-2 border-t border-gray-200">
                    <span class="text-sm font-medium text-gray-700">Account Status</span>
                    @php
                        $isProtectedAdmin = $user->email === 'nishant@gkv.ac.in';
                    @endphp
                    <form method="POST" action="{{ route('admin.users.toggle-status', $user->id) }}" class="inline" id="status-form-mobile-{{ $user->id }}">
                        @csrf
                        <label class="relative inline-flex items-center {{ $isProtectedAdmin ? 'cursor-not-allowed' : 'cursor-pointer' }}">
                            <input 
                                type="checkbox" 
                                class="sr-only peer" 
                                id="status-toggle-mobile-{{ $user->id }}"
                                {{ $user->status === 'active' ? 'checked' : '' }}
                                {{ $isProtectedAdmin ? 'disabled' : '' }}
                                onchange="{{ $isProtectedAdmin ? '' : "toggleUserStatus({$user->id})" }}"
                            >
                            <div class="w-11 h-6 {{ $isProtectedAdmin ? 'bg-gray-300 opacity-50' : 'bg-gray-200' }} peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            <span class="ml-3 text-sm font-medium {{ $user->status === 'active' ? 'text-green-600' : 'text-gray-500' }}" id="status-label-mobile-{{ $user->id }}">
                                {{ ucfirst($user->status) }}
                                @if($isProtectedAdmin)
                                    <span class="text-xs text-gray-500 ml-1">(Protected)</span>
                                @endif
                            </span>
                        </label>
                    </form>
                </div>

                <!-- Roles Section -->
                <div class="py-2 border-t border-gray-200">
                    <div class="text-sm font-medium text-gray-700 mb-3">Roles</div>
                    <div class="flex flex-wrap gap-2" id="roles-container-mobile-{{ $user->id }}">
                        @foreach($roles as $role)
                            @php
                                $isAdminRole = $role->name === 'admin';
                                $isDisabled = $isProtectedAdmin && $isAdminRole;
                                $hasRole = in_array($role->name, $userRoles[$user->id] ?? []);
                            @endphp
                            <label class="inline-flex items-center {{ $isDisabled ? 'cursor-not-allowed' : 'cursor-pointer' }}">
                                <input 
                                    type="checkbox" 
                                    class="sr-only peer role-checkbox" 
                                    data-user-id="{{ $user->id }}"
                                    data-role-name="{{ $role->name }}"
                                    {{ $hasRole ? 'checked' : '' }}
                                    {{ $isDisabled ? 'disabled' : '' }}
                                    onchange="{{ $isDisabled ? '' : "updateUserRoles({$user->id})" }}"
                                >
                                <span class="px-3 py-1 text-xs rounded-full transition-colors {{ $hasRole ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-600' }} {{ $isDisabled ? 'opacity-50' : '' }} peer-checked:bg-indigo-100 peer-checked:text-indigo-800">
                                    {{ $role->name }}
                                    @if($isDisabled)
                                        <span class="text-xs text-gray-500 ml-1">(Protected)</span>
                                    @endif
                                </span>
                            </label>
                        @endforeach
                    </div>
                    <form method="POST" action="{{ route('admin.users.roles.update', $user->id) }}" id="roles-form-mobile-{{ $user->id }}" style="display: none;">
                        @csrf
                    </form>
                </div>

                <!-- Actions -->
                <div class="pt-2 border-t border-gray-200 flex gap-3 items-center">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium" title="Edit User">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span>Edit</span>
                    </a>
                    @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" class="inline" onsubmit="return confirmDelete({{ json_encode($user->name) }})">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium" title="Delete User">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                <span>Delete</span>
                            </button>
                        </form>
                    @else
                        <span class="flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-500 rounded-lg text-sm font-medium cursor-not-allowed" title="Cannot delete your own account">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            <span>Delete</span>
                        </span>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white shadow rounded-lg p-8 text-center">
                <p class="text-gray-500">No users found</p>
            </div>
        @endforelse

        <!-- Pagination -->
        <div class="py-4">
            {{ $users->links() }}
        </div>
    </div>
</div>

<script>
    function confirmDelete(userName) {
        return confirm(`Are you sure you want to delete "${userName}"? This action cannot be undone and will permanently remove the user and all associated data.`);
    }

    function toggleUserStatus(userId) {
        const checkbox = document.getElementById(`status-toggle-${userId}`) || document.getElementById(`status-toggle-mobile-${userId}`);
        const label = document.getElementById(`status-label-${userId}`) || document.getElementById(`status-label-mobile-${userId}`);
        const form = document.getElementById(`status-form-${userId}`) || document.getElementById(`status-form-mobile-${userId}`);
        
        const formData = new FormData(form);
        const isActive = checkbox.checked;
        
        // Update visual state immediately
        if (isActive) {
            label.textContent = 'Active';
            label.classList.remove('text-gray-500');
            label.classList.add('text-green-600');
        } else {
            label.textContent = 'Inactive';
            label.classList.remove('text-green-600');
            label.classList.add('text-gray-500');
        }
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Success - status updated
            console.log('Status updated successfully');
        })
        .catch(error => {
            console.error('Error updating status:', error);
            // Revert checkbox on error
            checkbox.checked = !checkbox.checked;
            if (checkbox.checked) {
                label.textContent = 'Active';
                label.classList.remove('text-gray-500');
                label.classList.add('text-green-600');
            } else {
                label.textContent = 'Inactive';
                label.classList.remove('text-green-600');
                label.classList.add('text-gray-500');
            }
        });
    }

    function updateUserRoles(userId) {
        // Get all checked roles for this user
        const checkboxes = document.querySelectorAll(`input.role-checkbox[data-user-id="${userId}"]`);
        const checkedRoles = [];
        
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                checkedRoles.push(checkbox.getAttribute('data-role-name'));
            }
        });

        // Update visual state immediately
        checkboxes.forEach(checkbox => {
            const span = checkbox.nextElementSibling;
            if (checkbox.checked) {
                span.classList.remove('bg-gray-100', 'text-gray-600');
                span.classList.add('bg-indigo-100', 'text-indigo-800');
            } else {
                span.classList.remove('bg-indigo-100', 'text-indigo-800');
                span.classList.add('bg-gray-100', 'text-gray-600');
            }
        });

        // Prepare form data
        const form = document.getElementById(`roles-form-${userId}`) || document.getElementById(`roles-form-mobile-${userId}`);
        const formData = new FormData(form);
        
        // Add checked roles
        checkedRoles.forEach(role => {
            formData.append('roles[]', role);
        });

        // Submit via fetch
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Success - roles updated
            console.log('Roles updated successfully');
        })
        .catch(error => {
            console.error('Error updating roles:', error);
            // Revert checkboxes on error
            location.reload();
        });
    }
</script>
@endsection
