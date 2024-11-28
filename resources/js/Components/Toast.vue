<script setup lang="ts">
import { XCircle, CheckCircle, AlertCircle, Info } from 'lucide-vue-next'
import { TransitionGroup } from 'vue'
import { useToast } from '@/Composables/useToast'

const { toasts, removeToast } = useToast()

const getIcon = (type: string) => {
  switch (type) {
    case 'success':
      return CheckCircle
    case 'error':
      return XCircle
    case 'warning':
      return AlertCircle
    default:
      return Info
  }
}

const getToastClasses = (type: string) => {
  switch (type) {
    case 'success':
      return 'bg-appGreenLight text-white'
    case 'error':
      return 'bg-red-500 text-white'
    case 'warning':
      return 'bg-yellow-500 text-white'
    default:
      return 'bg-appBlueLight text-white'
  }
}
</script>

<template>
  <div
    aria-live="assertive"
    class="fixed inset-0 z-50 flex items-end px-4 py-6 pointer-events-none sm:p-6"
  >
    <div class="flex flex-col items-center w-full space-y-4 sm:items-end">
      <TransitionGroup
        enter-active-class="transform ease-out duration-300 transition"
        enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
        leave-active-class="transition ease-in duration-100"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div
          v-for="toast in toasts"
          :key="toast.id"
          class="w-full max-w-sm overflow-hidden rounded-lg shadow-lg pointer-events-auto"
        >
          <div
            :class="[
              'relative flex items-center p-4',
              getToastClasses(toast.type)
            ]"
          >
            <div class="flex-shrink-0">
              <component
                :is="getIcon(toast.type)"
                class="w-5 h-5"
                aria-hidden="true"
              />
            </div>
            <div class="ml-3 w-0 flex-1">
              <p class="text-sm font-medium">
                {{ toast.message }}
              </p>
            </div>
            <div class="flex flex-shrink-0 ml-4">
              <button
                type="button"
                class="inline-flex text-white hover:opacity-75 focus:outline-none focus:ring-2 focus:ring-white"
                @click="() => removeToast(toast.id)"
              >
                <span class="sr-only">Tutup</span>
                <XCircle class="w-5 h-5" aria-hidden="true" />
              </button>
            </div>
          </div>
        </div>
      </TransitionGroup>
    </div>
  </div>
</template>
