@extends('layouts.admin')

@section('title', 'Pending Profiles')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold">Pending Profiles for Approval</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-indigo-600 hover:text-indigo-800">Back to Dashboard</a>
    </div>

    @if($pendingProfiles->isEmpty())
        <div class="bg-white shadow rounded-lg p-6 text-center">
            <p class="text-gray-500">No pending profiles at the moment.</p>
        </div>
    @else
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Course</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Passing Year</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Company</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Proof Document</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Submitted</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($pendingProfiles as $profile)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $profile->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $profile->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $profile->course ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $profile->passing_year ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $profile->company ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($profile->proof_document)
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Uploaded</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Missing</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $profile->updated_at->diffForHumans() }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.profiles.view', $profile->id) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">View</a>
                                    <form method="POST" action="{{ route('admin.profiles.approve', $profile->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-800 text-sm" onclick="return confirm('Are you sure you want to approve this profile?')">Approve</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.profiles.block', $profile->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm" onclick="return confirm('Are you sure you want to block this profile?')">Block</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4">
                {{ $pendingProfiles->links() }}
            </div>
        </div>
    @endif
</div>
@endsection


