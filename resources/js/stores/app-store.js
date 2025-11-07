import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { taskStore, syncQueue, syncStatus } from './offline-db.js';
import axios from 'axios';

export const useAppStore = defineStore('app', () => {
    // State
    const tasks = ref([]);
    const isOnline = ref(navigator.onLine);
    const isSyncing = ref(false);
    const syncStatusData = ref({
        is_online: navigator.onLine,
        last_sync: null,
        pending_count: 0
    });
    const lastSyncError = ref(null);

    // Computed
    const pendingTasksCount = computed(() => 
        tasks.value.filter(task => !task.completed).length
    );
    
    const completedTasksCount = computed(() => 
        tasks.value.filter(task => task.completed).length
    );

    const syncStatusText = computed(() => {
        if (isSyncing.value) return 'Syncing...';
        if (!isOnline.value) return 'Offline';
        if (syncStatusData.value.pending_count > 0) return `${syncStatusData.value.pending_count} pending sync`;
        return 'All synced ✅';
    });

    // Actions
    const loadTasks = async () => {
        try {
            tasks.value = await taskStore.getAll();
            await updateSyncStatus();
        } catch (error) {
            console.error('Error loading tasks:', error);
        }
    };

    const createTask = async (taskData) => {
        try {
            const newTask = await taskStore.create(taskData);
            tasks.value.unshift(newTask);
            await updateSyncStatus();
            
            // Try to sync if online
            if (isOnline.value) {
                await syncPendingData();
            }
            
            return newTask;
        } catch (error) {
            console.error('Error creating task:', error);
            throw error;
        }
    };

    const updateTask = async (id, taskData) => {
        try {
            const updatedTask = await taskStore.update(id, taskData);
            const index = tasks.value.findIndex(task => task.id === id);
            if (index !== -1) {
                tasks.value[index] = updatedTask;
            }
            await updateSyncStatus();
            
            // Try to sync if online
            if (isOnline.value) {
                await syncPendingData();
            }
            
            return updatedTask;
        } catch (error) {
            console.error('Error updating task:', error);
            throw error;
        }
    };

    const deleteTask = async (id) => {
        try {
            await taskStore.delete(id);
            tasks.value = tasks.value.filter(task => task.id !== id);
            await updateSyncStatus();
            
            // Try to sync if online
            if (isOnline.value) {
                await syncPendingData();
            }
            
            return true;
        } catch (error) {
            console.error('Error deleting task:', error);
            throw error;
        }
    };

    const syncPendingData = async (onProgress = null) => {
        if (isSyncing.value || !isOnline.value) {
            if (!isOnline.value) {
                console.warn('Cannot sync: device is offline');
            }
            return;
        }

        isSyncing.value = true;
        lastSyncError.value = null;

        try {
            // Get all unsynced data stats first
            const stats = await syncQueue.getSyncStats();
            const totalPending = stats.pending;
            
            if (totalPending === 0) {
                await updateSyncStatus();
                return { success: true, count: 0, message: 'No data to sync' };
            }

            console.log(`📤 Starting sync of ${totalPending} items...`);
            
            // Process in batches to handle large amounts of data (30 days worth)
            const BATCH_SIZE = 50; // Process 50 items at a time
            let syncedCount = 0;
            let failedCount = 0;
            let processedCount = 0;

            while (processedCount < totalPending) {
                // Get next batch
                const batch = await syncQueue.getUnsyncedBatch(BATCH_SIZE);
                
                if (batch.length === 0) break;

                try {
                    // Update progress
                    if (onProgress) {
                        onProgress({
                            processed: processedCount,
                            total: totalPending,
                            percentage: Math.round((processedCount / totalPending) * 100)
                        });
                    }

                    const response = await axios.post('/api/v1/sync', {
                        data: batch
                    }, {
                        timeout: 60000 // 60 second timeout for large batches
                    });

                    if (response.data.success) {
                        // Mark synced items
                        for (const item of batch) {
                            await syncQueue.markSynced(item.client_id);
                        }

                        syncedCount += response.data.synced_count || batch.length;
                        processedCount += batch.length;

                        console.log(`✅ Synced batch: ${syncedCount}/${totalPending} items`);

                        // Small delay between batches to avoid overwhelming the server
                        if (processedCount < totalPending) {
                            await new Promise(resolve => setTimeout(resolve, 500));
                        }
                    } else {
                        throw new Error(response.data.message || 'Batch sync failed');
                    }
                } catch (error) {
                    console.error(`❌ Batch sync error:`, error);
                    
                    // For network errors, don't mark as failed - will retry
                    if (error.code === 'ECONNABORTED' || error.message.includes('timeout') || !navigator.onLine) {
                        console.log('Network error - stopping sync, will retry later');
                        throw error; // Stop sync, will retry when connection is stable
                    }

                    // For other errors, mark items as failed but continue with next batch
                    for (const item of batch) {
                        await syncQueue.markFailed(item.client_id, error.message);
                        await syncQueue.incrementRetryCount(item.client_id);
                    }
                    failedCount += batch.length;
                    processedCount += batch.length;
                }
            }

            // Update sync status
            if (syncedCount > 0) {
                await syncStatus.updateLastSync();
            }
            await updateSyncStatus();

            const result = {
                success: true,
                synced: syncedCount,
                failed: failedCount,
                total: totalPending,
                message: `Synced ${syncedCount} items${failedCount > 0 ? `, ${failedCount} failed` : ''}`
            };

            console.log(`✅ Sync complete: ${result.message}`);
            return result;

        } catch (error) {
            console.error('❌ Sync error:', error);
            lastSyncError.value = error.message || 'Failed to sync. Please try again.';
            throw error;
        } finally {
            isSyncing.value = false;
        }
    };

    const syncFromServer = async () => {
        if (!isOnline.value) return;

        try {
            const response = await axios.get('/api/v1/tasks');
            if (response.data.success) {
                await taskStore.syncFromServer(response.data.data);
                await loadTasks();
                await syncStatus.updateLastSync();
                await updateSyncStatus();
            }
        } catch (error) {
            console.error('Error syncing from server:', error);
        }
    };

    const updateSyncStatus = async () => {
        const pendingCount = await syncQueue.getPendingCount();
        const status = await syncStatus.updateStatus(isOnline.value, pendingCount);
        
        // Calculate days since last sync
        if (status.last_sync) {
            await syncStatus.calculateDaysSinceSync();
            const updatedStatus = await syncStatus.getStatus();
            syncStatusData.value = updatedStatus;
        } else {
            syncStatusData.value = status;
        }
    };

    const setOnlineStatus = async (online) => {
        const wasOffline = !isOnline.value && online;
        isOnline.value = online;
        syncStatusData.value.is_online = online;
        
        if (online) {
            // Update status immediately
            await updateSyncStatus();
            
            // Try to sync when coming back online (with delay to ensure connection is stable)
            if (wasOffline) {
                console.log('🌐 Connection restored - checking for pending sync...');
                setTimeout(async () => {
                    const pendingCount = await syncQueue.getPendingCount();
                    if (pendingCount > 0) {
                        console.log(`📤 Found ${pendingCount} pending changes - starting auto-sync...`);
                        try {
                            // Auto-sync without progress callback (silent background sync)
                            await syncPendingData();
                            console.log('✅ Auto-sync completed successfully');
                        } catch (error) {
                            console.error('❌ Auto-sync failed:', error);
                            // Don't show error to user for auto-sync, they can manually sync if needed
                        }
                    }
                }, 2000); // Wait 2 seconds for connection to stabilize
            }
        } else {
            console.log('📴 Connection lost - app will continue working offline');
        }
    };

    const clearSyncError = () => {
        lastSyncError.value = null;
    };

    // Initialize
    const initialize = async () => {
        await loadTasks();
        await updateSyncStatus();
        
        // Set up online/offline listeners
        window.addEventListener('online', () => setOnlineStatus(true));
        window.addEventListener('offline', () => setOnlineStatus(false));
    };

    return {
        // State
        tasks,
        isOnline,
        isSyncing,
        syncStatusData,
        lastSyncError,
        
        // Computed
        pendingTasksCount,
        completedTasksCount,
        syncStatusText,
        
        // Actions
        loadTasks,
        createTask,
        updateTask,
        deleteTask,
        syncPendingData,
        syncFromServer,
        updateSyncStatus,
        setOnlineStatus,
        clearSyncError,
        initialize
    };
});

