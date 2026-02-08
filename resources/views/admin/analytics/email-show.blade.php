@extends('layouts.admin')

@section('title', 'Email Log Details')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">Email Details</h1>
        <a href="{{ route('admin.analytics.emails') }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Logs
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Sent To</label>
                <div class="text-lg font-medium text-gray-900">{{ $emailLog->recipient_email }}</div>
                @if($emailLog->user)
                    <div class="text-sm text-gray-600">User: <a href="{{ route('admin.users.edit', $emailLog->user) }}" class="text-indigo-500 hover:underline">{{ $emailLog->user->name }}</a></div>
                @endif
            </div>
            <div class="md:text-right">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Sent At</label>
                <div class="text-gray-900">{{ $emailLog->created_at->format('F d, Y h:i A') }}</div>
                <div class="text-sm text-gray-500">{{ $emailLog->created_at->diffForHumans() }}</div>
            </div>
        </div>

        <div class="px-6 py-4 border-b border-gray-100">
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Subject</label>
            <h2 class="text-xl font-bold text-gray-800">{{ $emailLog->subject }}</h2>
        </div>

        <div class="px-6 py-6 prose max-w-none">
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4 border-b pb-2">Message Body</label>
            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                {!! $emailLog->body !!}
            </div>
        </div>
        
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 text-xs text-gray-500 flex justify-between">
            <span>Log ID: {{ $emailLog->id }}</span>
            <span>Status: <span class="uppercase font-bold text-green-600">{{ $emailLog->status }}</span></span>
        </div>
    </div>
</div>
@endsection
