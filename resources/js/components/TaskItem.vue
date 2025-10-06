<template>
  <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
    <div class="flex items-center justify-between">
      <div class="flex items-center space-x-3">
        <input
          type="checkbox"
          :checked="task.completed"
          @change="toggleCompleted"
          class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
        />
        <div class="flex-1">
          <h3 
            class="text-sm font-medium"
            :class="task.completed ? 'text-gray-500 line-through' : 'text-gray-900'"
          >
            {{ task.title }}
          </h3>
          <p 
            v-if="task.description"
            class="text-sm"
            :class="task.completed ? 'text-gray-400' : 'text-gray-600'"
          >
            {{ task.description }}
          </p>
          <div class="flex items-center space-x-4 mt-1">
            <span 
              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
              :class="priorityClasses"
            >
              {{ priorityText }}
            </span>
            <span v-if="task.due_date" class="text-xs text-gray-500">
              Due: {{ formatDate(task.due_date) }}
            </span>
            <span class="text-xs text-gray-400">
              {{ formatDate(task.created_at) }}
            </span>
          </div>
        </div>
      </div>
      
      <div class="flex items-center space-x-2">
        <button
          @click="editTask"
          class="text-gray-400 hover:text-gray-600 p-1"
          title="Edit task"
        >
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
          </svg>
        </button>
        <button
          @click="deleteTask"
          class="text-gray-400 hover:text-red-600 p-1"
          title="Delete task"
        >
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  task: {
    type: Object,
    required: true
  }
});

const emit = defineEmits(['update', 'delete']);

const priorityText = computed(() => {
  const priorities = {
    1: 'Low',
    2: 'Medium', 
    3: 'High'
  };
  return priorities[props.task.priority] || 'Low';
});

const priorityClasses = computed(() => {
  const classes = {
    1: 'bg-gray-100 text-gray-800',
    2: 'bg-yellow-100 text-yellow-800',
    3: 'bg-red-100 text-red-800'
  };
  return classes[props.task.priority] || 'bg-gray-100 text-gray-800';
});

const toggleCompleted = () => {
  emit('update', props.task.id, { completed: !props.task.completed });
};

const editTask = () => {
  // For now, just toggle completed. In a real app, you'd open an edit modal
  emit('update', props.task.id, { completed: !props.task.completed });
};

const deleteTask = () => {
  if (confirm('Are you sure you want to delete this task?')) {
    emit('delete', props.task.id);
  }
};

const formatDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric'
  });
};
</script>

