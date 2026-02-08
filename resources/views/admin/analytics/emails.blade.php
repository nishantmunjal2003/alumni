@extends('layouts.admin')

@section('title', 'Email Logs')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-100">
    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Email Logs</h2>
            <p class="text-gray-500 text-sm mt-1">History of all emails sent from the system</p>
        </div>
        <div>
            <form action="{{ route('admin.analytics.emails') }}" method="GET" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search email or subject..." 
                       class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">Search</button>
            </form>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 font-semibold text-gray-700 text-sm">ID</th>
                    <th class="px-6 py-4 font-semibold text-gray-700 text-sm">Recipient</th>
                    <th class="px-6 py-4 font-semibold text-gray-700 text-sm">Subject</th>
                    <th class="px-6 py-4 font-semibold text-gray-700 text-sm">Sent At</th>
                    <th class="px-6 py-4 font-semibold text-gray-700 text-sm text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-sm text-gray-600">#{{ $log->id }}</td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $log->recipient_email }}</div>
                        @if($log->user)
                        <div class="text-xs text-gray-500">{{ $log->user->name }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-800">{{ Str::limit($log->subject, 50) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $log->created_at->format('M d, Y h:i A') }}
                        <span class="text-xs text-gray-400 block">{{ $log->created_at->diffForHumans() }}</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.analytics.emails.show', $log) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">View Details</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        No email logs found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="p-6 border-t border-gray-100">
        {{ $logs->links() }}
    </div>
</div>
@endsection
