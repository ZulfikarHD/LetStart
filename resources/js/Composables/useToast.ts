import { ref } from 'vue'

interface Toast {
  message: string
  type: 'success' | 'error' | 'warning' | 'info'
  id: number
  title?: string
  duration?: number
  action?: {
    label: string
    onClick: () => void
  }
  icon?: string
}

const toasts = ref<Toast[]>([])
let nextId = 0

export function useToast() {
  const showToast = ({
    message,
    type = 'info',
    title,
    duration = 3000,
    action,
    icon
  }: Omit<Toast, 'id'>) => {
    const id = nextId++

    // Add new toast
    toasts.value.push({
      message,
      type,
      id,
      title,
      duration,
      action,
      icon
    })

    // Don't auto-remove if there's an action
    if (!action) {
      setTimeout(() => {
        removeToast(id)
      }, duration)
    }
  }

  const removeToast = (id: number) => {
    const index = toasts.value.findIndex(t => t.id === id)
    if (index > -1) {
      toasts.value.splice(index, 1)
    }
  }

  // Convenience methods
  const success = (message: string, options = {}) => showToast({ message, type: 'success', ...options })
  const error = (message: string, options = {}) => showToast({ message, type: 'error', ...options })
  const warning = (message: string, options = {}) => showToast({ message, type: 'warning', ...options })
  const info = (message: string, options = {}) => showToast({ message, type: 'info', ...options })

  return {
    toasts,
    showToast,
    removeToast,
    success,
    error,
    warning,
    info
  }
}
