@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<div class="max-w-4xl mx-auto min-h-[80vh] flex flex-col">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Messages</h1>
        <button onclick="openNewMessageModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium shadow-md transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            New Message
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white shadow-lg rounded-xl overflow-hidden flex-1 border border-gray-100">
        @if($conversations->count() > 0)
            <div class="divide-y divide-gray-100">
                @foreach($conversations as $conversation)
                    <a href="{{ route('messages.show', $conversation['user']->id) }}" class="block p-5 hover:bg-gray-50 transition-colors group">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="relative">
                                    @if($conversation['user']->profile_image)
                                        <img src="{{ $conversation['user']->profile_image_url }}" alt="{{ $conversation['user']->name }}" class="w-14 h-14 rounded-full object-cover border border-gray-200 group-hover:border-indigo-500 transition-colors">
                                    @else
                                        <div class="w-14 h-14 rounded-full bg-indigo-100 flex items-center justify-center border border-indigo-100 group-hover:border-indigo-500 transition-colors">
                                            <span class="text-indigo-600 font-bold text-lg">{{ getUserInitials($conversation['user']->name) }}</span>
                                        </div>
                                    @endif
                                    @if($conversation['unread_count'] > 0)
                                        <span class="absolute top-0 right-0 w-3.5 h-3.5 bg-red-500 border-2 border-white rounded-full"></span>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $conversation['user']->name }}</h3>
                                    <p class="text-sm text-gray-500 font-medium truncate max-w-xs sm:max-w-sm">
                                        {{ $conversation['last_message']->from_user_id == auth()->id() ? 'You: ' : '' }}
                                        {{ Str::limit($conversation['last_message']->message, 60) }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right flex flex-col items-end">
                                <span class="text-xs text-gray-400 font-medium">{{ $conversation['last_message']->created_at->diffForHumans(null, true, true) }}</span>
                                @if($conversation['unread_count'] > 0)
                                    <span class="mt-2 bg-indigo-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $conversation['unread_count'] }}</span>
                                @else
                                    <svg class="w-5 h-5 text-gray-300 mt-2 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center h-96 text-center p-8">
                <div class="bg-indigo-50 p-6 rounded-full mb-4">
                    <svg class="w-16 h-16 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No messages yet</h3>
                <p class="text-gray-500 max-w-sm mb-6">Connect with fellow alumni. Start a conversation to network, share updates, or just say hello.</p>
                <button onclick="openNewMessageModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium shadow-md transition-all transform hover:-translate-y-1">
                    Start a Conversation
                </button>
            </div>
        @endif
    </div>
</div>

<!-- New Message Modal -->
<div id="newMessageModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeNewMessageModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal Panel -->
        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <form action="{{ route('messages.batch.store') }}" method="POST" id="batchMessageForm">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start w-full">
                        <div class="w-full">
                            <h3 class="text-xl leading-6 font-bold text-gray-900 mb-4" id="modal-title">New Message</h3>
                            
                            <!-- Search & Selection -->
                            <div class="mb-4 relative">
                                <label class="block text-sm font-medium text-gray-700 mb-1">To:</label>
                                
                                <!-- Selected Users Container -->
                                <div id="selectedUsersContainer" class="flex flex-wrap gap-2 mb-2 min-h-[32px]"></div>

                                <input type="text" id="userSearchInput" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Search alumni by name...">
                                
                                <!-- Dropdown Results -->
                                <div id="searchResults" class="absolute z-10 w-full bg-white shadow-lg rounded-md border border-gray-200 mt-1 max-h-60 overflow-y-auto hidden">
                                    <!-- Results injected via JS -->
                                </div>
                            </div>

                            <!-- Hidden Inputs for Form Submission -->
                            <div id="hiddenRecipientsInputs"></div>

                            <!-- Message Body -->
                            <div class="mt-4">
                                <label for="messageBody" class="block text-sm font-medium text-gray-700 mb-1">Message:</label>
                                <textarea name="message" id="messageBody" rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Type your message here..." required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Send Message
                    </button>
                    <button type="button" onclick="closeNewMessageModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('newMessageModal');
    const searchInput = document.getElementById('userSearchInput');
    const resultsDiv = document.getElementById('searchResults');
    const selectedContainer = document.getElementById('selectedUsersContainer');
    const hiddenInputsContainer = document.getElementById('hiddenRecipientsInputs');
    
    let selectedUsers = new Map(); // Store id -> userObj used map to prevent duplicates

    function openNewMessageModal() {
        modal.classList.remove('hidden');
        searchInput.focus();
    }

    function closeNewMessageModal() {
        modal.classList.add('hidden');
        // Optional: clear selection? No, keep it ideally, or maybe clear. Let's not clear for better UX if accidentally closed.
    }

    // Search Logic
    let debounceTimer;
    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const query = this.value.trim();
        
        if (query.length < 2) {
            resultsDiv.classList.add('hidden');
            resultsDiv.innerHTML = '';
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch(`{{ route('messages.search') }}?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(users => {
                    resultsDiv.innerHTML = '';
                    if (users.length === 0) {
                        resultsDiv.innerHTML = '<div class="p-3 text-sm text-gray-500 text-center">No alumni found.</div>';
                    } else {
                        users.forEach(user => {
                            // Don't show if already selected
                            if(selectedUsers.has(user.id)) return;

                            const div = document.createElement('div');
                            div.className = 'p-3 hover:bg-indigo-50 cursor-pointer flex items-center gap-3 border-b border-gray-100 last:border-0 transition-colors';
                            
                            let avatarHtml = '';
                            if (user.profile_image_url) {
                                avatarHtml = `<img src="${user.profile_image_url}" class="w-8 h-8 rounded-full object-cover">`;
                            } else {
                                const initials = user.name.charAt(0);
                                avatarHtml = `<div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold">${initials}</div>`;
                            }

                            div.innerHTML = `
                                ${avatarHtml}
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">${user.name}</p>
                                    <p class="text-xs text-gray-500">${user.email}</p>
                                </div>
                            `;
                            
                            div.addEventListener('click', () => {
                                selectUser(user);
                                resultsDiv.classList.add('hidden');
                                searchInput.value = '';
                            });
                            
                            resultsDiv.appendChild(div);
                        });
                    }
                    resultsDiv.classList.remove('hidden');
                });
        }, 300);
    });

    function selectUser(user) {
        if (selectedUsers.has(user.id)) return;
        
        selectedUsers.set(user.id, user);
        renderSelectedUsers();
    }

    function removeUser(userId) {
        selectedUsers.delete(userId);
        renderSelectedUsers();
    }

    function renderSelectedUsers() {
        selectedContainer.innerHTML = '';
        hiddenInputsContainer.innerHTML = '';

        selectedUsers.forEach(user => {
            // Badge UI
            const badge = document.createElement('div');
            badge.className = 'inline-flex items-center bg-indigo-100 text-indigo-800 text-sm font-medium px-2.5 py-0.5 rounded-full';
            badge.innerHTML = `
                ${user.name}
                <button type="button" onclick="removeUser(${user.id})" class="ml-1.5 inline-flex items-center justify-center w-4 h-4 text-indigo-400 hover:text-indigo-900 focus:outline-none">
                    <span class="sr-only">Remove</span>
                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                </button>
            `;
            selectedContainer.appendChild(badge);

            // Hidden Input
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'recipients[]';
            input.value = user.id;
            hiddenInputsContainer.appendChild(input);
        });
    }

    // Close modal on click outside
    window.onclick = function(event) {
        if (event.target == modal) {
            closeNewMessageModal();
        }
    }
</script>
@endsection






