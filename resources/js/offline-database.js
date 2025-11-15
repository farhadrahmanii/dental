/**
 * Comprehensive Offline Database for Dental Practice
 * Stores all models locally in IndexedDB and syncs when online
 * Persists across browser restarts and computer restarts
 */

class OfflineDatabase {
    constructor() {
        this.dbName = 'dental-practice-db';
        this.dbVersion = 2; // Increment when schema changes
        this.db = null;
        this.initPromise = null;
    }

    async init() {
        if (this.initPromise) {
            return this.initPromise;
        }

        this.initPromise = new Promise((resolve, reject) => {
            const request = indexedDB.open(this.dbName, this.dbVersion);

            request.onerror = () => {
                console.error('❌ IndexedDB error:', request.error);
                reject(request.error);
            };

            request.onsuccess = () => {
                this.db = request.result;
                console.log('✅ Offline database opened successfully');
                resolve(this.db);
            };

            request.onupgradeneeded = (event) => {
                const db = event.target.result;
                const transaction = event.target.transaction;

                // Create object stores for each model
                const models = [
                    'patients',
                    'appointments',
                    'payments',
                    'services',
                    'expenses',
                    'invoices',
                    'treatments',
                    'xrays',
                    'transcriptions'
                ];

                models.forEach(model => {
                    if (!db.objectStoreNames.contains(model)) {
                        const store = db.createObjectStore(model, { keyPath: 'local_id', autoIncrement: true });
                        store.createIndex('server_id', 'server_id', { unique: false });
                        store.createIndex('sync_status', 'sync_status', { unique: false });
                        store.createIndex('created_at', 'created_at', { unique: false });
                        store.createIndex('updated_at', 'updated_at', { unique: false });
                    }
                });

                // Pending operations queue for sync
                if (!db.objectStoreNames.contains('syncQueue')) {
                    const syncStore = db.createObjectStore('syncQueue', { keyPath: 'id', autoIncrement: true });
                    syncStore.createIndex('model_type', 'model_type', { unique: false });
                    syncStore.createIndex('sync_status', 'sync_status', { unique: false });
                    syncStore.createIndex('timestamp', 'timestamp', { unique: false });
                }

                // Cached images
                if (!db.objectStoreNames.contains('cachedImages')) {
                    db.createObjectStore('cachedImages', { keyPath: 'url' });
                }

                console.log('✅ Offline database schema created');
            };
        });

        return this.initPromise;
    }

    // Generic method to save record offline
    async saveRecord(modelType, data, operation = 'create') {
        await this.init();

        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([modelType, 'syncQueue'], 'readwrite');
            const store = transaction.objectStore(modelType);
            const syncStore = transaction.objectStore('syncQueue');

            const timestamp = new Date().toISOString();
            const localId = `local_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;

            // Prepare record for storage
            const record = {
                local_id: localId,
                server_id: data.id || data.server_id || null,
                data: data,
                sync_status: 'pending', // pending, synced, error
                operation: operation, // create, update, delete
                created_at: timestamp,
                updated_at: timestamp
            };

            // Save to model store
            const storeRequest = store.add(record);

            storeRequest.onsuccess = () => {
                const recordId = storeRequest.result;
                
                // Add to sync queue
                const syncItem = {
                    model_type: modelType,
                    model_local_id: localId,
                    operation: operation,
                    data: data,
                    url: this.getSyncUrl(modelType, operation),
                    timestamp: Date.now(),
                    sync_status: 'pending',
                    retry_count: 0
                };

                const syncRequest = syncStore.add(syncItem);
                
                syncRequest.onsuccess = () => {
                    console.log(`✅ ${operation} operation saved offline for ${modelType}:`, localId);
                    resolve({
                        local_id: localId,
                        record_id: recordId,
                        ...record
                    });
                };

                syncRequest.onerror = () => {
                    console.error('❌ Failed to add to sync queue:', syncRequest.error);
                    reject(syncRequest.error);
                };
            };

            storeRequest.onerror = () => {
                console.error('❌ Failed to save record:', storeRequest.error);
                reject(storeRequest.error);
            };
        });
    }

    // Get all records for a model (including offline ones)
    async getRecords(modelType, includeSynced = true) {
        await this.init();

        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([modelType], 'readonly');
            const store = transaction.objectStore(modelType);
            const request = store.getAll();

            request.onsuccess = () => {
                let records = request.result || [];
                
                // Filter by sync status if needed
                if (!includeSynced) {
                    records = records.filter(r => r.sync_status !== 'synced');
                }

                // Transform to application format
                records = records.map(r => ({
                    id: r.server_id || r.local_id,
                    local_id: r.local_id,
                    ...r.data,
                    _offline: r.sync_status === 'pending',
                    _sync_status: r.sync_status
                }));

                resolve(records);
            };

            request.onerror = () => {
                reject(request.error);
            };
        });
    }

    // Get single record by ID
    async getRecord(modelType, id, isLocalId = false) {
        await this.init();

        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([modelType], 'readonly');
            const store = transaction.objectStore(modelType);
            
            if (isLocalId) {
                const request = store.get(id);
                request.onsuccess = () => {
                    if (request.result) {
                        const r = request.result;
                        resolve({
                            id: r.server_id || r.local_id,
                            local_id: r.local_id,
                            ...r.data,
                            _offline: r.sync_status === 'pending',
                            _sync_status: r.sync_status
                        });
                    } else {
                        resolve(null);
                    }
                };
                request.onerror = () => reject(request.error);
            } else {
                // Search by server_id using index
                const index = store.index('server_id');
                const request = index.getAll(id);
                request.onsuccess = () => {
                    const results = request.result || [];
                    if (results.length > 0) {
                        const r = results[0];
                        resolve({
                            id: r.server_id || r.local_id,
                            local_id: r.local_id,
                            ...r.data,
                            _offline: r.sync_status === 'pending',
                            _sync_status: r.sync_status
                        });
                    } else {
                        resolve(null);
                    }
                };
                request.onerror = () => reject(request.error);
            }
        });
    }

    // Update record offline
    async updateRecord(modelType, id, data, isLocalId = false) {
        await this.init();

        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([modelType, 'syncQueue'], 'readwrite');
            const store = transaction.objectStore(modelType);
            const syncStore = transaction.objectStore('syncQueue');

            const getRequest = isLocalId ? store.get(id) : store.index('server_id').get(id);

            getRequest.onsuccess = () => {
                const existing = getRequest.result;
                if (!existing) {
                    reject(new Error('Record not found'));
                    return;
                }

                // Update record
                existing.data = { ...existing.data, ...data };
                existing.sync_status = 'pending';
                existing.operation = existing.server_id ? 'update' : 'create';
                existing.updated_at = new Date().toISOString();

                const updateRequest = store.put(existing);

                updateRequest.onsuccess = () => {
                    // Add to sync queue
                    const syncItem = {
                        model_type: modelType,
                        model_local_id: existing.local_id,
                        operation: existing.operation,
                        data: existing.data,
                        url: this.getSyncUrl(modelType, existing.operation, existing.server_id),
                        timestamp: Date.now(),
                        sync_status: 'pending',
                        retry_count: 0
                    };

                    const syncRequest = syncStore.add(syncItem);
                    syncRequest.onsuccess = () => {
                        console.log(`✅ Update saved offline for ${modelType}:`, existing.local_id);
                        resolve(existing);
                    };
                    syncRequest.onerror = () => reject(syncRequest.error);
                };

                updateRequest.onerror = () => reject(updateRequest.error);
            };

            getRequest.onerror = () => reject(getRequest.error);
        });
    }

    // Delete record offline
    async deleteRecord(modelType, id, isLocalId = false) {
        await this.init();

        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([modelType, 'syncQueue'], 'readwrite');
            const store = transaction.objectStore(modelType);
            const syncStore = transaction.objectStore('syncQueue');

            const getRequest = isLocalId ? store.get(id) : store.index('server_id').get(id);

            getRequest.onsuccess = () => {
                const existing = getRequest.result;
                if (!existing) {
                    // If record doesn't exist locally, might be server-only
                    // Create a delete operation anyway
                    const localId = `local_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;
                    const syncItem = {
                        model_type: modelType,
                        model_local_id: localId,
                        operation: 'delete',
                        data: { id: id },
                        url: this.getSyncUrl(modelType, 'delete', id),
                        timestamp: Date.now(),
                        sync_status: 'pending',
                        retry_count: 0
                    };

                    const syncRequest = syncStore.add(syncItem);
                    syncRequest.onsuccess = () => {
                        console.log(`✅ Delete queued offline for ${modelType}:`, id);
                        resolve(true);
                    };
                    syncRequest.onerror = () => reject(syncRequest.error);
                    return;
                }

                // If not synced yet, just delete locally
                if (!existing.server_id) {
                    const deleteRequest = store.delete(existing.local_id);
                    deleteRequest.onsuccess = () => resolve(true);
                    deleteRequest.onerror = () => reject(deleteRequest.error);
                    return;
                }

                // Mark as deleted and add to sync queue
                existing.sync_status = 'pending';
                existing.operation = 'delete';
                existing.updated_at = new Date().toISOString();

                const updateRequest = store.put(existing);

                updateRequest.onsuccess = () => {
                    const syncItem = {
                        model_type: modelType,
                        model_local_id: existing.local_id,
                        operation: 'delete',
                        data: existing.data,
                        url: this.getSyncUrl(modelType, 'delete', existing.server_id),
                        timestamp: Date.now(),
                        sync_status: 'pending',
                        retry_count: 0
                    };

                    const syncRequest = syncStore.add(syncItem);
                    syncRequest.onsuccess = () => {
                        // Remove from local store
                        store.delete(existing.local_id);
                        console.log(`✅ Delete saved offline for ${modelType}:`, existing.local_id);
                        resolve(true);
                    };
                    syncRequest.onerror = () => reject(syncRequest.error);
                };

                updateRequest.onerror = () => reject(updateRequest.error);
            };

            getRequest.onerror = () => reject(getRequest.error);
        });
    }

    // Get sync URL for a model and operation
    getSyncUrl(modelType, operation, serverId = null) {
        const baseUrl = window.location.origin;
        const resourceMap = {
            'patients': '/admin/patients',
            'appointments': '/admin/appointments',
            'payments': '/admin/payments',
            'services': '/admin/services',
            'expenses': '/admin/expenses',
            'invoices': '/admin/invoices',
            'treatments': '/admin/treatments',
            'xrays': '/admin/xrays',
            'transcriptions': '/admin/transcriptions'
        };

        const base = resourceMap[modelType] || `/${modelType}`;
        
        if (operation === 'create') {
            return `${base}`;
        } else if (operation === 'update' || operation === 'delete') {
            return serverId ? `${base}/${serverId}` : base;
        }

        return base;
    }

    // Get all pending sync items
    async getPendingSyncItems() {
        await this.init();

        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction(['syncQueue'], 'readonly');
            const store = transaction.objectStore('syncQueue');
            const index = store.index('sync_status');
            const request = index.getAll('pending');

            request.onsuccess = () => {
                resolve(request.result || []);
            };

            request.onerror = () => {
                reject(request.error);
            };
        });
    }

    // Mark sync item as synced
    async markSynced(modelType, localId, serverId) {
        await this.init();

        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([modelType, 'syncQueue'], 'readwrite');
            const modelStore = transaction.objectStore(modelType);
            const syncStore = transaction.objectStore('syncQueue');

            // Update model record
            const getRequest = modelStore.get(localId);
            getRequest.onsuccess = () => {
                const record = getRequest.result;
                if (record) {
                    record.server_id = serverId;
                    record.sync_status = 'synced';
                    record.updated_at = new Date().toISOString();
                    modelStore.put(record);
                }

                // Mark sync queue items as synced
                const syncIndex = syncStore.index('model_local_id');
                const syncRequest = syncIndex.getAll(localId);

                syncRequest.onsuccess = () => {
                    const syncItems = syncRequest.result || [];
                    syncItems.forEach(item => {
                        item.sync_status = 'synced';
                        item.synced_at = Date.now();
                        syncStore.put(item);
                    });

                    resolve(true);
                };

                syncRequest.onerror = () => reject(syncRequest.error);
            };

            getRequest.onerror = () => reject(getRequest.error);
        });
    }

    // Sync all pending data
    async syncPendingData() {
        if (!navigator.onLine) {
            console.log('📴 Offline - Cannot sync');
            return { success: false, message: 'Offline' };
        }

        await this.init();

        const pendingItems = await this.getPendingSyncItems();
        if (pendingItems.length === 0) {
            console.log('✅ No pending data to sync');
            return { success: true, synced: 0 };
        }

        console.log(`🔄 Syncing ${pendingItems.length} pending items...`);

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
        let syncedCount = 0;
        let errorCount = 0;

        for (const item of pendingItems) {
            try {
                const url = item.url;
                const method = item.operation === 'delete' ? 'DELETE' : (item.server_id ? 'PUT' : 'POST');
                
                // Prepare data for Livewire
                const formData = new FormData();
                Object.keys(item.data).forEach(key => {
                    if (key !== '_token' && key !== '_method') {
                        const value = item.data[key];
                        if (value !== null && value !== undefined) {
                            if (typeof value === 'object' && !(value instanceof File)) {
                                formData.append(key, JSON.stringify(value));
                            } else {
                                formData.append(key, value);
                            }
                        }
                    }
                });

                // Use Livewire request format
                const response = await fetch(url, {
                    method: method === 'DELETE' ? 'POST' : 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: method === 'DELETE' ? JSON.stringify({ _method: 'DELETE' }) : formData
                });

                if (response.ok) {
                    const result = await response.json().catch(() => ({ id: item.server_id || null }));
                    const serverId = result.id || result.data?.id || item.server_id;
                    
                    await this.markSynced(item.model_type, item.model_local_id, serverId);
                    syncedCount++;
                    console.log(`✅ Synced ${item.model_type} ${item.operation}:`, item.model_local_id);
                } else {
                    errorCount++;
                    console.warn(`⚠️ Failed to sync ${item.model_type}:`, response.status);
                }
            } catch (error) {
                errorCount++;
                console.error(`❌ Error syncing ${item.model_type}:`, error);
            }
        }

        console.log(`✅ Sync complete: ${syncedCount} synced, ${errorCount} errors`);

        return {
            success: errorCount === 0,
            synced: syncedCount,
            errors: errorCount
        };
    }

    // Get sync status
    async getSyncStatus() {
        await this.init();
        const pendingItems = await this.getPendingSyncItems();
        
        return {
            pending_count: pendingItems.length,
            is_online: navigator.onLine,
            last_sync: localStorage.getItem('last_sync') || null
        };
    }
}

// Create global instance
const offlineDB = new OfflineDatabase();

// Make it globally available immediately
if (typeof window !== 'undefined') {
    window.offlineDB = offlineDB;
}

// Initialize on load
if (typeof window !== 'undefined') {
    // Initialize immediately
    offlineDB.init().then(() => {
        console.log('✅ Offline database initialized');
        window.offlineDB = offlineDB; // Ensure it's set
    }).catch(err => {
        console.error('❌ Failed to initialize offline database:', err);
    });

    // Auto-sync when coming online
    window.addEventListener('online', () => {
        console.log('🌐 Back online - Syncing pending data...');
        if (window.offlineDB) {
            window.offlineDB.syncPendingData().then(result => {
                if (result && result.success) {
                    localStorage.setItem('last_sync', new Date().toISOString());
                    // Show notification
                    if (window.Livewire && window.Livewire.dispatch) {
                        window.Livewire.dispatch('notify', {
                            type: 'success',
                            title: 'Sync Complete',
                            body: `Synced ${result.synced || 0} item(s) successfully.`
                        });
                    }
                }
            }).catch(err => {
                console.error('Error syncing:', err);
            });
        }
    });

    // Try to sync on page load if online
    if (navigator.onLine) {
        setTimeout(() => {
            if (window.offlineDB) {
                window.offlineDB.syncPendingData().then(result => {
                    if (result && result.success && result.synced > 0) {
                        localStorage.setItem('last_sync', new Date().toISOString());
                    }
                }).catch(err => {
                    console.error('Error syncing on load:', err);
                });
            }
        }, 3000);
    }

    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            // Ensure offlineDB is initialized
            if (window.offlineDB && !window.offlineDB.db) {
                window.offlineDB.init();
            }
        });
    } else {
        // DOM already loaded
        if (window.offlineDB && !window.offlineDB.db) {
            window.offlineDB.init();
        }
    }
}

export default offlineDB;

