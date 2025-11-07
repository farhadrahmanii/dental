import Dexie from 'dexie';

class OfflineDatabase extends Dexie {
    constructor() {
        super('DentalPWA');
        
        this.version(1).stores({
            tasks: '++id, title, description, completed, priority, due_date, client_id, created_at, updated_at',
            syncQueue: '++id, model_type, model_id, action, data, client_id, created_at, synced_at, is_synced, retry_count',
            syncStatus: '++id, last_sync, is_online, pending_count, days_since_sync, total_storage_size'
        });
    }
}

// Create database instance
export const db = new OfflineDatabase();

// Sync queue operations
export const syncQueue = {
    async add(modelType, modelId, action, data, clientId) {
        return await db.syncQueue.add({
            model_type: modelType,
            model_id: modelId,
            action: action,
            data: data,
            client_id: clientId,
            created_at: new Date().toISOString(),
            synced_at: null,
            is_synced: false,
            retry_count: 0
        });
    },

    async getUnsynced() {
        return await db.syncQueue.where('is_synced').equals(false).toArray();
    },

    async markSynced(clientId, syncedAt = new Date().toISOString()) {
        return await db.syncQueue
            .where('client_id')
            .equals(clientId)
            .modify({ is_synced: true, synced_at: syncedAt });
    },

    async markFailed(clientId, errorMessage) {
        return await db.syncQueue
            .where('client_id')
            .equals(clientId)
            .modify({ 
                is_synced: false, 
                error_message: errorMessage 
            });
    },

    async clearSynced() {
        return await db.syncQueue.where('is_synced').equals(true).delete();
    },

    async getPendingCount() {
        return await db.syncQueue.where('is_synced').equals(false).count();
    },

    async getUnsyncedBatch(limit = 100) {
        return await db.syncQueue
            .where('is_synced')
            .equals(false)
            .limit(limit)
            .toArray();
    },

    async incrementRetryCount(clientId) {
        const item = await db.syncQueue.where('client_id').equals(clientId).first();
        if (item) {
            await db.syncQueue.update(item.id, {
                retry_count: (item.retry_count || 0) + 1
            });
        }
    },

    async getSyncStats() {
        const total = await db.syncQueue.count();
        const synced = await db.syncQueue.where('is_synced').equals(true).count();
        const pending = await db.syncQueue.where('is_synced').equals(false).count();
        const oldestPending = await db.syncQueue
            .where('is_synced')
            .equals(false)
            .orderBy('created_at')
            .first();
        
        return {
            total,
            synced,
            pending,
            oldest_pending_date: oldestPending?.created_at || null
        };
    }
};

// Task operations
export const taskStore = {
    async getAll() {
        return await db.tasks.orderBy('created_at').reverse().toArray();
    },

    async getById(id) {
        return await db.tasks.get(id);
    },

    async create(taskData) {
        const clientId = `task_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;
        const task = {
            ...taskData,
            client_id: clientId,
            created_at: new Date().toISOString(),
            updated_at: new Date().toISOString()
        };

        const id = await db.tasks.add(task);
        
        // Add to sync queue
        await syncQueue.add('Task', id.toString(), 'create', taskData, clientId);
        
        return { id, ...task };
    },

    async update(id, taskData) {
        const task = await db.tasks.get(id);
        if (!task) throw new Error('Task not found');

        const updatedTask = {
            ...task,
            ...taskData,
            updated_at: new Date().toISOString()
        };

        await db.tasks.update(id, updatedTask);
        
        // Add to sync queue
        await syncQueue.add('Task', id.toString(), 'update', taskData, task.client_id);
        
        return updatedTask;
    },

    async delete(id) {
        const task = await db.tasks.get(id);
        if (!task) throw new Error('Task not found');

        await db.tasks.delete(id);
        
        // Add to sync queue
        await syncQueue.add('Task', id.toString(), 'delete', {}, task.client_id);
        
        return true;
    },

    async syncFromServer(serverTasks) {
        // Clear existing tasks
        await db.tasks.clear();
        
        // Add server tasks
        for (const task of serverTasks) {
            await db.tasks.add({
                ...task,
                client_id: task.client_id || `server_${task.id}`,
                created_at: task.created_at || new Date().toISOString(),
                updated_at: task.updated_at || new Date().toISOString()
            });
        }
    }
};

// Sync status operations
export const syncStatus = {
    async updateStatus(isOnline, pendingCount = null) {
        const status = await db.syncStatus.get(1);
        const newStatus = {
            id: 1,
            is_online: isOnline,
            last_sync: status?.last_sync || null,
            pending_count: pendingCount !== null ? pendingCount : (await syncQueue.getPendingCount())
        };

        await db.syncStatus.put(newStatus);
        return newStatus;
    },

    async getStatus() {
        return await db.syncStatus.get(1) || {
            id: 1,
            is_online: navigator.onLine,
            last_sync: null,
            pending_count: 0
        };
    },

    async updateLastSync() {
        const status = await this.getStatus();
        status.last_sync = new Date().toISOString();
        status.days_since_sync = 0;
        await db.syncStatus.put(status);
        return status;
    },

    async calculateDaysSinceSync() {
        const status = await this.getStatus();
        if (status.last_sync) {
            const lastSyncDate = new Date(status.last_sync);
            const now = new Date();
            const diffTime = Math.abs(now - lastSyncDate);
            const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
            status.days_since_sync = diffDays;
            await db.syncStatus.put(status);
            return diffDays;
        }
        return null;
    }
};

export default db;

