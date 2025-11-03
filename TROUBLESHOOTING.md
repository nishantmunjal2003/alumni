# Troubleshooting Guide

## Page Reloading and Form Data Clearing Issue

### Problem
Forms are losing data after page loads completely.

### Root Causes and Solutions

#### 1. Vite HMR (Hot Module Replacement) - FIXED ✅
**Issue:** `refresh: true` in `vite.config.js` was causing full page reloads.

**Solution:** Changed to selective refresh that only reloads on view/route changes, not on asset changes.

#### 2. Form Data Persistence - ADDED ✅
**Issue:** Browser reloads clear form inputs.

**Solution:** Added `form-persistence.js` that:
- Saves form data to `sessionStorage` before page unload
- Restores form data when page loads
- Clears saved data after successful submission
- Auto-saves every 5 seconds

**How it works:**
- Automatically saves all form data (except forms with `data-no-persist` attribute)
- Restores data when page loads
- Works with text inputs, textareas, selects, checkboxes, radio buttons

**To disable for a specific form:**
```html
<form data-no-persist>
    <!-- This form won't be saved/restored -->
</form>
```

#### 3. Prevent Unwanted Reloads

**Check Browser Console:**
Open browser DevTools (F12) → Console tab
- Look for JavaScript errors
- Check for any `location.reload()` calls
- Check network tab for failed requests

**Common Causes:**
1. **JavaScript Errors:** Fix any console errors
2. **Form Validation:** Ensure forms have proper validation
3. **Alpine.js Issues:** Check if Alpine.js is loading properly
4. **Session Flash Messages:** Flash messages can cause redirects

#### 4. Debugging Steps

**Step 1: Check if it's Vite**
```bash
# Stop Vite dev server
# Run production build
npm run build
# Test if issue persists
```

**Step 2: Check Browser Console**
- Open DevTools (F12)
- Go to Console tab
- Look for errors or warnings

**Step 3: Check Network Tab**
- Open DevTools → Network tab
- Look for failed requests
- Check if any requests cause redirects

**Step 4: Test Form Persistence**
- Fill out a form
- Let page reload automatically
- Check if data is restored
- Check browser console for errors

#### 5. Manual Form Data Recovery

If form data is lost, check browser's sessionStorage:
```javascript
// In browser console:
console.log(sessionStorage);
// Look for keys starting with "form-data-"
```

#### 6. Disable Auto-Reload (Development Only)

If you want to disable Vite's refresh completely during development:

**Option 1:** Change `vite.config.js`:
```js
refresh: false, // Disables all auto-reload
```

**Option 2:** Use production build:
```bash
npm run build
php artisan serve
```

**Option 3:** Use `npm run dev` but be aware it will auto-reload on file changes

### Best Practices

1. **Save frequently:** Form data auto-saves every 5 seconds
2. **Validate early:** Add client-side validation to catch errors before submission
3. **Use autosave for long forms:** Consider implementing autosave for complex forms
4. **Test in production:** Build and test to ensure persistence works in production too

### Additional Notes

- Form persistence uses `sessionStorage` (cleared when browser tab closes)
- Data is automatically cleared after successful form submission
- Works with all standard HTML form elements
- Does NOT save file uploads (browser security restriction)

### Still Having Issues?

1. Clear browser cache
2. Clear sessionStorage: `sessionStorage.clear()`
3. Check browser console for errors
4. Test in incognito/private mode
5. Check if issue occurs in production build too


