<script setup lang="ts">
import { CheckCircle2, XCircle, AlertCircle, Info, X } from 'lucide-vue-next'
import { useToast } from '@/Composables/useToast'

const { toasts, removeToast } = useToast()

const getIcon = (type: string) => {
  switch (type) {
    case 'success':
      return CheckCircle2
    case 'error':
      return XCircle
    case 'warning':
      return AlertCircle
    default:
      return Info
  }
}

const getToastClasses = (type: string) => {
  const baseClasses = 'flex items-center gap-3 rounded-xl p-4 shadow-lg backdrop-blur-sm border transition-all duration-300 transform'

  const typeClasses = {
    success: 'bg-green-50 border-green-200 dark:bg-green-900/20 dark:border-green-800',
    error: 'bg-red-50 border-red-200 dark:bg-red-900/20 dark:border-red-800',
    warning: 'bg-yellow-50 border-yellow-200 dark:bg-yellow-900/20 dark:border-yellow-800',
    info: 'bg-blue-50 border-blue-200 dark:bg-blue-900/20 dark:border-blue-800'
  }

  return `${baseClasses} ${typeClasses[type] || typeClasses.info}`
}
</script>

<template>
  <div aria-live="assertive" class="fixed inset-0 z-50 flex items-end px-4 py-6 pointer-events-none sm:p-6">
    <div class="flex flex-col items-center space-y-4 w-full sm:items-end">
      <TransitionGroup
        name="toast"
        tag="div"
        class="space-y-4"
      >
        <div
          v-for="toast in toasts"
          :key="toast.id"
          :class="getToastClasses(toast.type)"
          class="w-full max-w-sm pointer-events-auto"
        >
          <component
            :is="getIcon(toast.type)"
            :class="{
              'text-green-500 dark:text-green-400': toast.type === 'success',
              'text-red-500 dark:text-red-400': toast.type === 'error',
              'text-yellow-500 dark:text-yellow-400': toast.type === 'warning',
              'text-blue-500 dark:text-blue-400': toast.type === 'info'
            }"
            class="h-5 w-5 flex-shrink-0"
          />

          <div class="flex-1">
            <p
              v-if="toast.title"
              class="text-sm font-medium text-gray-900 dark:text-white"
            >
              {{ toast.title }}
            </p>
            <p
              :class="{'mt-1': toast.title}"
              class="text-sm text-gray-600 dark:text-gray-300"
            >
              {{ toast.message }}
            </p>
            <div
              v-if="toast.action"
              class="mt-2"
            >
              <button
                @click="toast.action.onClick"
                class="text-sm font-medium text-appBlueMedium hover:text-appBlueDark dark:text-appBlueLight"
              >
                {{ toast.action.label }}
              </button>
            </div>
          </div>

          <button
            @click="() => removeToast(toast.id)"
            class="ml-4 flex-shrink-0 rounded-lg p-1 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-200"
          >
            <span class="sr-only">Tutup</span>
            <X class="h-4 w-4 text-gray-400" />
          </button>
        </div>
      </TransitionGroup>
    </div>
  </div>
</template>

<style scoped>
.toast-move, /* apply transition to moving elements */
.toast-enter-active,
.toast-leave-active {
  transition: all 0.3s ease;
}

.toast-enter-from {
  opacity: 0;
  transform: translateX(30px);
}

.toast-leave-to {
  opacity: 0;
  transform: translateX(30px);
}

/* ensure leaving items are taken out of layout flow so that moving
   animations can be calculated correctly. */
.toast-leave-active {
  position: absolute;
}
</style>
