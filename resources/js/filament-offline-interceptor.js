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
        const self = this;

        // Intercept Livewire v3 commit method
        if (window.Livewire) {
            // Wait for Livewire to be fully initialized
            const initLivewireInterception = () => {
                // Intercept Livewire's commit method (v3)
                if (window.Livewire.committer) {
                    const originalCommit = window.Livewire.committer.commit;
                    if (originalCommit) {
                        window.Livewire.committer.commit = async function(...args) {
                            if (!navigator.onLine) {
                                try {
                                    const payload = args[0] || {};
                                    const component = payload.component || payload;
                                    
                                    // Extract form data from Livewire payload
                                    if (component && component.fingerprint && component.fingerprint.name) {
                                        const componentName = component.fingerprint.name;
                                        
                                        // Check if it's a Filament form component
                                        if (componentName.includes('Filament') || componentName.includes('EditRecord') || componentName.includes('CreateRecord')) {
                                            // Extract form data from updates
                                            const updates = component.updates || {};
                                            const snapshot = component.snapshot || {};
                                            
                                            // Get the current URL to determine model type
                                            const currentUrl = window.location.href;
                                            const modelType = self.getModelTypeFromUrl(currentUrl);
                                            
                                            if (modelType) {
                                                // Extract form data from Livewire updates
                                                const formData = {};
                                                
                                                // Merge updates into form data
                                                Object.keys(updates).forEach(key => {
                                                    if (!key.startsWith('_') && key !== 'mountedFormComponentActionsData') {
                                                        formData[key] = updates[key];
                                                    }
                                                });
                                                
                                                // Also check snapshot data
                                                if (snapshot.data) {
                                                    Object.keys(snapshot.data).forEach(key => {
                                                        if (!key.startsWith('_') && !formData.hasOwnProperty(key)) {
                                                            formData[key] = snapshot.data[key];
                                                        }
                                                    });
                                                }
                                                
                                                // Determine operation from URL
                                                let operation = 'create';
                                                const idMatch = currentUrl.match(/\/(\d+)(?:\/|$)/);
                                                const serverId = idMatch ? parseInt(idMatch[1]) : null;
                                                
                                                if (serverId) {
                                                    operation = 'update';
                                                }
                                                
                                                // Save offline
                                                if (operation === 'update' && serverId) {
                                                    await window.offlineDB.updateRecord(modelType, serverId, formData);
                                                } else {
                                                    await window.offlineDB.saveRecord(modelType, formData, operation);
                                                }
                                                
                                                // Show notification
                                                self.showNotification('Offline Mode', 
                                                    'Your ' + operation + ' operation has been saved and will be synced when you are back online.', 
                                                    'warning'
                                                );
                                                
                                                // Return mock success response
                                                return Promise.resolve({
                                                    effects: {
                                                        html: document.body.innerHTML,
                                                        dirty: []
                                                    }
                                                });
                                            }
                                        }
                                    }
                                } catch (error) {
                                    console.error('Failed to intercept Livewire commit:', error);
                                }
                            }
                            
                            // Online - proceed with normal commit
                            return originalCommit.apply(this, args);
                        };
                    }
                }
            };
            
            // Try to initialize immediately
            if (window.Livewire.committer) {
                initLivewireInterception();
            } else {
                // Wait for Livewire to initialize
                document.addEventListener('livewire:init', () => {
                    setTimeout(initLivewireInterception, 100);
                });
            }
        }

        // Intercept fetch requests (Livewire uses fetch for /livewire/update and /livewire/commit)
        const originalFetch = window.fetch;

        window.fetch = async function(...args) {
            const url = args[0];
            const options = args[1] || {};

            // Check if offline and it's a POST request to Livewire endpoints
            if (!navigator.onLine && options.method === 'POST') {
                const urlString = typeof url === 'string' ? url : url.url || '';
                
                // Intercept Livewire update/commit requests
                if (urlString.includes('/livewire/update') || urlString.includes('/livewire/commit')) {
                    try {
                        // Parse the request body
                        let payload = {};
                        if (options.body) {
                            if (options.body instanceof FormData) {
                                // Convert FormData to object
                                for (let [key, value] of options.body.entries()) {
                                    if (key === 'components' || key === 'snapshot') {
                                        try {
                                            payload[key] = JSON.parse(value);
                                        } catch (e) {
                                            payload[key] = value;
                                        }
                                    } else {
                                        payload[key] = value;
                                    }
                                }
                            } else if (typeof options.body === 'string') {
                                try {
                                    payload = JSON.parse(options.body);
                                } catch (e) {
                                    // Try URL-encoded format
                                    const params = new URLSearchParams(options.body);
                                    params.forEach((value, key) => {
                                        if (key === 'components' || key === 'snapshot') {
                                            try {
                                                payload[key] = JSON.parse(value);
                                            } catch (e2) {
                                                payload[key] = value;
                                            }
                                        } else {
                                            payload[key] = value;
                                        }
                                    });
                                }
                            } else {
                                payload = options.body;
                            }
                        }
                        
                        // Extract component data
                        const components = payload.components || [];
                        const currentUrl = window.location.href;
                        const modelType = self.getModelTypeFromUrl(currentUrl);
                        
                        if (modelType) {
                            // Try to extract form data from Livewire payload
                            let formData = {};
                            
                            if (components.length > 0) {
                                // Get the first component (usually the form component)
                                const component = components[0];
                                const updates = component.updates || {};
                                const snapshot = component.snapshot || {};
                                
                                // Merge updates
                                Object.keys(updates).forEach(key => {
                                    if (!key.startsWith('_') && 
                                        key !== 'mountedFormComponentActionsData' &&
                                        key !== 'mountedFormComponentActions' &&
                                        !key.includes('$')) {
                                        formData[key] = updates[key];
                                    }
                                });
                                
                                // Merge snapshot data (form state)
                                if (snapshot.data) {
                                    Object.keys(snapshot.data).forEach(key => {
                                        if (!key.startsWith('_') && 
                                            !formData.hasOwnProperty(key) &&
                                            !key.includes('$')) {
                                            formData[key] = snapshot.data[key];
                                        }
                                    });
                                }
                                
                                // Also check serverMemo for form data
                                if (snapshot.serverMemo && snapshot.serverMemo.data) {
                                    Object.keys(snapshot.serverMemo.data).forEach(key => {
                                        if (!key.startsWith('_') && 
                                            !formData.hasOwnProperty(key) &&
                                            !key.includes('$')) {
                                            formData[key] = snapshot.serverMemo.data[key];
                                        }
                                    });
                                }
                            }
                            
                            // Fallback: Extract from DOM form if payload doesn't have enough data
                            if (Object.keys(formData).length === 0 || Object.keys(formData).length < 3) {
                                const form = document.querySelector('form[wire\\:submit], form[wire\\:submit\\.prevent]');
                                if (form) {
                                    const formDataObj = new FormData(form);
                                    for (let [key, value] of formDataObj.entries()) {
                                        if (!key.startsWith('_') && key !== '_token' && key !== '_method') {
                                            formData[key] = value;
                                        }
                                    }
                                }
                            }
                            
                            // Only proceed if we have form data
                            if (Object.keys(formData).length > 0) {
                                // Determine operation
                                let operation = 'create';
                                const idMatch = currentUrl.match(/\/(\d+)(?:\/|$)/);
                                const serverId = idMatch ? parseInt(idMatch[1]) : null;
                                
                                if (serverId) {
                                    operation = 'update';
                                }
                                
                                // Save offline
                                if (operation === 'update' && serverId) {
                                    await window.offlineDB.updateRecord(modelType, serverId, formData);
                                } else {
                                    await window.offlineDB.saveRecord(modelType, formData, operation);
                                }
                                
                                // Show notification
                                self.showNotification('Offline Mode', 
                                    'Your ' + operation + ' operation has been saved and will be synced when you are back online.', 
                                    'warning'
                                );
                                
                                // Return mock success response that Livewire expects
                                // We need to return a response that matches Livewire's expected format
                                const responseData = {
                                    effects: {
                                        html: '',
                                        dirty: []
                                    }
                                };
                                
                                // Add component data if available
                                if (components.length > 0 && components[0].snapshot) {
                                    responseData.serverMemo = components[0].snapshot.serverMemo || {};
                                    responseData.fingerprint = components[0].snapshot.fingerprint || {};
                                }
                                
                                return Promise.resolve(new Response(
                                    JSON.stringify(responseData),
                                    {
                                        status: 200,
                                        statusText: 'OK',
                                        headers: { 'Content-Type': 'application/json' }
                                    }
                                ));
                            }
                        }
                    } catch (error) {
                        console.error('Failed to intercept Livewire fetch:', error);
                    }
                }
                
                // Also intercept admin routes (fallback)
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

            // Online or not intercepted route - proceed normally
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


