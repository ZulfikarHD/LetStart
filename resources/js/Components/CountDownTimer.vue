<template>
  <div class="flex items-center gap-2">
    <LucideClock class="h-4 w-4" />
    <span v-if="isExpired">Waktu telah berakhir</span>
    <span v-else>{{ formattedTimeRemaining }}</span>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Clock as LucideClock } from 'lucide-vue-next'

const props = defineProps({
  date: {
    type: String,
    required: true
  },
  startTime: {
    type: String,
    required: true
  }
})

const timeRemaining = ref('')
const isExpired = ref(false)
let timer

const calculateTimeRemaining = () => {
  const [startHour, startMinute] = props.startTime.split(':')
  const targetDate = new Date(props.date)
  targetDate.setHours(parseInt(startHour), parseInt(startMinute), 0)

  const now = new Date()
  const diff = targetDate - now

  if (diff <= 0) {
    isExpired.value = true
    return
  }

  const days = Math.floor(diff / (1000 * 60 * 60 * 24))
  const remainingHours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))
  const remainingMinutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60))

  if (days > 0) {
    timeRemaining.value = `${days} hari ${remainingHours} jam lagi`
  } else if (remainingHours > 0) {
    timeRemaining.value = `${remainingHours} jam ${remainingMinutes} menit lagi`
  } else {
    timeRemaining.value = `${remainingMinutes} menit lagi`
  }
}

const formattedTimeRemaining = computed(() => {
  return timeRemaining.value
})

onMounted(() => {
  calculateTimeRemaining()
  timer = setInterval(calculateTimeRemaining, 60000) // Update every minute
})

onUnmounted(() => {
  if (timer) clearInterval(timer)
})
</script>
