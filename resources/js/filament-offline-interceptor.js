/**
 * Filament Offline Interceptor
 * Intercepts Livewire form submissions and stores them offline
 * Works with all Filament resources (Patients, Appointments, Payments, etc.)
 */

// Import offline database
import offlineDB from './offline-database.js';

class FilamentOfflineInterceptor {
    constructor() {
        this.initialized = false;
        this.init();
    }

    async init() {
        if (this.initialized) return;
        
        // Wait for offline database to be ready
        if (window.offlineDB) {
            this.setupInterceptors();
        } else {
            // Wait a bit for offline database to initialize
            setTimeout(() => {
                if (window.offlineDB) {
                    this.setupInterceptors();
                }
            }, 1000);
        }
    }

    setupInterceptors() {
        // Intercept Livewire form submissions
        this.interceptLivewireSubmissions();
        
        // Intercept standard form submissions
        this.interceptStandardForms();
        
        // Handle Livewire events
        this.handleLivewireEvents();
        
        this.initialized = true;
        console.log('✅ Filament offline interceptor initialized');
    }

    // Get model type from URL
    getModelTypeFromUrl(url) {
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
                return model;
            }
        }

        // Try to extract from URL pattern
        const match = url.match(/\/admin\/(\w+)/);
        if (match) {
            return match[1].replace(/s$/, ''); // Remove plural 's'
        }

        return null;
    }

    // Intercept Livewire form submissions
    interceptLivewireSubmissions() {
        // Intercept Livewire HTTP requests
        if (window.Livewire) {
            // Override Livewire's HTTP methods
            const originalRequest = window.Livewire.request || window.Livewire.http?.request;
            
            if (originalRequest) {
                const self = this;
                
                window.Livewire.request = async function(...args) {
                    // Check if offline
                    if (!navigator.onLine) {
                        const url = args[0] || window.location.href;
                        const options = args[1] || {};
                        
                        // Only intercept if it's a form submission
                        if (options.method && ['POST', 'PUT', 'PATCH', 'DELETE'].includes(options.method)) {
                            const modelType = self.getModelTypeFromUrl(url);
                            
                            if (modelType) {
                                // Extract data from options
                                const formData = options.body || options.data || {};
                                let data = {};
                                
                                if (formData instanceof FormData) {
                                    for (let [key, value] of formData.entries()) {
                                        data[key] = value;
                                    }
                                } else if (typeof formData === 'string') {
                                    try {
                                        data = JSON.parse(formData);
                                    } catch (e) {
                                        // Try URL-encoded format
                                        const params = new URLSearchParams(formData);
                                        params.forEach((value, key) => {
                                            data[key] = value;
                                        });
                                    }
                                } else {
                                    data = formData;
                                }

                                // Determine operation
                                let operation = 'create';
                                if (options.method === 'DELETE') {
                                    operation = 'delete';
                                } else if (options.method === 'PUT' || options.method === 'PATCH') {
                                    operation = 'update';
                                } else if (url.match(/\/\d+$/)) {
                                    operation = 'update';
                                }

                                // Extract ID if updating/deleting
                                const idMatch = url.match(/\/(\d+)$/);
                                const serverId = idMatch ? parseInt(idMatch[1]) : null;

                                // Save offline
                                try {
                                    if (operation === 'delete') {
                                        await window.offlineDB.deleteRecord(modelType, serverId);
                                    } else if (operation === 'update') {
                                        await window.offlineDB.updateRecord(modelType, serverId, data);
                                    } else {
                                        await window.offlineDB.saveRecord(modelType, data, operation);
                                    }

                                    // Show notification
                                    if (window.Livewire && window.Livewire.dispatch) {
                                        window.Livewire.dispatch('notify', {
                                            type: 'warning',
                                            title: 'Offline Mode',
                                            body: 'Your ' + operation + ' operation has been saved and will be synced when you are back online.'
                                        });
                                    }

                                    // Return a mock success response
                                    return Promise.resolve({
                                        ok: true,
                                        status: 200,
                                        json: async () => ({ success: true, offline: true })
                                    });
                                } catch (error) {
                                    console.error('Failed to save offline:', error);
                                    return Promise.reject(error);
                                }
                            }
                        }
                    }

                    // Online - proceed with normal request
                    return originalRequest.apply(this, args);
                };
            }
        }

        // Intercept fetch requests (Livewire uses fetch)
        const originalFetch = window.fetch;
        const self = this;

        window.fetch = async function(...args) {
            const url = args[0];
            const options = args[1] || {};

            // Check if offline and it's a POST/PUT/DELETE request
            if (!navigator.onLine && options.method && ['POST', 'PUT', 'PATCH', 'DELETE'].includes(options.method)) {
                const urlString = typeof url === 'string' ? url : url.url || '';
                
                // Only intercept admin routes
                if (urlString.includes('/admin/')) {
                    const modelType = self.getModelTypeFromUrl(urlString);
                    
                    if (modelType) {
                        // Extract data
                        let data = {};
                        
                        if (options.body) {
                            if (options.body instanceof FormData) {
                                for (let [key, value] of options.body.entries()) {
                                    if (key !== '_token' && key !== '_method') {
                                        data[key] = value;
                                    }
                                }
                            } else if (typeof options.body === 'string') {
                                try {
                                    data = JSON.parse(options.body);
                                } catch (e) {
                                    const params = new URLSearchParams(options.body);
                                    params.forEach((value, key) => {
                                        if (key !== '_token' && key !== '_method') {
                                            data[key] = value;
                                        }
                                    });
                                }
                            } else {
                                data = options.body;
                            }
                        }

                        // Determine operation
                        let operation = 'create';
                        if (options.method === 'DELETE' || data._method === 'DELETE') {
                            operation = 'delete';
                        } else if (options.method === 'PUT' || options.method === 'PATCH' || data._method === 'PUT' || data._method === 'PATCH') {
                            operation = 'update';
                        } else if (urlString.match(/\/\d+$/)) {
                            operation = 'update';
                        }

                        // Extract ID
                        const idMatch = urlString.match(/\/(\d+)$/);
                        const serverId = idMatch ? parseInt(idMatch[1]) : null;

                        // Save offline
                        try {
                            if (operation === 'delete') {
                                await window.offlineDB.deleteRecord(modelType, serverId);
                            } else if (operation === 'update' && serverId) {
                                await window.offlineDB.updateRecord(modelType, serverId, data);
                            } else {
                                await window.offlineDB.saveRecord(modelType, data, operation);
                            }

                            // Show notification
                            self.showNotification('Offline Mode', 
                                'Your ' + operation + ' operation has been saved and will be synced when you are back online.', 
                                'warning'
                            );

                            // Return mock success response
                            return Promise.resolve(new Response(
                                JSON.stringify({ success: true, offline: true }),
                                {
                                    status: 200,
                                    statusText: 'OK',
                                    headers: { 'Content-Type': 'application/json' }
                                }
                            ));
                        } catch (error) {
                            console.error('Failed to save offline:', error);
                            return originalFetch.apply(this, args);
                        }
                    }
                }
            }

            // Online or not admin route - proceed normally
            return originalFetch.apply(this, args);
        };
    }

    // Intercept standard form submissions
    interceptStandardForms() {
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
                
                // Convert FormData to object
                const data = {};
                for (let [key, value] of formData.entries()) {
                    if (key !== '_token' && key !== '_method') {
                        data[key] = value;
                    }
                }

                const url = form.action || window.location.href;
                const method = form.method || formData.get('_method') || 'POST';
                const modelType = this.getModelTypeFromUrl(url);

                if (modelType && window.offlineDB) {
                    try {
                        let operation = 'create';
                        if (method === 'DELETE' || formData.get('_method') === 'DELETE') {
                            operation = 'delete';
                        } else if (method === 'PUT' || method === 'PATCH' || formData.get('_method') === 'PUT' || formData.get('_method') === 'PATCH') {
                            operation = 'update';
                        }

                        const idMatch = url.match(/\/(\d+)$/);
                        const serverId = idMatch ? parseInt(idMatch[1]) : null;

                        if (operation === 'delete' && serverId) {
                            await window.offlineDB.deleteRecord(modelType, serverId);
                        } else if (operation === 'update' && serverId) {
                            await window.offlineDB.updateRecord(modelType, serverId, data);
                        } else {
                            await window.offlineDB.saveRecord(modelType, data, operation);
                        }

                        this.showNotification('Offline Mode',
                            'Your ' + operation + ' operation has been saved and will be synced when you are back online.',
                            'warning'
                        );

                        return false;
                    } catch (error) {
                        console.error('Failed to save offline:', error);
                        this.showNotification('Error',
                            'Failed to save offline. Please try again when you have internet connection.',
                            'error'
                        );
                        return false;
                    }
                }
            }
        }, true);
    }

    // Handle Livewire events
    handleLivewireEvents() {
        if (window.Livewire) {
            // Listen for form submissions
            document.addEventListener('livewire:init', () => {
                console.log('✅ Livewire initialized');
            });

            // Listen for component updates
            document.addEventListener('livewire:update', () => {
                // Cache updated pages
                if ('serviceWorker' in navigator && navigator.serviceWorker.controller) {
                    navigator.serviceWorker.controller.postMessage({
                        type: 'CACHE_FILAMENT_PAGE',
                        url: window.location.href
                    });
                }
            });
        }
    }

    // Show notification
    showNotification(title, body, type = 'info') {
        // Try Livewire notification first
        if (window.Livewire && window.Livewire.dispatch) {
            window.Livewire.dispatch('notify', {
                type: type,
                title: title,
                body: body
            });
            return;
        }

        // Try Filament notification
        if (window.$wire && window.$wire.dispatch) {
            window.$wire.dispatch('notify', {
                type: type,
                title: title,
                body: body
            });
            return;
        }

        // Fallback to custom notification
        const notification = document.createElement('div');
        const colors = {
            warning: '#ff9500',
            success: '#10b981',
            error: '#ef4444',
            info: '#3b82f6'
        };

        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${colors[type] || colors.info};
            color: white;
            padding: 16px 24px;
            border-radius: 8px;
            z-index: 99999;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            max-width: 400px;
            animation: slideIn 0.3s ease-out;
        `;

        notification.innerHTML = `
            <strong>${title}</strong><br>
            ${body}
        `;

        // Add animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(400px); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
        `;
        document.head.appendChild(style);

        document.body.appendChild(notification);
        setTimeout(() => {
            notification.style.animation = 'slideIn 0.3s ease-out reverse';
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }
}

// Initialize interceptor when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        new FilamentOfflineInterceptor();
    });
} else {
    new FilamentOfflineInterceptor();
}

export default FilamentOfflineInterceptor;


