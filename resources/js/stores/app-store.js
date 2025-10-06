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

    const syncPendingData = async () => {
        if (isSyncing.value || !isOnline.value) return;

        isSyncing.value = true;
        lastSyncError.value = null;

        try {
            const unsyncedData = await syncQueue.getUnsynced();
            
            if (unsyncedData.length === 0) {
                await updateSyncStatus();
                return;
            }

            const response = await axios.post('/api/v1/sync', {
                data: unsyncedData
            });

            if (response.data.success) {
                // Mark synced items
                for (const item of unsyncedData) {
                    await syncQueue.markSynced(item.client_id);
                }

                // Update sync status
                await syncStatus.updateLastSync();
                await updateSyncStatus();

                console.log(`Synced ${response.data.synced_count} items`);
            } else {
                throw new Error(response.data.message || 'Sync failed');
            }
        } catch (error) {
            console.error('Sync error:', error);
            lastSyncError.value = error.message;
            
            // Mark failed items
            const unsyncedData = await syncQueue.getUnsynced();
            for (const item of unsyncedData) {
                await syncQueue.markFailed(item.client_id, error.message);
            }
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
        syncStatusData.value = await syncStatus.updateStatus(isOnline.value, pendingCount);
    };

    const setOnlineStatus = (online) => {
        isOnline.value = online;
        syncStatusData.value.is_online = online;
        
        if (online) {
            // Try to sync when coming back online
            setTimeout(() => {
                syncPendingData();
            }, 1000);
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
