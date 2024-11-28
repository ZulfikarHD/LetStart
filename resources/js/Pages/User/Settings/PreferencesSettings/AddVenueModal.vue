<script setup>
import { ref, computed } from 'vue'
import { MapPin, Search, Building2, Plus } from 'lucide-vue-next'
import AnimatedDialog from '@/Components/AnimatedDialog.vue'

const props = defineProps({
  isOpen: {
    type: Boolean,
    required: true
  }
})

const emit = defineEmits(['close', 'venue-added'])

const searchQuery = ref('')
const selectedVenue = ref(null)

// Dummy data untuk venue yang tersedia
const availableVenues = ref([
  {
    id: 5,
    name: 'GOR Futsal Senayan',
    location: 'Jakarta Pusat',
    rating: 4.5,
    sport: 'Futsal',
    image: '/images/venues/futsal-2.jpg'
  },
  {
    id: 6,
    name: 'Badminton Hall Gading',
    location: 'Jakarta Utara',
    rating: 4.7,
    sport: 'Badminton',
    image: '/images/venues/badminton-2.jpg'
  }
])

const filteredVenues = computed(() => {
  if (!searchQuery.value) return availableVenues.value

  const query = searchQuery.value.toLowerCase()
  return availableVenues.value.filter(venue =>
    venue.name.toLowerCase().includes(query) ||
    venue.location.toLowerCase().includes(query)
  )
})

const handleClose = () => {
  searchQuery.value = ''
  selectedVenue.value = null
  emit('close')
}

const handleAddVenue = () => {
  if (selectedVenue.value) {
    emit('venue-added', selectedVenue.value)
    handleClose()
  }
}
</script>

<template>
  <AnimatedDialog :open="isOpen" @close="handleClose">
    <div class="mb-6">
      <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center gap-2">
        <Plus class="h-5 w-5 text-appGreenLight" />
        Tambah Venue Favorit
      </h3>
      <p class="mt-1 text-sm text-gray-500">
        Cari dan pilih venue yang ingin Anda tambahkan ke daftar favorit
      </p>
    </div>

    <!-- Search Input -->
    <div class="relative mb-6">
      <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
        <Search class="h-5 w-5 text-gray-400" />
      </div>
      <input
        v-model="searchQuery"
        type="text"
        class="block w-full rounded-lg border border-gray-200 bg-white py-2.5 pl-10 pr-3 text-sm placeholder:text-gray-400 focus:border-appGreenLight focus:outline-none focus:ring-1 focus:ring-appGreenLight dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:placeholder:text-gray-500"
        placeholder="Cari venue berdasarkan nama atau lokasi..."
      />
    </div>

    <!-- Venues List -->
    <div class="max-h-[300px] space-y-3 overflow-y-auto">
      <button
        v-for="venue in filteredVenues"
        :key="venue.id"
        @click="selectedVenue = venue"
        class="w-full"
      >
        <div
          :class="[
            'flex items-center justify-between rounded-xl border p-4 transition-all',
            selectedVenue?.id === venue.id
              ? 'border-appGreenLight bg-appGreenLight/10'
              : 'border-gray-100 hover:border-appGreenLight/50 dark:border-gray-700'
          ]"
        >
          <div class="flex items-center gap-4">
            <div class="rounded-lg bg-gray-100 p-2 dark:bg-gray-700">
              <Building2 class="h-6 w-6 text-appBlueLight" />
            </div>
            <div class="text-left">
              <h4 class="font-medium text-gray-900 dark:text-white">
                {{ venue.name }}
              </h4>
              <div class="mt-1 flex items-center gap-2 text-sm text-gray-500">
                <MapPin class="h-4 w-4" />
                {{ venue.location }}
              </div>
            </div>
          </div>
        </div>
      </button>
    </div>

    <!-- Empty State -->
    <div
      v-if="filteredVenues.length === 0"
      class="py-12 text-center"
    >
      <Building2 class="mx-auto h-12 w-12 text-gray-400" />
      <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
        Tidak ada venue ditemukan
      </h3>
      <p class="mt-1 text-sm text-gray-500">
        Coba cari dengan kata kunci lain
      </p>
    </div>

    <!-- Actions -->
    <div class="mt-6 flex justify-end gap-3">
      <button
        type="button"
        class="rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
        @click="handleClose"
      >
        Batal
      </button>
      <button
        type="button"
        class="rounded-lg bg-appGreenLight px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-appGreenMedium disabled:cursor-not-allowed disabled:opacity-50"
        :disabled="!selectedVenue"
        @click="handleAddVenue"
      >
        Tambah ke Favorit
      </button>
    </div>
  </AnimatedDialog>
</template>
