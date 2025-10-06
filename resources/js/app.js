import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from './components/App.vue';
import { registerSW } from 'virtual:pwa-register';

// Create Vue app
const app = createApp(App);
const pinia = createPinia();

app.use(pinia);
app.mount('#app');

// Register service worker
if ('serviceWorker' in navigator) {
    registerSW({
        onNeedRefresh() {
            console.log('New content available, refresh needed');
        },
        onOfflineReady() {
            console.log('App ready to work offline');
        },
    });
}

// Debug PWA installation
console.log('PWA Debug Info:');
console.log('- Service Worker Support:', 'serviceWorker' in navigator);
console.log('- HTTPS:', location.protocol === 'https:');
console.log('- Manifest:', document.querySelector('link[rel="manifest"]')?.href);
console.log('- Current URL:', location.href);
