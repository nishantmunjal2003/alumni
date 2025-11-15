@extends('layouts.admin')

@section('title', 'Email Event Registrants')

@section('content')
<div class="space-y-6 px-4 sm:px-0">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Email Event Registrants</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $event->title }}</p>
        </div>
        <a href="{{ route('admin.events.registrations', $event->id) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
            Back to Registrations
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg" role="alert">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow rounded-lg p-6">
        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <p class="text-sm text-blue-800">
                <strong>Note:</strong> This email will be sent to all <strong>{{ $recipientCount }}</strong> registrants of this event.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.events.email.send', $event->id) }}" class="space-y-6">
            @csrf

            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                    Subject <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="subject" 
                    name="subject" 
                    value="{{ old('subject') }}"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Event Update: {{ $event->title }}"
                >
            </div>

            <div>
                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                    Message <span class="text-red-500">*</span>
                </label>
                <textarea 
                    id="message" 
                    name="message" 
                    rows="12"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Enter your message here..."
                >{{ old('message') }}</textarea>
                <p class="mt-2 text-sm text-gray-500">
                    You can use HTML formatting in your message.
                </p>
            </div>

            <div class="flex gap-4">
                <button 
                    type="submit" 
                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Send Email to All Registrants
                </button>
                <a 
                    href="{{ route('admin.events.registrations', $event->id) }}" 
                    class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

