import { ref } from 'vue'

interface Toast {
  message: string
  type: 'success' | 'error' | 'warning' | 'info'
  id: number
}

const toasts = ref<Toast[]>([])
let nextId = 0

export function useToast() {
  const showToast = (message: string, type: Toast['type'] = 'info') => {
    const id = nextId++

    // Add new toast
    toasts.value.push({
      message,
      type,
      id,
    })

    // Automatically remove toast after 3 seconds
    setTimeout(() => {
      removeToast(id)
    }, 3000)
  }

  const removeToast = (id: number) => {
    const index = toasts.value.findIndex(t => t.id === id)
    if (index > -1) {
      toasts.value.splice(index, 1)
    }
  }

  return {
    toasts,
    showToast,
    removeToast,
  }
}
