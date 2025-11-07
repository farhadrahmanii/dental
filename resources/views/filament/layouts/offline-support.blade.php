{{-- Filament Offline Support --}}
@if(config('app.env') !== 'testing')
<script>
    // Filament Offline Support - Auto-loads on all Filament pages
    (function() {
        'use strict';
        
        // Initialize IndexedDB for offline storage
        let offlineDB = null;
        const initDB = () => {
            return new Promise((resolve, reject) => {
                const request = indexedDB.open('dental-offline-db', 1);
                request.onerror = () => reject(request.error);
                request.onsuccess = () => {
                    offlineDB = request.result;
                    resolve(offlineDB);
                };
                request.onupgradeneeded = (e) => {
                    const db = e.target.result;
                    if (!db.objectStoreNames.contains('pendingSubmissions')) {
                        db.createObjectStore('pendingSubmissions', { keyPath: 'id', autoIncrement: true });
                    }
                    if (!db.objectStoreNames.contains('cachedImages')) {
                        db.createObjectStore('cachedImages', { keyPath: 'url' });
                    }
                };
            });
        };
        
        // Cache current Filament page
        const cacheCurrentPage = async () => {
            if ('serviceWorker' in navigator && navigator.serviceWorker.controller) {
                try {
                    const html = document.documentElement.outerHTML;
                    const response = new Response(html, {
                        headers: { 'Content-Type': 'text/html' }
                    });
                    
                    navigator.serviceWorker.controller.postMessage({
                        type: 'CACHE_FILAMENT_PAGE',
                        url: window.location.href
                    });
                } catch (err) {
                    console.warn('Failed to cache page:', err);
                }
            }
        };
        
        // Cache all images on page (including profile images)
        const cacheAllImages = async () => {
            const images = document.querySelectorAll('img[src]');
            const imageCache = await caches.open('images-cache-v3');
            
            images.forEach(async (img) => {
                if (img.src.startsWith(window.location.origin) && !img.dataset.cached) {
                    img.dataset.cached = 'true';
                    try {
                        const response = await fetch(img.src);
                        if (response.ok) {
                            await imageCache.put(img.src, response);
                        }
                    } catch (err) {
                        // Silently fail
                    }
                }
            });
        };
        
        // Store pending form submission
        const storePendingSubmission = async (formData) => {
            try {
                if (!offlineDB) {
                    await initDB();
                }
                
                return new Promise((resolve, reject) => {
                    const transaction = offlineDB.transaction(['pendingSubmissions'], 'readwrite');
                    const store = transaction.objectStore('pendingSubmissions');
                    
                    const submission = {
                        url: formData.url,
                        method: formData.method,
                        data: formData.data,
                        csrfToken: formData.csrfToken,
                        headers: formData.headers,
                        timestamp: Date.now(),
                        synced: false
                    };
                    
                    const request = store.add(submission);
                    request.onsuccess = () => {
                        console.log('✅ Form submission stored for offline sync:', request.result);
                        resolve(request.result);
                    };
                    request.onerror = () => {
                        console.error('❌ Failed to store submission:', request.error);
                        reject(request.error);
                    };
                });
            } catch (err) {
                console.error('Failed to store submission:', err);
                // Fallback to localStorage if IndexedDB fails
                try {
                    const submissions = JSON.parse(localStorage.getItem('pendingSubmissions') || '[]');
                    submissions.push({
                        ...formData,
                        id: Date.now(),
                        timestamp: Date.now(),
                        synced: false
                    });
                    localStorage.setItem('pendingSubmissions', JSON.stringify(submissions));
                    console.log('✅ Form submission stored in localStorage');
                } catch (e) {
                    console.error('Failed to store in localStorage:', e);
                }
            }
        };
        
        // Intercept form submissions when offline
        document.addEventListener('submit', async (e) => {
            // Only intercept Filament forms in admin area
            if (!window.location.pathname.startsWith('/admin')) {
                return;
            }
            
            if (!navigator.onLine) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                
                const form = e.target;
                const formData = new FormData(form);
                
                // Convert FormData to object (handle files separately)
                const data = {};
                const files = {};
                
                for (let [key, value] of formData.entries()) {
                    if (value instanceof File) {
                        if (!files[key]) files[key] = [];
                        files[key].push({
                            name: value.name,
                            type: value.type,
                            size: value.size
                        });
                    } else {
                        data[key] = value;
                    }
                }
                
                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || 
                                 document.querySelector('input[name="_token"]')?.value || '';
                
                // Store for later sync
                try {
                    await storePendingSubmission({
                        url: form.action || window.location.href,
                        method: form.method || 'POST',
                        data: { ...data, _files: files },
                        csrfToken: csrfToken,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                    
                    // Show Filament notification if available
                    if (window.Livewire && window.Livewire.dispatch) {
                        window.Livewire.dispatch('notify', {
                            type: 'warning',
                            title: 'Offline Mode',
                            body: 'Your submission has been saved and will be synced when you are back online.'
                        });
                    } else if (window.$wire && window.$wire.dispatch) {
                        window.$wire.dispatch('notify', {
                            type: 'warning',
                            title: 'Offline Mode',
                            body: 'Your submission has been saved and will be synced when you are back online.'
                        });
                    } else {
                        // Fallback notification
                        const notification = document.createElement('div');
                        notification.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #ff9500; color: white; padding: 16px; border-radius: 8px; z-index: 99999; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
                        notification.innerHTML = '<strong>Offline Mode</strong><br>Your submission has been saved and will be synced when you are back online.';
                        document.body.appendChild(notification);
                        setTimeout(() => notification.remove(), 5000);
                    }
                } catch (err) {
                    console.error('Failed to store submission:', err);
                    alert('Failed to save submission offline. Please try again when you have internet connection.');
                }
                
                return false;
            }
        }, true);
        
        // Cache page and images on load
        const initOfflineSupport = () => {
            setTimeout(() => {
                cacheCurrentPage();
                cacheAllImages();
            }, 1000);
        };
        
        // Run initialization
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initOfflineSupport);
        } else {
            initOfflineSupport();
        }
        
        // Observe for new images (Livewire updates)
        if (window.MutationObserver) {
            const observer = new MutationObserver(() => {
                cacheAllImages();
            });
            observer.observe(document.body, { childList: true, subtree: true });
        }
        
        // Cache on Livewire updates
        if (window.Livewire) {
            document.addEventListener('livewire:load', () => {
                setTimeout(() => {
                    cacheCurrentPage();
                    cacheAllImages();
                }, 500);
            });
        }
        
        // Sync pending data when back online
        const syncPendingData = async () => {
            if (!offlineDB) {
                await initDB();
            }
            
            try {
                const transaction = offlineDB.transaction(['pendingSubmissions'], 'readonly');
                const store = transaction.objectStore('pendingSubmissions');
                const index = store.index('synced');
                const request = index.getAll(false); // Get unsynced submissions
                
                request.onsuccess = async () => {
                    const submissions = request.result || [];
                    console.log(`🔄 Found ${submissions.length} pending submissions to sync`);
                    
                    for (const submission of submissions) {
                        try {
                            const response = await fetch(submission.url, {
                                method: submission.method || 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': submission.csrfToken,
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                    ...submission.headers
                                },
                                body: JSON.stringify(submission.data)
                            });
                            
                            if (response.ok) {
                                // Mark as synced
                                const updateTransaction = offlineDB.transaction(['pendingSubmissions'], 'readwrite');
                                const updateStore = updateTransaction.objectStore('pendingSubmissions');
                                submission.synced = true;
                                submission.syncedAt = Date.now();
                                await new Promise((resolve, reject) => {
                                    const updateRequest = updateStore.put(submission);
                                    updateRequest.onsuccess = () => resolve();
                                    updateRequest.onerror = () => reject(updateRequest.error);
                                });
                                
                                console.log('✅ Synced submission:', submission.id);
                            } else {
                                console.warn('⚠️ Failed to sync submission:', submission.id, response.status);
                            }
                        } catch (error) {
                            console.error('❌ Error syncing submission:', submission.id, error);
                        }
                    }
                    
                    if (submissions.length > 0) {
                        // Show notification
                        if (window.Livewire && window.Livewire.dispatch) {
                            window.Livewire.dispatch('notify', {
                                type: 'success',
                                title: 'Sync Complete',
                                body: `Synced ${submissions.length} pending submission(s).`
                            });
                        }
                    }
                };
            } catch (error) {
                console.error('❌ Error syncing pending data:', error);
            }
        };
        
        window.addEventListener('online', async () => {
            console.log('🌐 Back online - Syncing pending data...');
            await syncPendingData();
        });
        
        // Also try to sync on page load if online
        if (navigator.onLine) {
            setTimeout(syncPendingData, 2000);
        }
    })();
</script>
@endif

