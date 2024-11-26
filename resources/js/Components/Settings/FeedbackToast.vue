<script setup>
import { CheckCircle2, XCircle } from 'lucide-vue-next';
import { TransitionRoot } from '@headlessui/vue';

defineProps({
  show: Boolean,
  message: String,
  type: {
    type: String,
    default: 'success'
  }
});

defineEmits(['close']);
</script>

<template>
  <TransitionRoot
    appear
    :show="show"
    enter="transform transition ease-out duration-300"
    enter-from="translate-y-2 opacity-0"
    enter-to="translate-y-0 opacity-100"
    leave="transform transition ease-in duration-200"
    leave-from="translate-y-0 opacity-100"
    leave-to="translate-y-2 opacity-0"
  >
    <div
      :class="[
        'fixed bottom-4 right-4 z-50 flex items-center gap-2 rounded-lg p-4 shadow-lg',
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
      ]"
    >
      <component
        :is="type === 'success' ? CheckCircle2 : XCircle"
        class="h-5 w-5 text-white"
      />
      <span class="text-sm font-medium text-white">{{ message }}</span>
      <button
        @click="$emit('close')"
        class="ml-2 rounded-lg p-1 hover:bg-white/20"
      >
        <span class="sr-only">Tutup</span>
        <svg class="h-4 w-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </button>
    </div>
  </TransitionRoot>
</template>
