@extends('layouts.admin')

@section('title', 'Activity Logs')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-100">
    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">User Activity Logs</h2>
            <p class="text-gray-500 text-sm mt-1">Detailed history of user actions and page visits</p>
        </div>
        <div>
            <form action="{{ route('admin.analytics.activity') }}" method="GET" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search URL or IP..." 
                       class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">Search</button>
            </form>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 font-semibold text-gray-700 text-sm">User</th>
                    <th class="px-6 py-4 font-semibold text-gray-700 text-sm">Action / URL</th>
                    <th class="px-6 py-4 font-semibold text-gray-700 text-sm">Status</th>
                    <th class="px-6 py-4 font-semibold text-gray-700 text-sm">IP Address</th>
                    <th class="px-6 py-4 font-semibold text-gray-700 text-sm text-right">Time</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50 transition-colors {{ $log->status_code >= 400 ? 'bg-red-50 hover:bg-red-100' : '' }}">
                    <td class="px-6 py-4">
                        @if($log->user)
                            <div class="text-sm font-medium text-gray-900">{{ $log->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $log->user->email }}</div>
                        @else
                            <span class="text-sm text-gray-500 italic">Guest</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-1 text-xs font-bold rounded {{ $log->method === 'GET' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $log->method }}
                            </span>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-700 truncate max-w-xs block" title="{{ $log->url }}">{{ Str::limit($log->url, 50) }}</span>
                                @if($log->exception)
                                    <span class="text-xs text-red-600 font-mono mt-1" title="{{ $log->exception }}">{{ Str::limit($log->exception, 60) }}</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($log->status_code >= 400)
                            <span class="px-2 py-1 text-xs font-bold rounded bg-red-100 text-red-800">
                                {{ $log->status_code }}
                            </span>
                        @elseif($log->status_code >= 300)
                             <span class="px-2 py-1 text-xs font-bold rounded bg-yellow-100 text-yellow-800">
                                {{ $log->status_code }}
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-bold rounded bg-green-100 text-green-800">
                                {{ $log->status_code ?? 200 }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600 font-mono">{{ $log->ip_address }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        @if($log->country)
                            {{ $log->city ? $log->city . ', ' : '' }}{{ $log->country }}
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="text-sm text-gray-900">{{ $log->created_at->format('M d, H:i') }}</div>
                        <div class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        No active logs found.
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
