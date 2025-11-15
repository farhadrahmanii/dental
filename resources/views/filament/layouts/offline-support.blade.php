{{-- Filament Offline Support --}}
@if(config('app.env') !== 'testing')
<script>
    // Filament Offline Support - Auto-loads on all Filament pages
    (function() {
        'use strict';
        
        // Wait for offline database to be initialized
        const waitForOfflineDB = () => {
            return new Promise((resolve) => {
                if (window.offlineDB) {
                    resolve(window.offlineDB);
                    return;
                }
                
                const checkInterval = setInterval(() => {
                    if (window.offlineDB) {
                        clearInterval(checkInterval);
                        resolve(window.offlineDB);
                    }
                }, 100);
                
                // Timeout after 5 seconds
                setTimeout(() => {
                    clearInterval(checkInterval);
                    resolve(null);
                }, 5000);
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
        
        // Store pending form submission (using new offline database)
        const storePendingSubmission = async (formData) => {
            try {
                const db = await waitForOfflineDB();
                if (!db) {
                    console.warn('Offline database not available');
                    return;
                }
                
                // Extract model type from URL
                const url = formData.url || window.location.href;
                let modelType = null;
                
                const urlMap = {
                    '/admin/patients': 'patients',
                    '/admin/appointments': 'appointments',
                    '/admin/payments': 'payments',
                    '/admin/services': 'services',
                    '/admin/expenses': 'expenses',
                    '/admin/invoices': 'invoices'
                };
                
                for (const [path, model] of Object.entries(urlMap)) {
                    if (url.includes(path)) {
                        modelType = model;
                        break;
                    }
                }
                
                if (!modelType) {
                    const match = url.match(/\/admin\/(\w+)/);
                    if (match) {
                        modelType = match[1].replace(/s$/, '');
                    }
                }
                
                if (modelType) {
                    // Determine operation
                    let operation = 'create';
                    if (formData.method === 'DELETE' || formData.data._method === 'DELETE') {
                        operation = 'delete';
                    } else if (formData.method === 'PUT' || formData.method === 'PATCH' || formData.data._method) {
                        operation = 'update';
                    } else if (url.match(/\/\d+$/)) {
                        operation = 'update';
                    }
                    
                    // Extract ID if updating/deleting
                    const idMatch = url.match(/\/(\d+)$/);
                    const serverId = idMatch ? parseInt(idMatch[1]) : null;
                    
                    // Save using offline database
                    if (operation === 'delete' && serverId) {
                        await db.deleteRecord(modelType, serverId);
                    } else if (operation === 'update' && serverId) {
                        await db.updateRecord(modelType, serverId, formData.data);
                    } else {
                        await db.saveRecord(modelType, formData.data, operation);
                    }
                    
                    console.log('✅ Form submission stored offline:', modelType, operation);
                }
            } catch (err) {
                console.error('Failed to store submission:', err);
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
        
        // Sync pending data when back online (using new offline database)
        const syncPendingData = async () => {
            const db = await waitForOfflineDB();
            if (!db) {
                console.warn('Offline database not available for sync');
                return;
            }
            
            try {
                const result = await db.syncPendingData();
                if (result.success && result.synced > 0) {
                    localStorage.setItem('last_sync', new Date().toISOString());
                    
                    // Show notification
                    if (window.Livewire && window.Livewire.dispatch) {
                        window.Livewire.dispatch('notify', {
                            type: 'success',
                            title: 'Sync Complete',
                            body: `Synced ${result.synced} pending item(s) successfully.`
                        });
                    }
                }
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
        
        // Wait for offline database to be available
        if (!window.offlineDB) {
            let attempts = 0;
            const maxAttempts = 50; // 5 seconds max
            
            const checkOfflineDB = setInterval(() => {
                attempts++;
                if (window.offlineDB) {
                    clearInterval(checkOfflineDB);
                    console.log('✅ Offline database is now available');
                } else if (attempts >= maxAttempts) {
                    clearInterval(checkOfflineDB);
                    console.warn('⚠️ Offline database module not loaded after 5 seconds. Offline features may not work.');
                }
            }, 100);
        }
    })();
</script>

{{-- Load offline database and interceptor modules --}}
@php
    $manifestPath = public_path('build/manifest.json');
    $hotPath = public_path('hot');
@endphp

@if (file_exists($hotPath))
    {{-- Development: Use Vite dev server --}}
    @vite(['resources/js/offline-database.js', 'resources/js/filament-offline-interceptor.js'])
@elseif (file_exists($manifestPath))
    {{-- Production: These files are bundled in app.js, which is already loaded --}}
    {{-- No need to load separately as they're imported in resources/js/app.js --}}
@endif
@endif

