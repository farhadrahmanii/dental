// Service Worker for Dental Practice PWA
const CACHE_NAME = 'dental-pwa-v1';
const OFFLINE_URL = '/pwa';

// Files to cache for offline functionality
const CACHE_FILES = [
  '/',
  '/pwa',
  '/manifest.json',
  '/pwa-192x192.svg',
  '/favicon.ico',
  // Add other static assets as needed
];

// Install event - cache essential files
self.addEventListener('install', (event) => {
  console.log('🔧 Service Worker: Installing...');
  
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => {
        console.log('📦 Service Worker: Caching essential files...');
        return cache.addAll(CACHE_FILES).catch((error) => {
          // If some files fail to cache, continue anyway
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
        console.log('✅ Service Worker: Installation complete - ready for offline use');
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
            if (cacheName !== CACHE_NAME) {
              console.log('🗑️ Service Worker: Cleaning up old cache:', cacheName);
              return caches.delete(cacheName);
            }
          })
        );
      })
      .then(() => {
        console.log('✅ Service Worker: Activated and ready');
        // Notify all clients that service worker is ready
        return self.clients.claim().then(() => {
          // Send message to all clients
          return self.clients.matchAll().then(clients => {
            clients.forEach(client => {
              client.postMessage({
                type: 'SW_READY',
                message: 'Service Worker is ready for offline use'
              });
            });
          });
        });
      })
  );
});

// Fetch event - serve from cache when offline
self.addEventListener('fetch', (event) => {
  // Skip non-GET requests, chrome-extension requests, and data URLs
  if (
    event.request.method !== 'GET' || 
    event.request.url.startsWith('chrome-extension://') ||
    event.request.url.startsWith('data:')
  ) {
    return;
  }
  
  // Handle API requests - NetworkFirst strategy
  if (event.request.url.includes('/api/')) {
    event.respondWith(
      fetch(event.request)
        .then((response) => {
          // If online and response is ok, cache it and return
          if (response.ok) {
            const responseToCache = response.clone();
            caches.open(CACHE_NAME).then((cache) => {
              cache.put(event.request, responseToCache);
            });
            return response;
          }
          throw new Error('Network response was not ok');
        })
        .catch(() => {
          // If offline, try to return cached response
          return caches.match(event.request)
            .then((cachedResponse) => {
              if (cachedResponse) {
                return cachedResponse;
              }
              // Return offline page for failed API requests
              return caches.match(OFFLINE_URL);
            });
        })
    );
    return;
  }
  
  // Handle page and asset requests - CacheFirst strategy for offline support
  event.respondWith(
    caches.match(event.request)
      .then((cachedResponse) => {
        // Return cached version if available (works offline)
        if (cachedResponse) {
          return cachedResponse;
        }
        
        // Otherwise fetch from network
        return fetch(event.request)
          .then((response) => {
            // Check if valid response
            if (!response || response.status !== 200 || response.type === 'error') {
              return response;
            }
            
            // Clone the response for caching
            const responseToCache = response.clone();
            
            // Cache the response for future offline use
            caches.open(CACHE_NAME)
              .then((cache) => {
                cache.put(event.request, responseToCache);
              });
            
            return response;
          })
        .catch((error) => {
          // If offline and no cache available
          console.log('📴 Offline: No cache for', event.request.url);
          
          if (event.request.destination === 'document') {
            // For HTML pages, return offline page
            return caches.match(OFFLINE_URL).then((offlinePage) => {
              if (offlinePage) {
                return offlinePage;
              }
              // Fallback if offline page not cached
              return new Response(
                '<!DOCTYPE html><html><head><title>Offline</title></head><body><h1>You are offline</h1><p>Please check your internet connection.</p><button onclick="location.reload()">Retry</button></body></html>',
                {
                  status: 200,
                  headers: new Headers({
                    'Content-Type': 'text/html'
                  })
                }
              );
            });
          }
          
          // For other assets, return a basic response
          return new Response('Offline', {
            status: 503,
            statusText: 'Service Unavailable',
            headers: new Headers({
              'Content-Type': 'text/plain'
            })
          });
        });
      })
  );
});

// Background sync for offline data
self.addEventListener('sync', (event) => {
  console.log('Service Worker: Background sync', event.tag);
  
  if (event.tag === 'background-sync') {
    event.waitUntil(
      // Sync offline data when connection is restored
      syncOfflineData()
    );
  }
});

// Push notifications
self.addEventListener('push', (event) => {
  console.log('Service Worker: Push event', event);
  
  const options = {
    body: event.data ? event.data.text() : 'New notification from Dental PWA',
    icon: '/pwa-192x192.png',
    badge: '/favicon-32x32.png',
    vibrate: [100, 50, 100],
    data: {
      dateOfArrival: Date.now(),
      primaryKey: 1
    },
    actions: [
      {
        action: 'explore',
        title: 'View App',
        icon: '/favicon-32x32.png'
      },
      {
        action: 'close',
        title: 'Close',
        icon: '/favicon-32x32.png'
      }
    ]
  };
  
  event.waitUntil(
    self.registration.showNotification('Dental Practice PWA', options)
  );
});

// Notification click handler
self.addEventListener('notificationclick', (event) => {
  console.log('Service Worker: Notification click', event);
  
  event.notification.close();
  
  if (event.action === 'explore') {
    event.waitUntil(
      clients.openWindow('/pwa')
    );
  }
});

// Helper function to sync offline data
async function syncOfflineData() {
  try {
    // This would typically communicate with the main thread
    // to get offline data and sync it with the server
    console.log('Service Worker: Syncing offline data');
    
    // Send message to main thread to trigger sync
    const clients = await self.clients.matchAll();
    clients.forEach(client => {
      client.postMessage({
        type: 'SYNC_OFFLINE_DATA'
      });
    });
  } catch (error) {
    console.error('Service Worker: Sync failed', error);
  }
}

// Message handler for communication with main thread
self.addEventListener('message', (event) => {
  console.log('Service Worker: Message received', event.data);
  
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
});
