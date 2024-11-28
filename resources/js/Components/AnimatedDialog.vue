<template>
  <TransitionRoot appear :show="open" as="template">
    <Dialog as="div" @close="$emit('close')" :class="['relative z-50', className]">
      <!-- Backdrop -->
      <TransitionChild
        as="template"
        enter="duration-300 ease-out"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="duration-200 ease-in"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" aria-hidden="true" />
      </TransitionChild>

      <!-- Dialog position wrapper -->
      <div class="fixed inset-0 flex items-center justify-center p-4 sm:p-6">
        <!-- Dialog panel -->
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0 scale-95"
          enter-to="opacity-100 scale-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100 scale-100"
          leave-to="opacity-0 scale-95"
        >
          <DialogPanel
            :class="[
              'w-full transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all dark:bg-gray-800',
              // Default max width if none provided
              size === 'sm' && 'max-w-md',
              size === 'md' && 'max-w-2xl',
              size === 'lg' && 'max-w-4xl',
              size === 'xl' && 'max-w-6xl',
              size === 'full' && 'max-w-[90vw] max-h-[90vh] overflow-y-auto',
              // Allow custom max width through class prop
              panelClass
            ]"
          >
            <slot />
          </DialogPanel>
        </TransitionChild>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import { TransitionRoot, TransitionChild, Dialog, DialogPanel } from '@headlessui/vue'

const props = defineProps({
  open: {
    type: Boolean,
    required: true
  },
  // Add size prop for predefined sizes
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg', 'xl', 'full'].includes(value)
  },
  // Allow custom classes for the dialog wrapper
  className: {
    type: String,
    default: ''
  },
  // Allow custom classes for the panel
  panelClass: {
    type: String,
    default: ''
  }
})

defineEmits(['close'])
</script>

<style scoped>
/* Add custom scrollbar styling for the 'full' size dialog */
.max-h-[90vh] {
  scrollbar-width: thin;
  scrollbar-color: #CBD5E1 transparent;
}

.max-h-[90vh]::-webkit-scrollbar {
  width: 6px;
}

.max-h-[90vh]::-webkit-scrollbar-track {
  background: transparent;
}

.max-h-[90vh]::-webkit-scrollbar-thumb {
  background-color: #CBD5E1;
  border-radius: 3px;
}

/* Dark mode scrollbar */
:deep(.dark) .max-h-[90vh] {
  scrollbar-color: #4B5563 transparent;
}

:deep(.dark) .max-h-[90vh]::-webkit-scrollbar-thumb {
  background-color: #4B5563;
}
</style>
