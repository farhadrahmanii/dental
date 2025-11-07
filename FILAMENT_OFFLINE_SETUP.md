# Filament Offline Support Setup

## Overview
Full offline support has been added for FilamentPHP admin pages, including:
- ✅ All Filament admin pages work offline
- ✅ Form submissions are queued when offline and synced when online
- ✅ Profile images and storage images are cached and work offline
- ✅ Livewire requests are handled offline
- ✅ All Filament assets (CSS, JS) are cached

## What's Been Added

### 1. Enhanced Service Worker (`public/sw.js`)
- **Filament Routes**: All `/admin/*` routes are cached
- **Filament Assets**: CSS, JS, and fonts from Filament are cached separately
- **Storage Images**: Images from `/storage/` are cached with placeholder fallback
- **Livewire Requests**: Handled with cache fallback

### 2. Offline Storage (`resources/js/offline-storage.js`)
- **IndexedDB**: Stores pending form submissions
- **Image Caching**: Caches images as blobs in IndexedDB
- **Auto-sync**: Syncs pending data when connection is restored

### 3. Filament Offline Support (`resources/views/filament/layouts/offline-support.blade.php`)
- **Auto-loaded**: Injected into all Filament pages via render hook
- **Form Interception**: Intercepts form submissions when offline
- **Image Caching**: Automatically caches all images on Filament pages
- **Page Caching**: Caches Filament pages for offline access

## How It Works

### Caching Strategy

1. **Filament Pages** (`/admin/*`):
   - **Strategy**: NetworkFirst with cache fallback
   - **Behavior**: Tries network first, falls back to cache when offline
   - **Cache**: All visited admin pages are cached automatically

2. **Filament Assets**:
   - **CSS/JS**: CacheFirst strategy
   - **Fonts**: CacheFirst strategy
   - **Cache Name**: `filament-assets-v3`

3. **Storage Images**:
   - **Strategy**: CacheFirst
   - **Fallback**: Placeholder SVG if not cached
   - **Cache Name**: `images-cache-v3`

### Offline Form Submissions

1. **When Offline**:
   - Form submission is intercepted
   - Data is stored in IndexedDB
   - User is notified that submission is queued
   - Form does not submit (prevents errors)

2. **When Online**:
   - Pending submissions are automatically synced
   - Submissions are sent to server
   - Success/error notifications are shown

### Image Caching

1. **On Load**:
   - All images on Filament pages are automatically cached
   - Profile images from `/storage/` are cached
   - Images are stored in both Cache API and IndexedDB

2. **When Offline**:
   - Cached images are served immediately
   - Missing images show placeholder

## Testing Offline Mode

### 1. Test Filament Pages Offline

1. **Visit Filament pages while online**:
   - Go to `/admin`
   - Navigate to different resources (Patients, Treatments, etc.)
   - Visit create/edit pages

2. **Go offline** (DevTools > Network > Offline)

3. **Test navigation**:
   - Navigate between cached Filament pages
   - Pages should load from cache
   - UI should remain functional

### 2. Test Form Submissions Offline

1. **Go to a create/edit page** (e.g., `/admin/patients/create`)

2. **Fill out the form**

3. **Go offline** before submitting

4. **Submit the form**:
   - Submission should be intercepted
   - Notification should appear
   - Data should be stored in IndexedDB

5. **Go back online**:
   - Submissions should sync automatically
   - Check browser console for sync messages

### 3. Test Image Caching

1. **Visit a page with profile images** (e.g., Patient list)

2. **Wait for images to load**

3. **Go offline**

4. **Refresh the page**:
   - Images should still display
   - Cached images load instantly
   - Missing images show placeholder

## Configuration

### Service Worker Cache Versions

Update cache version in `public/sw.js` when making changes:

```javascript
const CACHE_NAME = 'dental-pwa-v3';  // Update version number
const FILAMENT_CACHE = 'filament-assets-v3';
const IMAGES_CACHE = 'images-cache-v3';
```

### IndexedDB Structure

- **pendingSubmissions**: Stores form submissions waiting to sync
- **cachedImages**: Stores image blobs
- **offlineRecords**: Stores offline-created records

## Troubleshooting

### Filament Pages Not Caching

1. **Check service worker**:
   ```javascript
   navigator.serviceWorker.getRegistration().then(reg => {
     console.log('Service Worker:', reg);
   });
   ```

2. **Check cache**:
   - DevTools > Application > Cache Storage
   - Look for `dental-runtime-v3` cache
   - Check if Filament pages are cached

3. **Clear cache and retry**:
   ```javascript
   caches.delete('dental-runtime-v3').then(() => {
     location.reload();
   });
   ```

### Images Not Caching

1. **Check image paths**:
   - Images must be same-origin
   - Check if images are from `/storage/` path

2. **Check console**:
   - Look for image caching errors
   - Check if images are being fetched

3. **Manual cache**:
   ```javascript
   fetch('/storage/path/to/image.jpg')
     .then(r => r.blob())
     .then(blob => {
       caches.open('images-cache-v3').then(cache => {
         cache.put('/storage/path/to/image.jpg', new Response(blob));
       });
     });
   ```

### Form Submissions Not Syncing

1. **Check IndexedDB**:
   - DevTools > Application > IndexedDB
   - Check `dental-offline-db` > `pendingSubmissions`
   - Verify submissions are stored

2. **Check sync**:
   - Go online
   - Check browser console for sync messages
   - Verify submissions are being sent

3. **Manual sync**:
   ```javascript
   // In browser console
   offlineStorage.syncPendingData();
   ```

## Features

- ✅ All Filament admin pages work offline
- ✅ Form submissions queued when offline
- ✅ Automatic sync when back online
- ✅ Profile images cached and work offline
- ✅ Storage images cached with placeholder fallback
- ✅ Livewire requests handled offline
- ✅ Filament assets (CSS, JS) cached
- ✅ Automatic page caching on visit
- ✅ Image caching on load

## Next Steps

1. **Test thoroughly** in offline mode
2. **Monitor IndexedDB** usage
3. **Adjust cache strategies** if needed
4. **Add sync status indicator** in UI
5. **Handle file uploads** offline (advanced)

## Notes

- Service worker only handles GET requests
- POST/PUT/DELETE requests are intercepted in main thread
- Form data is stored in IndexedDB, not cache
- Images are cached in both Cache API and IndexedDB
- Cache version should be updated when making changes

