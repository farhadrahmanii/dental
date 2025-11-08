// Offline Handler for Full Application Offline Support
class OfflineHandler {
  constructor() {
    this.isOnline = navigator.onLine;
    this.init();
  }

  init() {
    // Listen for online/offline events
    window.addEventListener('online', () => this.handleOnline());
    window.addEventListener('offline', () => this.handleOffline());

    // Check service worker status
    this.checkServiceWorker();

    // Show initial status
    if (!this.isOnline) {
      this.showOfflineIndicator();
    }
  }

  async checkServiceWorker() {
    if ('serviceWorker' in navigator) {
      try {
        const registration = await navigator.serviceWorker.getRegistration();
        if (registration) {
          console.log('✅ Service Worker is active');
          this.serviceWorkerReady = true;
        } else {
          console.warn('⚠️ Service Worker not registered');
          this.serviceWorkerReady = false;
        }
      } catch (error) {
        console.error('❌ Error checking Service Worker:', error);
      }
    }
  }

  handleOnline() {
    this.isOnline = true;
    console.log('🌐 Connection restored');
    this.hideOfflineIndicator();
    
    // Sync offline data if needed
    this.syncOfflineData();
  }

  handleOffline() {
    this.isOnline = false;
    console.log('📴 Connection lost - App will continue to work offline');
    this.showOfflineIndicator();
  }

  showOfflineIndicator() {
    // Remove existing indicator if any
    const existing = document.getElementById('offline-indicator');
    if (existing) {
      existing.remove();
    }

    // Create offline indicator
    const indicator = document.createElement('div');
    indicator.id = 'offline-indicator';
    indicator.innerHTML = `
      <div style="
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        background: #ff9500;
        color: white;
        padding: 12px;
        text-align: center;
        z-index: 10000;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        font-size: 14px;
        font-weight: 500;
      ">
        📴 You are offline. The app will continue to work with cached data.
      </div>
    `;
    document.body.appendChild(indicator);

    // Adjust body padding to prevent content from being hidden
    document.body.style.paddingTop = '48px';
  }

  hideOfflineIndicator() {
    const indicator = document.getElementById('offline-indicator');
    if (indicator) {
      indicator.remove();
      document.body.style.paddingTop = '';
    }
  }

  async syncOfflineData() {
    // Notify service worker to sync offline data
    if ('serviceWorker' in navigator && navigator.serviceWorker.controller) {
      navigator.serviceWorker.controller.postMessage({
        type: 'SYNC_OFFLINE_DATA'
      });
    }
  }
}

// Initialize offline handler when DOM is ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    window.offlineHandler = new OfflineHandler();
  });
} else {
  window.offlineHandler = new OfflineHandler();
}

// Export for use in modules
export default OfflineHandler;

