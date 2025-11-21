@if($alumni->isEmpty())
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No alumni found</h3>
        <p class="mt-1 text-sm text-gray-500">Try adjusting your search criteria.</p>
    </div>
@else
    <!-- Mobile Card View -->
    <div class="block md:hidden space-y-4">
        @foreach($alumni as $alumnus)
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center gap-3 flex-1">
                        @if($alumnus->profile_image)
                            <img src="{{ $alumnus->profile_image_url }}" alt="{{ $alumnus->name }}" class="w-12 h-12 rounded-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center hidden">
                                <span class="text-indigo-600 font-semibold text-xs">{{ getUserInitials($alumnus->name) }}</span>
                            </div>
                        @else
                            <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center">
                                <span class="text-indigo-600 font-semibold text-xs">{{ getUserInitials($alumnus->name) }}</span>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $alumnus->name }}</h3>
                            <p class="text-xs text-gray-600 truncate">{{ $alumnus->email }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1">
                        @if($alumnus->status === 'active')
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Inactive</span>
                        @endif
                        @if($alumnus->profile_status === 'approved')
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Approved</span>
                        @elseif($alumnus->profile_status === 'pending')
                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Blocked</span>
                        @endif
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 text-xs text-gray-600 mb-3">
                    <div><span class="font-medium">Course:</span> {{ $alumnus->course ?? 'N/A' }}</div>
                    <div><span class="font-medium">Year:</span> {{ $alumnus->passing_year ?? 'N/A' }}</div>
                    <div class="col-span-2">
                        <span class="font-medium">Company:</span> 
                        <span class="truncate block" title="{{ $alumnus->company ?? 'N/A' }}">{{ $alumnus->company ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.alumni.view', $alumnus->id) }}" class="flex-1 text-center bg-indigo-600 text-white px-3 py-2 rounded-md hover:bg-indigo-700 text-xs font-medium transition-colors touch-manipulation">
                        View
                    </a>
                    <a href="{{ route('admin.users.edit', $alumnus->id) }}" class="flex-1 text-center bg-gray-600 text-white px-3 py-2 rounded-md hover:bg-gray-700 text-xs font-medium transition-colors touch-manipulation">
                        Edit
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Desktop Table View -->
    <div class="hidden md:block">
        <div class="overflow-hidden border border-gray-200 rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-48">
                            @php
                                $currentSortBy = $sortBy ?? 'name';
                                $currentSortOrder = $sortOrder ?? 'asc';
                                $nextOrder = ($currentSortBy === 'name' && $currentSortOrder === 'asc') ? 'desc' : 'asc';
                                $isActive = $currentSortBy === 'name';
                            @endphp
                            <a href="{{ route('admin.alumni.index', array_merge(request()->except(['sort_by', 'sort_order', 'page']), ['sort_by' => 'name', 'sort_order' => $nextOrder])) }}" class="flex items-center gap-1 hover:text-indigo-600 transition-colors group">
                                Name
                                @if($isActive)
                                    @if($currentSortOrder === 'asc')
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @else
                                    <svg class="w-4 h-4 text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                    </svg>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-56">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40">
                            @php
                                $nextOrder = ($currentSortBy === 'course' && $currentSortOrder === 'asc') ? 'desc' : 'asc';
                                $isActive = $currentSortBy === 'course';
                            @endphp
                            <a href="{{ route('admin.alumni.index', array_merge(request()->except(['sort_by', 'sort_order', 'page']), ['sort_by' => 'course', 'sort_order' => $nextOrder])) }}" class="flex items-center gap-1 hover:text-indigo-600 transition-colors group">
                                Course
                                @if($isActive)
                                    @if($currentSortOrder === 'asc')
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @else
                                    <svg class="w-4 h-4 text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                    </svg>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">
                            @php
                                $nextOrder = ($currentSortBy === 'passing_year' && $currentSortOrder === 'asc') ? 'desc' : 'asc';
                                $isActive = $currentSortBy === 'passing_year';
                            @endphp
                            <a href="{{ route('admin.alumni.index', array_merge(request()->except(['sort_by', 'sort_order', 'page']), ['sort_by' => 'passing_year', 'sort_order' => $nextOrder])) }}" class="flex items-center gap-1 hover:text-indigo-600 transition-colors group">
                                Year
                                @if($isActive)
                                    @if($currentSortOrder === 'asc')
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @else
                                    <svg class="w-4 h-4 text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                    </svg>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32 max-w-[8rem]">
                            @php
                                $nextOrder = ($currentSortBy === 'company' && $currentSortOrder === 'asc') ? 'desc' : 'asc';
                                $isActive = $currentSortBy === 'company';
                            @endphp
                            <a href="{{ route('admin.alumni.index', array_merge(request()->except(['sort_by', 'sort_order', 'page']), ['sort_by' => 'company', 'sort_order' => $nextOrder])) }}" class="flex items-center gap-1 hover:text-indigo-600 transition-colors group">
                                Company
                                @if($isActive)
                                    @if($currentSortOrder === 'asc')
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @else
                                    <svg class="w-4 h-4 text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                    </svg>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Profile</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($alumni as $alumnus)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div class="flex items-center min-w-0">
                                    @if($alumnus->profile_image)
                                        <img src="{{ $alumnus->profile_image_url }}" alt="{{ $alumnus->name }}" class="w-8 h-8 rounded-full object-cover mr-2 flex-shrink-0" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center mr-2 flex-shrink-0 hidden">
                                            <span class="text-indigo-600 font-semibold text-xs">{{ getUserInitials($alumnus->name) }}</span>
                                        </div>
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center mr-2 flex-shrink-0">
                                            <span class="text-indigo-600 font-semibold text-xs">{{ getUserInitials($alumnus->name) }}</span>
                                        </div>
                                    @endif
                                    <div class="text-sm font-medium text-gray-900 truncate">{{ $alumnus->name }}</div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-900 truncate" title="{{ $alumnus->email }}">{{ $alumnus->email }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-900 truncate" title="{{ $alumnus->course ?? 'N/A' }}">{{ $alumnus->course ?? 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-900">{{ $alumnus->passing_year ?? 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-900 truncate max-w-[8rem]" title="{{ $alumnus->company ?? 'N/A' }}">{{ $alumnus->company ?? 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-3">
                                @if($alumnus->status === 'active')
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 whitespace-nowrap">Active</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 whitespace-nowrap">Inactive</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($alumnus->profile_status === 'approved')
                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 whitespace-nowrap">Approved</span>
                                @elseif($alumnus->profile_status === 'pending')
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 whitespace-nowrap">Pending</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 whitespace-nowrap">Blocked</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-1.5">
                                    <a href="{{ route('admin.alumni.view', $alumnus->id) }}" class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded transition-colors whitespace-nowrap">
                                        View
                                    </a>
                                    <a href="{{ route('admin.users.edit', $alumnus->id) }}" class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-white bg-gray-600 hover:bg-gray-700 rounded transition-colors whitespace-nowrap">
                                        Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $alumni->links() }}
    </div>
@endif

