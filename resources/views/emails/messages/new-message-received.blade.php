# New Message from {{ $senderName }}
 
You have received a new message on the Alumni Portal.
 
---
**{{ $senderName }} wrote:**
 
*"{{ Str::limit($messageContent, 200) }}"*
---
 
<x-mail::button :url="$conversationUrl">
View Conversation
</x-mail::button>
 
Thanks,
{{ config('app.name') }}
