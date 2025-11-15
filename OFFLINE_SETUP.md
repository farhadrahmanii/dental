# Full Offline Support Setup

## Overview
The entire application now works offline without internet connection. All routes, pages, and assets are cached and available offline.

## What's Changed

### 1. Service Worker (`public/sw.js`)
- **Updated**: Caches ALL routes and pages dynamically
- **Strategy**: 
  - **HTML Pages**: NetworkFirst (tries network, falls back to cache)
  - **Static Assets** (CSS, JS, images): CacheFirst (uses cache first)
  - **API Requests**: NetworkFirst with cache fallback
- **Removed**: `/pwa` specific offline page redirect
- **Added**: Full application offline support

### 2. Manifest (`public/manifest.json`)
- **Changed**: `start_url` from `/pwa` to `/`
- **Updated**: All shortcuts to use root URLs instead of `/pwa`

### 3. Service Worker Registration
- **Added**: Service worker registration in main layout (`resources/views/layouts/app.blade.php`)
- **Scope**: `/` (entire application)
- **Automatic**: Registers on all pages using the main layout

### 4. Vite PWA Configuration (`vite.config.js`)
- **Updated**: `start_url` to `/`
- **Enhanced**: Runtime caching strategies for better offline support
- **Added**: Page caching with NetworkFirst strategy

## How It Works

### Caching Strategy

1. **On First Visit** (Online):
   - Service worker installs and caches essential files
   - User navigates the app normally
   - All pages visited are cached automatically

2. **Subsequent Visits** (Online):
   - Pages load from network (fresh content)
   - Responses are cached for offline use

3. **Offline Mode**:
   - All cached pages work instantly
   - Navigation between cached pages works seamlessly
   - API calls return cached data if available
   - Static assets load from cache

### Routes Cached

- ✅ Home page (`/`)
- ✅ About page (`/about`)
- ✅ Services page (`/dental-services`)
- ✅ Contact page (`/contact`)
- ✅ Patients page (`/patients`)
- ✅ Patient detail pages (`/patient/{id}`)
- ✅ Financial dashboard (`/financial/dashboard`)
- ✅ **FilamentPHP admin pages (`/admin/*`) - Cached for 30 days**
- ✅ **Livewire requests (FilamentPHP) - Cached for 30 days**
- ✅ All other routes visited by users

### Assets Cached

- ✅ CSS files (from `/build/assets/`)
- ✅ JavaScript files (from `/build/assets/`)
- ✅ Images (from `/images/`)
- ✅ Fonts (from `/fonts/`)
- ✅ Icons and favicons

## Testing Offline Mode

### 1. Enable Offline Mode in Browser

**Chrome DevTools:**
1. Open DevTools (F12)
2. Go to "Network" tab
3. Check "Offline" checkbox
4. Refresh the page

**Firefox DevTools:**
1. Open DevTools (F12)
2. Go to "Network" tab
3. Click the "Offline" button
4. Refresh the page

### 2. Test Offline Functionality

1. **Visit pages while online** to cache them
2. **Go offline** using browser DevTools
3. **Navigate between pages** - they should work offline
4. **Check console** for service worker messages

### 3. Verify Service Worker

```javascript
// In browser console
navigator.serviceWorker.getRegistration().then(reg => {
  console.log('Service Worker:', reg ? 'Active' : 'Not registered');
});
```

## FilamentPHP 30-Day Cache

### Overview
FilamentPHP admin pages and Livewire requests are cached for **30 days** to improve performance and offline access. This includes:

- All admin panel pages (`/admin/*`)
- Livewire component requests
- FilamentPHP resource pages
- FilamentPHP dashboard

### How It Works

1. **First Visit**: FilamentPHP pages are fetched from the network and cached with a timestamp
2. **Subsequent Visits**: Pages are served from cache if:
   - Cache exists AND
   - Cache age is less than 30 days
3. **After 30 Days**: Cache is considered expired and fresh content is fetched from the network
4. **Offline**: Even expired cache is served if network is unavailable

### Cache Expiration
- Cache expiration is tracked via timestamps stored in response headers
- Automatic expiration check on every request
- Fresh content fetched when cache exceeds 30 days
- Console logs indicate cache status for debugging

### Cache Storage
- Dedicated cache: `filament-pages-v4`
- Separate from other page caches for better management
- Automatic cleanup of expired entries

## Cache Management

### Clearing Cache

If you need to clear the cache:

```javascript
// In browser console
caches.keys().then(names => {
  names.forEach(name => {
    caches.delete(name);
  });
});
```

### Checking Cache Size

```javascript
// In browser console
caches.keys().then(names => {
  Promise.all(names.map(name => 
    caches.open(name).then(cache => 
      cache.keys().then(keys => ({ name, count: keys.length }))
    )
  )).then(console.log);
});
```

## Deployment Notes

### Production Build

1. **Build assets**:
   ```bash
   npm run build
   ```

2. **Deploy**:
   - Upload `public/sw.js` (service worker)
   - Upload `public/manifest.json` (manifest)
   - Upload `public/build/` directory (built assets)

3. **Verify**:
   - Check that service worker registers
   - Test offline functionality
   - Verify all routes work offline

### Cache Version

The cache version is stored in `CACHE_NAME` in `public/sw.js`. When you update the app:
1. Change the cache version number
2. Old caches will be automatically cleaned up
3. New cache will be created

## Troubleshooting

### Service Worker Not Registering

1. Check HTTPS (required for service workers)
2. Check browser console for errors
3. Verify `public/sw.js` is accessible
4. Check file permissions

### Pages Not Caching

1. Visit pages while online first
2. Check service worker is active
3. Verify cache in DevTools > Application > Cache Storage

### Offline Not Working

1. Ensure service worker is installed
2. Check that pages were visited while online
3. Verify cache contains the pages
4. Check browser console for errors

## Features

- ✅ Full application works offline
- ✅ All routes cached dynamically
- ✅ **FilamentPHP admin pages cached for 30 days**
- ✅ **Livewire requests (FilamentPHP) cached for 30 days**
- ✅ Static assets cached
- ✅ API responses cached
- ✅ Automatic cache updates
- ✅ Cache expiration tracking (30 days for FilamentPHP)
- ✅ Offline indicator
- ✅ Background sync support
- ✅ Push notification support

## Next Steps

1. Test offline functionality thoroughly
2. Monitor cache usage
3. Adjust cache strategies if needed
4. Add offline data synchronization if required

