/**
 * Badge Updater
 * Updates message notification badge count dynamically
 */

(function() {
    'use strict';

    function updateMessageBadge() {
        // Check if user is authenticated
        const messageLink = document.querySelector('a[href*="/messages"]');
        if (!messageLink) return;

        fetch('/messages/unread/count', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            const link = document.querySelector('a[href*="/messages"]');
            if (!link) return;

            let badge = link.querySelector('span.bg-red-500');
            
            if (data.count > 0) {
                if (badge) {
                    badge.textContent = data.count > 9 ? '9+' : data.count;
                    badge.style.display = 'flex';
                } else {
                    // Create badge if it doesn't exist
                    badge = document.createElement('span');
                    badge.className = 'absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center';
                    badge.textContent = data.count > 9 ? '9+' : data.count;
                    link.style.position = 'relative';
                    link.appendChild(badge);
                }
            } else {
                // Hide badge if no unread messages
                if (badge) {
                    badge.style.display = 'none';
                }
            }
        })
        .catch(error => {
            // Silently fail - badge update is not critical
            console.debug('Badge update failed (non-critical):', error);
        });
    }

    // Update badge on page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', updateMessageBadge);
    } else {
        updateMessageBadge();
    }

    // Update badge every 30 seconds
    setInterval(updateMessageBadge, 30000);

    // Update badge after navigation (for SPA-like behavior)
    if (window.history && window.history.pushState) {
        const originalPushState = window.history.pushState;
        window.history.pushState = function() {
            originalPushState.apply(window.history, arguments);
            setTimeout(updateMessageBadge, 100);
        };
    }

    // Expose function globally for manual updates
    window.updateMessageBadge = updateMessageBadge;
})();


