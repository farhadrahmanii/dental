// Service Worker for Dental Practice PWA - Full Offline Support with Filament
const CACHE_NAME = 'dental-pwa-v4';
const RUNTIME_CACHE = 'dental-runtime-v4';
const FILAMENT_CACHE = 'filament-assets-v4';
const FILAMENT_PAGES_CACHE = 'filament-pages-v4'; // Dedicated cache for FilamentPHP pages (30 days)
const IMAGES_CACHE = 'images-cache-v4';

// Cache expiration: 30 days in milliseconds
const FILAMENT_CACHE_EXPIRATION = 30 * 24 * 60 * 60 * 1000; // 30 days

// Essential assets to cache on install
const CACHE_FILES = [
  '/',
  '/admin',
  '/admin/login',
  '/manifest.json',
  '/pwa-192x192.svg',
  '/favicon.ico',
];

// Install event - cache essential files
self.addEventListener('install', (event) => {
  console.log('🔧 Service Worker: Installing...');
  
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => {
        console.log('📦 Service Worker: Caching essential files...');
        return cache.addAll(CACHE_FILES).catch((error) => {
          console.warn('⚠️ Some files failed to cache:', error);
          // Cache what we can
          return Promise.allSettled(
            CACHE_FILES.map(url => 
              cache.add(url).catch(err => {
                console.warn(`Failed to cache ${url}:`, err);
                return null;
              })
            )
          );
        });
      })
      .then(() => {
        console.log('✅ Service Worker: Installation complete');
        return self.skipWaiting();
      })
      .catch((error) => {
        console.error('❌ Service Worker: Installation failed', error);
      })
  );
});

// Activate event - clean up old caches
self.addEventListener('activate', (event) => {
  console.log('🚀 Service Worker: Activating...');
  
  event.waitUntil(
    caches.keys()
      .then((cacheNames) => {
        return Promise.all(
          cacheNames.map((cacheName) => {
            if (cacheName !== CACHE_NAME && 
                cacheName !== RUNTIME_CACHE && 
                cacheName !== FILAMENT_CACHE &&
                cacheName !== FILAMENT_PAGES_CACHE &&
                cacheName !== IMAGES_CACHE) {
              console.log('🗑️ Service Worker: Cleaning up old cache:', cacheName);
              return caches.delete(cacheName);
            }
          })
        );
      })
      .then(() => {
        console.log('✅ Service Worker: Activated and ready');
        return self.clients.claim();
      })
  );
});

// Fetch event - serve from cache when offline, network when online
self.addEventListener('fetch', (event) => {
  // Skip non-GET requests and browser extension requests
  if (
    event.request.method !== 'GET' || 
    event.request.url.startsWith('chrome-extension://') ||
    event.request.url.startsWith('moz-extension://') ||
    event.request.url.startsWith('data:') ||
    event.request.url.startsWith('blob:')
  ) {
    return;
  }

  const url = new URL(event.request.url);
  
  // Skip cross-origin requests (except same origin)
  if (url.origin !== location.origin) {
    return;
  }

  // Handle Filament admin routes - Cache all admin pages with 30-day expiration
  if (url.pathname.startsWith('/admin')) {
    event.respondWith(
      filamentPageStrategy(event.request)
        .catch(() => {
          // Try to return cached admin page (even if expired)
          return caches.match(event.request).then((cached) => {
            if (cached) return cached;
            // Fallback to admin login page
            return caches.match('/admin/login').then((loginPage) => {
              if (loginPage) return loginPage;
              // Ultimate fallback
              return caches.match('/admin');
            });
          });
        })
    );
    return;
  }

  // Handle Filament assets (CSS, JS, fonts) - CacheFirst strategy
  if (url.pathname.includes('/build/assets/theme-') ||
      url.pathname.includes('/build/assets/filament-') ||
      url.pathname.includes('/js/filament/') ||
      url.pathname.includes('/css/filament/') ||
      url.pathname.includes('/fonts/filament/')) {
    event.respondWith(
      cacheFirstStrategy(event.request, FILAMENT_CACHE)
    );
    return;
  }

  // Handle storage images (profile images, etc.) - CacheFirst strategy
  if (url.pathname.startsWith('/storage/') || 
      url.pathname.includes('/storage/app/public/') ||
      url.pathname.match(/\/storage\/[^\/]+\/[^\/]+\.(jpg|jpeg|png|gif|webp|svg)$/i)) {
    event.respondWith(
      cacheFirstStrategy(event.request, IMAGES_CACHE)
        .catch(() => {
          // Return placeholder image if not cached
          return new Response(
            '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200"><rect width="200" height="200" fill="#ddd"/><text x="50%" y="50%" text-anchor="middle" dy=".3em" fill="#999" font-family="Arial" font-size="14">No Image</text></svg>',
            {
              status: 200,
              headers: new Headers({ 'Content-Type': 'image/svg+xml' })
            }
          );
        })
    );
    return;
  }

  // Handle Livewire requests (Filament uses Livewire) - Cache for 30 days
  if (event.request.headers.get('X-Livewire') || 
      url.searchParams.has('livewire') ||
      event.request.url.includes('/livewire/')) {
    event.respondWith(
      filamentPageStrategy(event.request)
        .catch(() => {
          // Return cached response if available
          return caches.match(event.request);
        })
    );
    return;
  }

  // Handle API requests - NetworkFirst strategy with cache fallback
  if (event.request.url.includes('/api/')) {
    event.respondWith(
      networkFirstStrategy(event.request, RUNTIME_CACHE)
    );
    return;
  }

  // Handle HTML pages - NetworkFirst with cache fallback for full offline support
  if (event.request.destination === 'document' || 
      event.request.headers.get('accept')?.includes('text/html')) {
    event.respondWith(
      networkFirstStrategy(event.request, RUNTIME_CACHE)
        .catch(() => {
          // If network fails and not in cache, return cached home page
          return caches.match(event.request).then((cached) => {
            if (cached) return cached;
            return caches.match('/').then((cachedHome) => {
              if (cachedHome) {
                return cachedHome;
              }
              // Ultimate fallback
              return new Response(
                '<!DOCTYPE html><html><head><title>Offline</title></head><body><h1>You are offline</h1><p>Please check your internet connection.</p><button onclick="location.reload()">Retry</button></body></html>',
                {
                  status: 200,
                  headers: new Headers({ 'Content-Type': 'text/html' })
                }
              );
            });
          });
        })
    );
    return;
  }

  // Handle static assets (CSS, JS, images, fonts) - CacheFirst strategy
  if (event.request.destination === 'style' ||
      event.request.destination === 'script' ||
      event.request.destination === 'image' ||
      event.request.destination === 'font' ||
      event.request.url.includes('/build/') ||
      event.request.url.includes('/css/') ||
      event.request.url.includes('/js/') ||
      event.request.url.includes('/images/') ||
      event.request.url.includes('/fonts/')) {
    event.respondWith(
      cacheFirstStrategy(event.request, CACHE_NAME)
    );
    return;
  }

  // Default: NetworkFirst strategy for everything else
  event.respondWith(
    networkFirstStrategy(event.request, RUNTIME_CACHE)
  );
});

// NetworkFirst strategy - try network first, fallback to cache
async function networkFirstStrategy(request, cacheName) {
  try {
    const networkResponse = await fetch(request);
    
    // If network response is ok, cache it and return
    if (networkResponse && networkResponse.status === 200) {
      const cache = await caches.open(cacheName);
      // Clone response before caching
      const responseToCache = networkResponse.clone();
      cache.put(request, responseToCache).catch(err => {
        console.warn('Failed to cache response:', err);
      });
      return networkResponse;
    }
    
    // If network response is not ok, try cache
    throw new Error('Network response not ok');
  } catch (error) {
    // Network failed, try to return from cache
    const cachedResponse = await caches.match(request);
    if (cachedResponse) {
      return cachedResponse;
    }
    throw error;
  }
}

// CacheFirst strategy - try cache first, fallback to network
async function cacheFirstStrategy(request, cacheName) {
  const cachedResponse = await caches.match(request);
  
  if (cachedResponse) {
    return cachedResponse;
  }
  
  try {
    const networkResponse = await fetch(request);
    
    if (networkResponse && networkResponse.status === 200) {
      const cache = await caches.open(cacheName);
      const responseToCache = networkResponse.clone();
      cache.put(request, responseToCache).catch(err => {
        console.warn('Failed to cache response:', err);
      });
    }
    
    return networkResponse;
  } catch (error) {
    // If both cache and network fail, return a basic response
    if (request.destination === 'image') {
      return new Response('', { status: 404 });
    }
    throw error;
  }
}

// FilamentPHP page strategy - CacheFirst with 30-day expiration
async function filamentPageStrategy(request) {
  const cache = await caches.open(FILAMENT_PAGES_CACHE);
  const cachedResponse = await cache.match(request);
  
  // Check if cached response exists and is still valid (within 30 days)
  if (cachedResponse) {
    const cachedTimestamp = cachedResponse.headers.get('X-Cache-Date');
    if (cachedTimestamp) {
      const cacheAge = Date.now() - parseInt(cachedTimestamp);
      if (cacheAge < FILAMENT_CACHE_EXPIRATION) {
        console.log('✅ Service Worker: Serving FilamentPHP page from cache (age: ' + Math.floor(cacheAge / (24 * 60 * 60 * 1000)) + ' days)');
        return cachedResponse;
      } else {
        console.log('⏰ Service Worker: FilamentPHP page cache expired, fetching fresh');
        // Cache expired, but continue to try network
      }
    } else {
      // No timestamp, treat as valid (backward compatibility)
      return cachedResponse;
    }
  }
  
  try {
    // Fetch fresh content from network (cache expired or not found)
    const networkResponse = await fetch(request);
    
    if (networkResponse && networkResponse.status === 200) {
      // Clone response and add cache timestamp header
      const responseToCache = networkResponse.clone();
      const headers = new Headers(responseToCache.headers);
      headers.set('X-Cache-Date', Date.now().toString());
      
      // Create new response with timestamp header
      const cachedResponseWithTimestamp = new Response(responseToCache.body, {
        status: responseToCache.status,
        statusText: responseToCache.statusText,
        headers: headers
      });
      
      // Store in cache with timestamp
      cache.put(request, cachedResponseWithTimestamp).catch(err => {
        console.warn('Failed to cache FilamentPHP page:', err);
      });
      
      console.log('💾 Service Worker: Cached FilamentPHP page for 30 days');
      return networkResponse;
    }
    
    // If network response not ok, return cached if available (even if expired)
    if (cachedResponse) {
      return cachedResponse;
    }
    
    throw new Error('Network response not ok');
  } catch (error) {
    // Network failed, return cached response if available (even if expired)
    if (cachedResponse) {
      console.log('⚠️ Service Worker: Network failed, serving expired FilamentPHP cache');
      return cachedResponse;
    }
    throw error;
  }
}

// Background sync for offline data
self.addEventListener('sync', (event) => {
  console.log('🔄 Service Worker: Background sync', event.tag);
  
  if (event.tag === 'background-sync') {
    event.waitUntil(syncOfflineData());
  }
  
  if (event.tag.startsWith('sync-form-')) {
    event.waitUntil(syncFormData(event.tag));
  }
});

// Push notifications
self.addEventListener('push', (event) => {
  console.log('📬 Service Worker: Push event', event);
  
  const options = {
    body: event.data ? event.data.text() : 'New notification from Dental Practice',
    icon: '/pwa-192x192.svg',
    badge: '/favicon.ico',
    vibrate: [100, 50, 100],
    data: {
      dateOfArrival: Date.now(),
      primaryKey: 1
    },
    actions: [
      {
        action: 'explore',
        title: 'View App',
        icon: '/favicon.ico'
      },
      {
        action: 'close',
        title: 'Close',
        icon: '/favicon.ico'
      }
    ]
  };
  
  event.waitUntil(
    self.registration.showNotification('Dental Practice', options)
  );
});

// Notification click handler
self.addEventListener('notificationclick', (event) => {
  console.log('🔔 Service Worker: Notification click', event);
  
  event.notification.close();
  
  if (event.action === 'explore') {
    event.waitUntil(
      clients.openWindow('/')
    );
  }
});

// Helper function to sync offline data
async function syncOfflineData() {
  try {
    console.log('🔄 Service Worker: Syncing offline data');
    
    const clientsList = await self.clients.matchAll();
    clientsList.forEach(client => {
      client.postMessage({
        type: 'SYNC_OFFLINE_DATA'
      });
    });
  } catch (error) {
    console.error('❌ Service Worker: Sync failed', error);
  }
}

// Helper function to sync form data
async function syncFormData(tag) {
  try {
    console.log('🔄 Service Worker: Syncing form data', tag);
    // This would be handled by the main thread with IndexedDB
    const clientsList = await self.clients.matchAll();
    clientsList.forEach(client => {
      client.postMessage({
        type: 'SYNC_FORM_DATA',
        tag: tag
      });
    });
  } catch (error) {
    console.error('❌ Service Worker: Form sync failed', error);
  }
}

// Message handler for communication with main thread
self.addEventListener('message', (event) => {
  console.log('💬 Service Worker: Message received', event.data);
  
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
  
  if (event.data && event.data.type === 'CACHE_URLS') {
    event.waitUntil(
      caches.open(CACHE_NAME)
        .then((cache) => {
          return cache.addAll(event.data.urls);
        })
    );
  }
  
  if (event.data && event.data.type === 'CACHE_FILAMENT_PAGE') {
    event.waitUntil(
      caches.open(FILAMENT_PAGES_CACHE)
        .then((cache) => {
          // Add timestamp to response for expiration tracking
          const response = event.data.response;
          const headers = new Headers(response.headers);
          headers.set('X-Cache-Date', Date.now().toString());
          
          const responseWithTimestamp = new Response(response.body, {
            status: response.status,
            statusText: response.statusText,
            headers: headers
          });
          
          return cache.put(event.data.url, responseWithTimestamp);
        })
    );
  }
  
  if (event.data && event.data.type === 'GET_CACHE_SIZE') {
    event.waitUntil(
      caches.keys().then(cacheNames => {
        return Promise.all(
          cacheNames.map(name => caches.open(name).then(cache => {
            return cache.keys().then(keys => keys.length);
          }))
        );
      }).then(sizes => {
        event.ports[0].postMessage({ sizes });
      })
    );
  }
});
