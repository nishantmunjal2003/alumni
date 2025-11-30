@if($pendingProfiles->isEmpty())
    <div class="bg-white shadow rounded-lg p-6 text-center">
        <p class="text-gray-500">No pending profiles found.</p>
    </div>
@else
    <!-- Mobile Card View -->
    <div class="block md:hidden space-y-4">
        @foreach($pendingProfiles as $index => $profile)
            <div class="bg-white shadow rounded-lg p-4 space-y-3">
                <div class="flex items-start justify-between">
                    <div class="flex items-start gap-3 flex-1">
                        <span class="text-sm font-semibold text-indigo-600 mt-1">#{{ ($pendingProfiles->currentPage() - 1) * $pendingProfiles->perPage() + $index + 1 }}</span>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $profile->name }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $profile->email }}</p>
                        </div>
                    </div>
                    @if($profile->proof_document)
                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 whitespace-nowrap">Uploaded</span>
                    @else
                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 whitespace-nowrap">Missing</span>
                    @endif
                </div>
                
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <span class="text-gray-500">Course:</span>
                        <span class="text-gray-900 ml-1">{{ $profile->course ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Year:</span>
                        <span class="text-gray-900 ml-1">{{ $profile->passing_year ?? 'N/A' }}</span>
                    </div>
                    <div class="col-span-2">
                        <span class="text-gray-500">Company:</span>
                        <span class="text-gray-900 ml-1">{{ $profile->company ?? 'N/A' }}</span>
                    </div>
                    <div class="col-span-2">
                        <span class="text-gray-500">Submitted:</span>
                        <span class="text-gray-900 ml-1">{{ $profile->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
                
                <div class="flex flex-col gap-2 pt-2 border-t border-gray-200">
                    <a href="{{ route('admin.profiles.view', $profile->id) }}" class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md transition-colors touch-manipulation">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        View Profile
                    </a>
                    <div class="grid grid-cols-2 gap-2">
                        <form method="POST" action="{{ route('admin.profiles.approve', $profile->id) }}" class="inline">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-md transition-colors touch-manipulation" onclick="return confirm('Are you sure you want to approve this profile?')">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Approve
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.profiles.block', $profile->id) }}" class="inline">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md transition-colors touch-manipulation" onclick="return confirm('Are you sure you want to block this profile?')">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Block
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Desktop Table View -->
    <div class="hidden md:block bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-hidden border border-gray-200 rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">S.No.</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-36">Name</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-48">Email</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Course</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Year</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32 max-w-[8rem]">Company</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Proof</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Submitted</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-44">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($pendingProfiles as $index => $profile)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2">
                                <div class="text-sm font-medium text-gray-900">{{ ($pendingProfiles->currentPage() - 1) * $pendingProfiles->perPage() + $index + 1 }}</div>
                            </td>
                            <td class="px-3 py-2">
                                <div class="text-sm font-medium text-gray-900 truncate max-w-[9rem]" title="{{ $profile->name }}">{{ $profile->name }}</div>
                            </td>
                            <td class="px-3 py-2">
                                <div class="text-sm text-gray-900 truncate max-w-[12rem]" title="{{ $profile->email }}">{{ $profile->email }}</div>
                            </td>
                            <td class="px-3 py-2">
                                <div class="text-sm text-gray-900 truncate max-w-[8rem]" title="{{ $profile->course ?? 'N/A' }}">{{ $profile->course ?? 'N/A' }}</div>
                            </td>
                            <td class="px-3 py-2">
                                <div class="text-sm text-gray-900">{{ $profile->passing_year ?? 'N/A' }}</div>
                            </td>
                            <td class="px-3 py-2">
                                <div class="text-sm text-gray-900 truncate max-w-[8rem]" title="{{ $profile->company ?? 'N/A' }}">{{ $profile->company ?? 'N/A' }}</div>
                            </td>
                            <td class="px-3 py-2">
                                @if($profile->proof_document)
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-800 whitespace-nowrap">Uploaded</span>
                                @else
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-red-100 text-red-800 whitespace-nowrap">Missing</span>
                                @endif
                            </td>
                            <td class="px-3 py-2">
                                <div class="text-sm text-gray-900 whitespace-nowrap">{{ $profile->updated_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-3 py-2">
                                <div class="flex gap-1 flex-nowrap">
                                    <a href="{{ route('admin.profiles.view', $profile->id) }}" class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded transition-colors whitespace-nowrap flex-shrink-0">
                                        <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View
                                    </a>
                                    <form method="POST" action="{{ route('admin.profiles.approve', $profile->id) }}" class="inline flex-shrink-0">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-green-600 hover:bg-green-700 rounded transition-colors whitespace-nowrap" onclick="return confirm('Are you sure you want to approve this profile?')">
                                            <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Approve
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.profiles.block', $profile->id) }}" class="inline flex-shrink-0">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-red-600 hover:bg-red-700 rounded transition-colors whitespace-nowrap" onclick="return confirm('Are you sure you want to block this profile?')">
                                            <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Block
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $pendingProfiles->links() }}
        </div>
    </div>

    <!-- Mobile Pagination -->
    <div class="block md:hidden">
        {{ $pendingProfiles->links() }}
    </div>
@endif





