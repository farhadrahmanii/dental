// Offline Data Storage using IndexedDB
// Handles offline form submissions, record creation, and image caching

class OfflineStorage {
  constructor() {
    this.dbName = 'dental-offline-db';
    this.dbVersion = 1;
    this.db = null;
    this.init();
  }

  async init() {
    return new Promise((resolve, reject) => {
      const request = indexedDB.open(this.dbName, this.dbVersion);

      request.onerror = () => {
        console.error('❌ IndexedDB error:', request.error);
        reject(request.error);
      };

      request.onsuccess = () => {
        this.db = request.result;
        console.log('✅ IndexedDB opened successfully');
        resolve(this.db);
      };

      request.onupgradeneeded = (event) => {
        const db = event.target.result;

        // Object store for pending form submissions
        if (!db.objectStoreNames.contains('pendingSubmissions')) {
          const submissionsStore = db.createObjectStore('pendingSubmissions', {
            keyPath: 'id',
            autoIncrement: true
          });
          submissionsStore.createIndex('timestamp', 'timestamp', { unique: false });
          submissionsStore.createIndex('type', 'type', { unique: false });
          submissionsStore.createIndex('synced', 'synced', { unique: false });
        }

        // Object store for cached images
        if (!db.objectStoreNames.contains('cachedImages')) {
          const imagesStore = db.createObjectStore('cachedImages', {
            keyPath: 'url'
          });
          imagesStore.createIndex('timestamp', 'timestamp', { unique: false });
        }

        // Object store for offline records
        if (!db.objectStoreNames.contains('offlineRecords')) {
          const recordsStore = db.createObjectStore('offlineRecords', {
            keyPath: 'id',
            autoIncrement: true
          });
          recordsStore.createIndex('type', 'type', { unique: false });
          recordsStore.createIndex('timestamp', 'timestamp', { unique: false });
          recordsStore.createIndex('synced', 'synced', { unique: false });
        }

        console.log('✅ IndexedDB structure created');
      };
    });
  }

  // Store pending form submission
  async storePendingSubmission(data) {
    if (!this.db) await this.init();

    return new Promise((resolve, reject) => {
      const transaction = this.db.transaction(['pendingSubmissions'], 'readwrite');
      const store = transaction.objectStore('pendingSubmissions');
      
      const submission = {
        ...data,
        timestamp: Date.now(),
        synced: false
      };

      const request = store.add(submission);

      request.onsuccess = () => {
        console.log('✅ Pending submission stored:', request.result);
        resolve(request.result);
      };

      request.onerror = () => {
        console.error('❌ Error storing submission:', request.error);
        reject(request.error);
      };
    });
  }

  // Get all pending submissions
  async getPendingSubmissions() {
    if (!this.db) await this.init();

    return new Promise((resolve, reject) => {
      const transaction = this.db.transaction(['pendingSubmissions'], 'readonly');
      const store = transaction.objectStore('pendingSubmissions');
      const index = store.index('synced');
      const request = index.getAll(false); // Get unsynced only

      request.onsuccess = () => {
        resolve(request.result || []);
      };

      request.onerror = () => {
        reject(request.error);
      };
    });
  }

  // Mark submission as synced
  async markSubmissionSynced(id) {
    if (!this.db) await this.init();

    return new Promise((resolve, reject) => {
      const transaction = this.db.transaction(['pendingSubmissions'], 'readwrite');
      const store = transaction.objectStore('pendingSubmissions');
      const getRequest = store.get(id);

      getRequest.onsuccess = () => {
        const submission = getRequest.result;
        if (submission) {
          submission.synced = true;
          submission.syncedAt = Date.now();
          
          const updateRequest = store.put(submission);
          updateRequest.onsuccess = () => resolve();
          updateRequest.onerror = () => reject(updateRequest.error);
        } else {
          resolve();
        }
      };

      getRequest.onerror = () => reject(getRequest.error);
    });
  }

  // Store offline record
  async storeOfflineRecord(type, data) {
    if (!this.db) await this.init();

    return new Promise((resolve, reject) => {
      const transaction = this.db.transaction(['offlineRecords'], 'readwrite');
      const store = transaction.objectStore('offlineRecords');
      
      const record = {
        type,
        data,
        timestamp: Date.now(),
        synced: false
      };

      const request = store.add(record);

      request.onsuccess = () => {
        console.log('✅ Offline record stored:', request.result);
        resolve(request.result);
      };

      request.onerror = () => {
        console.error('❌ Error storing record:', request.error);
        reject(request.error);
      };
    });
  }

  // Get offline records by type
  async getOfflineRecords(type) {
    if (!this.db) await this.init();

    return new Promise((resolve, reject) => {
      const transaction = this.db.transaction(['offlineRecords'], 'readonly');
      const store = transaction.objectStore('offlineRecords');
      const index = store.index('type');
      const request = index.getAll(type);

      request.onsuccess = () => {
        resolve(request.result || []);
      };

      request.onerror = () => {
        reject(request.error);
      };
    });
  }

  // Cache image as blob
  async cacheImage(url, blob) {
    if (!this.db) await this.init();

    return new Promise((resolve, reject) => {
      const transaction = this.db.transaction(['cachedImages'], 'readwrite');
      const store = transaction.objectStore('cachedImages');
      
      const imageData = {
        url,
        blob,
        timestamp: Date.now()
      };

      const request = store.put(imageData);

      request.onsuccess = () => {
        console.log('✅ Image cached:', url);
        resolve();
      };

      request.onerror = () => {
        console.error('❌ Error caching image:', request.error);
        reject(request.error);
      };
    });
  }

  // Get cached image
  async getCachedImage(url) {
    if (!this.db) await this.init();

    return new Promise((resolve, reject) => {
      const transaction = this.db.transaction(['cachedImages'], 'readonly');
      const store = transaction.objectStore('cachedImages');
      const request = store.get(url);

      request.onsuccess = () => {
        if (request.result) {
          resolve(request.result.blob);
        } else {
          resolve(null);
        }
      };

      request.onerror = () => {
        reject(request.error);
      };
    });
  }

  // Sync all pending data when online
  async syncPendingData() {
    if (!navigator.onLine) {
      console.log('📴 Offline - Cannot sync');
      return;
    }

    console.log('🔄 Syncing pending data...');
    const submissions = await this.getPendingSubmissions();

    for (const submission of submissions) {
      try {
        // Try to sync the submission
        const response = await fetch(submission.url, {
          method: submission.method || 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': submission.csrfToken || '',
            ...submission.headers
          },
          body: JSON.stringify(submission.data)
        });

        if (response.ok) {
          await this.markSubmissionSynced(submission.id);
          console.log('✅ Synced submission:', submission.id);
        } else {
          console.warn('⚠️ Failed to sync submission:', submission.id);
        }
      } catch (error) {
        console.error('❌ Error syncing submission:', error);
      }
    }
  }

  // Clear old cached data
  async clearOldCache(maxAge = 7 * 24 * 60 * 60 * 1000) { // 7 days
    if (!this.db) await this.init();

    const cutoff = Date.now() - maxAge;

    // Clear old images
    const transaction = this.db.transaction(['cachedImages'], 'readwrite');
    const imagesStore = transaction.objectStore('cachedImages');
    const index = imagesStore.index('timestamp');
    const range = IDBKeyRange.upperBound(cutoff);
    const request = index.openCursor(range);

    request.onsuccess = (event) => {
      const cursor = event.target.result;
      if (cursor) {
        cursor.delete();
        cursor.continue();
      }
    };
  }
}

// Initialize offline storage
const offlineStorage = new OfflineStorage();

// Intercept form submissions when offline
document.addEventListener('DOMContentLoaded', () => {
  // Intercept Filament form submissions
  document.addEventListener('submit', async (event) => {
    if (!navigator.onLine) {
      event.preventDefault();
      
      const form = event.target;
      const formData = new FormData(form);
      const data = Object.fromEntries(formData.entries());
      const url = form.action || window.location.href;
      const method = form.method || 'POST';
      
      // Get CSRF token
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
      
      // Store submission for later sync
      await offlineStorage.storePendingSubmission({
        url,
        method,
        data,
        csrfToken,
        headers: {}
      });

      // Show notification
      alert('You are offline. Your submission has been saved and will be synced when you are back online.');
      
      // Prevent default submission
      return false;
    }
  });

  // Sync when coming back online
  window.addEventListener('online', () => {
    console.log('🌐 Back online - Syncing pending data...');
    offlineStorage.syncPendingData();
  });

  // Cache images when they load
  document.addEventListener('load', (event) => {
    if (event.target.tagName === 'IMG') {
      const img = event.target;
      const url = img.src;
      
      // Only cache same-origin images
      if (url.startsWith(window.location.origin)) {
        fetch(url)
          .then(response => response.blob())
          .then(blob => offlineStorage.cacheImage(url, blob))
          .catch(err => console.warn('Failed to cache image:', err));
      }
    }
  }, true);
});

// Export for use in modules
export default offlineStorage;

