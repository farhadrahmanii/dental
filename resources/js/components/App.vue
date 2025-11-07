<template>
  <div id="app" class="min-h-screen bg-gray-50">
    <!-- Offline Banner -->
    <div 
      v-if="!isOnline" 
      class="bg-yellow-500 text-white px-4 py-3 shadow-lg sticky top-0 z-50"
    >
      <div class="max-w-7xl mx-auto flex items-center justify-between">
        <div class="flex items-center space-x-3">
          <svg class="h-5 w-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 010 12.728m0 0l-2.829-2.829m2.829 2.829L21 21M15.536 8.464a5 5 0 010 7.072m0 0l-2.829-2.829m-4.243 2.829a4.978 4.978 0 01-1.414-2.83m-1.414 5.658a9 9 0 01-2.167-9.238m7.824 2.167a1 1 0 111.414 1.414m-1.414-1.414L3 3m8.293 8.293l1.414 1.414"></path>
          </svg>
          <div>
            <p class="font-semibold">You're currently offline</p>
            <p class="text-sm opacity-90">
              {{ syncStatusData.pending_count > 0 
                ? `${syncStatusData.pending_count} change(s) will sync when you're back online` 
                : 'All changes are saved locally and will sync automatically when connection is restored' }}
            </p>
          </div>
        </div>
        <div v-if="serviceWorkerReady" class="flex items-center space-x-2 text-sm">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
          <span>App ready offline</span>
        </div>
      </div>
    </div>

    <!-- Online Sync Banner -->
    <div 
      v-if="isOnline && syncStatusData.pending_count > 0 && !isSyncing" 
      class="bg-blue-500 text-white px-4 py-2 shadow-md"
    >
      <div class="max-w-7xl mx-auto flex items-center justify-between">
        <div class="flex items-center space-x-2">
          <svg class="h-4 w-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
          </svg>
          <span class="text-sm">{{ syncStatusData.pending_count }} change(s) pending sync</span>
        </div>
        <button
          @click="syncPendingData"
          class="text-sm underline hover:no-underline"
        >
          Sync now
        </button>
      </div>
    </div>

    <!-- Success Sync Banner -->
    <div 
      v-if="showSyncSuccess" 
      class="bg-green-500 text-white px-4 py-2 shadow-md animate-slide-down"
    >
      <div class="max-w-7xl mx-auto flex items-center justify-between">
        <div class="flex items-center space-x-2">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <span>All changes synced successfully!</span>
        </div>
        <button @click="showSyncSuccess = false" class="text-white hover:text-gray-200">
          ✕
        </button>
      </div>
    </div>

    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
          <div class="flex items-center">
            <h1 class="text-2xl font-bold text-gray-900">Dental Practice PWA</h1>
            <div class="ml-4 flex items-center space-x-2">
              <div 
                class="w-3 h-3 rounded-full transition-all"
                :class="isOnline ? 'bg-green-500 animate-pulse' : 'bg-red-500'"
                :title="isOnline ? 'Online' : 'Offline'"
              ></div>
              <span class="text-sm text-gray-600">{{ syncStatusText }}</span>
              <span v-if="serviceWorkerReady && !isOnline" class="text-xs text-gray-500" title="Service Worker Active">
                📦
              </span>
            </div>
          </div>
          <div class="flex items-center space-x-4">
            <button
              v-if="!isOnline && syncStatusData.pending_count > 0"
              @click="syncPendingData"
              :disabled="isSyncing"
              class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
            >
              {{ isSyncing ? 'Syncing...' : 'Sync Now' }}
            </button>
            <button
              @click="showAddTask = true"
              class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
            >
              Add Task
            </button>
          </div>
        </div>
      </div>
    </header>

    <!-- Error Banner -->
    <div v-if="lastSyncError" class="bg-red-50 border-l-4 border-red-400 p-4">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex">
          <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
          </div>
          <div class="ml-3">
            <p class="text-sm text-red-700">
              Sync error: {{ lastSyncError }}
              <button @click="clearSyncError" class="ml-2 text-red-600 hover:text-red-800 underline">
                Dismiss
              </button>
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Stats -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">Total Tasks</p>
              <p class="text-2xl font-semibold text-gray-900">{{ tasks.length }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">Pending</p>
              <p class="text-2xl font-semibold text-gray-900">{{ pendingTasksCount }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">Completed</p>
              <p class="text-2xl font-semibold text-gray-900">{{ completedTasksCount }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Tasks List -->
      <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
          <h2 class="text-lg font-medium text-gray-900">Tasks</h2>
        </div>
        <div class="divide-y divide-gray-200">
          <div v-if="tasks.length === 0" class="px-6 py-8 text-center text-gray-500">
            No tasks yet. Create your first task to get started!
          </div>
          <TaskItem
            v-for="task in tasks"
            :key="task.id"
            :task="task"
            @update="updateTask"
            @delete="deleteTask"
          />
        </div>
      </div>
    </main>

    <!-- Add Task Modal -->
    <AddTaskModal
      v-if="showAddTask"
      @close="showAddTask = false"
      @save="handleAddTask"
    />

    <!-- Install Prompt -->
    <div v-if="showInstallPrompt" class="fixed bottom-4 right-4 bg-blue-600 text-white p-4 rounded-lg shadow-lg max-w-sm">
      <div class="flex items-center justify-between">
        <div>
          <p class="font-medium">Install App</p>
          <p class="text-sm opacity-90">Add to home screen for better experience</p>
        </div>
        <button @click="installApp" class="ml-4 px-3 py-1 bg-blue-700 rounded hover:bg-blue-800">
          Install
        </button>
        <button @click="showInstallPrompt = false" class="ml-2 text-blue-200 hover:text-white">
          ✕
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useAppStore } from '../stores/app-store.js';
import TaskItem from './TaskItem.vue';
import AddTaskModal from './AddTaskModal.vue';

const store = useAppStore();
const {
  tasks,
  isOnline,
  isSyncing,
  syncStatusData,
  lastSyncError,
  pendingTasksCount,
  completedTasksCount,
  syncStatusText,
  createTask,
  updateTask,
  deleteTask,
  syncPendingData,
  clearSyncError,
  initialize
} = store;

const showAddTask = ref(false);
const showInstallPrompt = ref(false);
const serviceWorkerReady = ref(false);
const showSyncSuccess = ref(false);
let deferredPrompt = null;
let syncSuccessTimeout = null;

// Watch for sync completion
watch([() => syncStatusData.value.pending_count, isSyncing], ([pendingCount, syncing]) => {
  if (!syncing && pendingCount === 0 && isOnline.value && syncStatusData.value.last_sync) {
    showSyncSuccess.value = true;
    if (syncSuccessTimeout) clearTimeout(syncSuccessTimeout);
    syncSuccessTimeout = setTimeout(() => {
      showSyncSuccess.value = false;
    }, 5000);
  }
});

// Check service worker status
const checkServiceWorker = async () => {
  if ('serviceWorker' in navigator) {
    try {
      const registration = await navigator.serviceWorker.getRegistration();
      if (registration) {
        serviceWorkerReady.value = true;
        console.log('Service Worker is active and ready for offline use');
      }
    } catch (error) {
      console.error('Error checking service worker:', error);
    }
  }
};

const handleAddTask = async (taskData) => {
  try {
    await createTask(taskData);
    showAddTask.value = false;
  } catch (error) {
    console.error('Error adding task:', error);
  }
};

const installApp = async () => {
  if (deferredPrompt) {
    deferredPrompt.prompt();
    const { outcome } = await deferredPrompt.userChoice;
    console.log(`User response to the install prompt: ${outcome}`);
    deferredPrompt = null;
    showInstallPrompt.value = false;
  }
};

onMounted(async () => {
  await initialize();
  await checkServiceWorker();
  
  // Listen for install prompt
  window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
    showInstallPrompt.value = true;
  });

  // Listen for service worker updates and messages
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.addEventListener('controllerchange', () => {
      serviceWorkerReady.value = true;
    });

    // Listen for messages from service worker
    navigator.serviceWorker.addEventListener('message', (event) => {
      if (event.data && event.data.type === 'SW_READY') {
        serviceWorkerReady.value = true;
        console.log('✅', event.data.message);
      }
    });

    // Check if service worker is already controlling the page
    if (navigator.serviceWorker.controller) {
      serviceWorkerReady.value = true;
    }
  }

  // Show toast notification when coming back online
  let wasOffline = !isOnline.value;
  watch(isOnline, (online) => {
    if (wasOffline && online) {
      // Connection restored - sync will happen automatically
      console.log('Connection restored - syncing pending changes...');
    }
    wasOffline = !online;
  });
});
</script>

