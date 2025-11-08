// Filament Offline Support
// Handles Filament admin pages, Livewire requests, and form submissions offline

// Cache Filament pages when visited
function cacheFilamentPage() {
  if (window.location.pathname.startsWith('/admin')) {
    // Cache the current page
    if ('serviceWorker' in navigator && navigator.serviceWorker.controller) {
      // Clone the current page HTML
      const html = document.documentElement.outerHTML;
      const response = new Response(html, {
        headers: { 'Content-Type': 'text/html' }
      });
      
      navigator.serviceWorker.controller.postMessage({
        type: 'CACHE_FILAMENT_PAGE',
        url: window.location.href,
        response: response
      });
    }
  }
}

// Intercept Livewire requests for offline support
if (window.Livewire) {
  const originalRequest = window.Livewire.find || (() => {});
  
  // Override Livewire requests to work offline
  window.addEventListener('livewire:request', (event) => {
    if (!navigator.onLine) {
      console.log('📴 Offline - Intercepting Livewire request');
      // Handle offline Livewire requests
      event.detail.components.forEach(component => {
        // Store component state for offline use
        localStorage.setItem(`livewire:${component.id}`, JSON.stringify(component));
      });
    }
  });
}

// Cache images when they load (especially profile images)
function cacheImages() {
  // Cache all images on the page
  document.querySelectorAll('img').forEach(img => {
    if (img.src && img.src.startsWith(window.location.origin)) {
      // Only cache same-origin images
      if (!img.dataset.cached) {
        img.dataset.cached = 'true';
        
        fetch(img.src)
          .then(response => response.blob())
          .then(blob => {
            // Cache in service worker cache
            if ('serviceWorker' in navigator && navigator.serviceWorker.controller) {
              caches.open('images-cache-v3').then(cache => {
                cache.put(img.src, new Response(blob));
              });
            }
          })
          .catch(err => console.warn('Failed to cache image:', err));
      }
    }
  });
}

// Intercept Filament form submissions
function interceptFilamentForms() {
  // Intercept all form submissions in Filament
  document.addEventListener('submit', async (event) => {
    const form = event.target;
    
    // Check if it's a Filament form (usually in /admin routes)
    if (window.location.pathname.startsWith('/admin')) {
      if (!navigator.onLine) {
        event.preventDefault();
        
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        
        // Get all file inputs
        const files = {};
        form.querySelectorAll('input[type="file"]').forEach(input => {
          if (input.files && input.files.length > 0) {
            files[input.name] = Array.from(input.files);
          }
        });
        
        // Store form data for later sync
        if (window.offlineStorage) {
          const submission = {
            url: form.action || window.location.href,
            method: form.method || 'POST',
            data: { ...data, files },
            csrfToken: document.querySelector('meta[name="csrf-token"]')?.content || '',
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json'
            }
          };
          
          await window.offlineStorage.storePendingSubmission(submission);
          
          // Show Filament-style notification
          if (window.Livewire) {
            window.Livewire.dispatch('notify', {
              type: 'warning',
              title: 'Offline Mode',
              body: 'Your submission has been saved and will be synced when you are back online.'
            });
          } else {
            alert('You are offline. Your submission has been saved and will be synced when you are back online.');
          }
        }
        
        return false;
      }
    }
  });
}

// Preload and cache Filament assets
function preloadFilamentAssets() {
  // Cache Filament CSS and JS
  const assets = [
    ...Array.from(document.querySelectorAll('link[rel="stylesheet"]')).map(link => link.href),
    ...Array.from(document.querySelectorAll('script[src]')).map(script => script.src)
  ].filter(url => url && url.includes('/build/assets/theme-') || url.includes('/js/filament/'));
  
  assets.forEach(url => {
    if (url.startsWith(window.location.origin)) {
      fetch(url)
        .then(response => {
          if (response.ok) {
            return caches.open('filament-assets-v3').then(cache => {
              cache.put(url, response);
            });
          }
        })
        .catch(err => console.warn('Failed to cache asset:', err));
    }
  });
}

// Initialize Filament offline support
function initFilamentOffline() {
  if (window.location.pathname.startsWith('/admin')) {
    console.log('🔧 Initializing Filament offline support');
    
    // Cache current page
    cacheFilamentPage();
    
    // Cache images
    cacheImages();
    
    // Observe for new images
    const observer = new MutationObserver(() => {
      cacheImages();
    });
    
    observer.observe(document.body, {
      childList: true,
      subtree: true
    });
    
    // Intercept forms
    interceptFilamentForms();
    
    // Preload assets
    preloadFilamentAssets();
    
    // Cache page on navigation (Livewire navigation)
    window.addEventListener('popstate', () => {
      setTimeout(cacheFilamentPage, 500);
    });
    
    // Listen for Livewire navigation
    if (window.Livewire) {
      window.Livewire.hook('morph.updated', () => {
        setTimeout(() => {
          cacheFilamentPage();
          cacheImages();
        }, 500);
      });
    }
  }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initFilamentOffline);
} else {
  initFilamentOffline();
}

// Also initialize after a short delay for dynamic content
setTimeout(initFilamentOffline, 1000);

// Export for use in modules
export { initFilamentOffline, cacheFilamentPage, cacheImages };

