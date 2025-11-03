/**
 * Form Data Persistence
 * Prevents form data loss on page reloads
 */

(function() {
    'use strict';

    // Save form data to sessionStorage before page unload
    function saveFormData() {
        const forms = document.querySelectorAll('form:not([data-no-persist])');
        
        forms.forEach((form, index) => {
            const formData = new FormData(form);
            const data = {};
            
            // Convert FormData to object
            for (let [key, value] of formData.entries()) {
                if (data[key]) {
                    // Handle multiple values (like checkboxes)
                    if (Array.isArray(data[key])) {
                        data[key].push(value);
                    } else {
                        data[key] = [data[key], value];
                    }
                } else {
                    data[key] = value;
                }
            }
            
            // Save to sessionStorage
            const formId = form.id || form.action || `form-${index}`;
            sessionStorage.setItem(`form-data-${formId}`, JSON.stringify(data));
        });
    }

    // Restore form data from sessionStorage
    function restoreFormData() {
        const forms = document.querySelectorAll('form:not([data-no-persist])');
        
        forms.forEach((form, index) => {
            const formId = form.id || form.action || `form-${index}`;
            const savedData = sessionStorage.getItem(`form-data-${formId}`);
            
            if (savedData) {
                try {
                    const data = JSON.parse(savedData);
                    
                    // Restore form fields
                    Object.keys(data).forEach(key => {
                        const value = data[key];
                        const field = form.querySelector(`[name="${key}"]`);
                        
                        if (field) {
                            if (field.type === 'checkbox' || field.type === 'radio') {
                                if (Array.isArray(value)) {
                                    value.forEach(val => {
                                        const checkbox = form.querySelector(`[name="${key}"][value="${val}"]`);
                                        if (checkbox) checkbox.checked = true;
                                    });
                                } else {
                                    const checkbox = form.querySelector(`[name="${key}"][value="${value}"]`);
                                    if (checkbox) checkbox.checked = true;
                                }
                            } else if (field.tagName === 'SELECT') {
                                field.value = Array.isArray(value) ? value[0] : value;
                            } else if (field.tagName === 'TEXTAREA') {
                                field.value = Array.isArray(value) ? value.join('\n') : value;
                            } else {
                                field.value = Array.isArray(value) ? value[0] : value;
                            }
                        }
                    });
                    
                    // Clear saved data after restoring
                    sessionStorage.removeItem(`form-data-${formId}`);
                } catch (e) {
                    console.error('Error restoring form data:', e);
                }
            }
        });
    }

    // Clear form data after successful submission
    function clearFormData(form) {
        const formId = form.id || form.action || 'form-default';
        sessionStorage.removeItem(`form-data-${formId}`);
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', restoreFormData);
    } else {
        restoreFormData();
    }

    // Save before page unload
    window.addEventListener('beforeunload', saveFormData);

    // Save periodically (every 5 seconds)
    setInterval(saveFormData, 5000);

    // Clear on successful form submission
    document.addEventListener('submit', function(e) {
        const form = e.target;
        
        // Only clear if form is valid and not prevented
        form.addEventListener('submit', function(ev) {
            if (!ev.defaultPrevented) {
                setTimeout(() => clearFormData(form), 100);
            }
        });
    });

    // Expose clear function globally
    window.clearFormData = clearFormData;
})();


