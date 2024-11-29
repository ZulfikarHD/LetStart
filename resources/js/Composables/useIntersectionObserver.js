import { ref, onMounted, onUnmounted } from 'vue'

export function useIntersectionObserver(options = {}) {
  const isIntersecting = ref(false)
  const target = ref(null)

  const observer = new IntersectionObserver(([entry]) => {
    isIntersecting.value = entry.isIntersecting
  }, {
    threshold: options.threshold || 0.1,
    rootMargin: options.rootMargin || '0px'
  })

  onMounted(() => {
    if (target.value) observer.observe(target.value)
  })

  onUnmounted(() => {
    observer.disconnect()
  })

  return { isIntersecting, target }
}
