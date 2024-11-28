<script setup>
import { ref, computed } from 'vue';
import { TransitionGroup } from 'vue';
import { useToast } from '@/Composables/useToast';
import { Head } from '@inertiajs/vue3';
import {
  Heart, Target, Activity, Award, Dumbbell,
  MapPin, Star, Building2, ArrowRight, Plus, Trash2, Trophy
} from 'lucide-vue-next';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AnimatedDialog from '@/Components/AnimatedDialog.vue';
import AddVenueModal from '@/Pages/User/Settings/PreferencesSettings/AddVenueModal.vue'

// Dummy data for sports
const sports = ref([
  {
    id: 1,
    name: 'Futsal',
    icon: Target, // Using Target as alternative for Football
    active: true
  },
  {
    id: 2,
    name: 'Badminton',
    icon: Activity, // Using Activity as alternative for Racquet
    active: true
  },
  {
    id: 3,
    name: 'Basket',
    icon: Target, // Using Target as alternative for Basketball
    active: false
  },
  {
    id: 4,
    name: 'Voli',
    icon: Activity, // Using Activity as alternative for Volleyball
    active: false
  },
  {
    id: 5,
    name: 'Sepeda',
    icon: Award, // Using Award as alternative for Bike
    active: false
  },
  {
    id: 6,
    name: 'Gym',
    icon: Dumbbell, // Dumbbell exists in Lucide
    active: true
  }
]);

// Dummy data for venues
const venues = ref([
  {
    id: 1,
    name: 'GOR Sahabat Futsal',
    location: 'Jakarta Selatan',
    rating: 4.8,
    sport: 'Futsal',
    image: '/images/venues/futsal.jpg'
  },
  {
    id: 2,
    name: 'PB Duta Badminton',
    location: 'Jakarta Timur',
    rating: 4.9,
    sport: 'Badminton',
    image: '/images/venues/badminton.jpg'
  },
  {
    id: 3,
    name: 'Basket Arena Champions',
    location: 'Jakarta Pusat',
    rating: 4.7,
    sport: 'Basket',
    image: '/images/venues/basket.jpg'
  },
  {
    id: 4,
    name: 'PowerGym Fitness Center',
    location: 'Jakarta Barat',
    rating: 4.6,
    sport: 'Gym',
    image: '/images/venues/gym.jpg'
  }
]);

const showDeleteDialog = ref(false);
const selectedVenueId = ref(null);
const isLoading = ref(false);
const { showToast } = useToast();
const showAddModal = ref(false)

const toggleSport = async (sportId) => {
  isLoading.value = true;
  try {
    const sport = sports.value.find(s => s.id === sportId);
    if (sport) {
      sport.active = !sport.active;
      showToast('Preferensi olahraga berhasil diperbarui', 'success');
    }
  } catch (error) {
    showToast('Gagal memperbarui preferensi', 'error');
  } finally {
    isLoading.value = false;
  }
};

const confirmDelete = (venueId) => {
  selectedVenueId.value = venueId;
  showDeleteDialog.value = true;
};

const handleRemoveVenue = () => {
  venues.value = venues.value.filter(v => v.id !== selectedVenueId.value);
  showDeleteDialog.value = false;
  showToast('Venue berhasil dihapus dari favorit', 'success');
};

const handleVenueAdded = (newVenue) => {
  venues.value.push(newVenue)
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="space-y-8 p-6 max-w-7xl mx-auto">
      <!-- Header -->
      <div>
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center gap-2">
          <Heart class="h-6 w-6 text-appGreenLight" />
          Preferensi Olahraga & Venue
        </h2>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
          Atur olahraga dan venue favorit Anda untuk rekomendasi yang lebih baik
        </p>
      </div>

      <!-- Sports Section -->
      <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <h3 class="flex items-center gap-2 text-lg font-medium text-gray-900 dark:text-white">
          <Trophy class="h-5 w-5 text-appBlueLight" />
          Olahraga Favorit
        </h3>

        <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4">
          <button
            v-for="sport in sports"
            :key="sport.id"
            @click="toggleSport(sport.id)"
            :disabled="isLoading"
            :class="[
              'flex items-center gap-3 rounded-xl border p-4 transition-all duration-300',
              'hover:scale-105 active:scale-95',
              sport.active
                ? 'border-appGreenLight bg-appGreenLight/10'
                : 'border-gray-200 hover:border-appGreenLight/50'
            ]"
          >
            <component
              :is="sport.icon"
              :class="[
                'h-6 w-6',
                sport.active
                  ? 'text-appGreenDark dark:text-appGreenLight'
                  : 'text-gray-500 dark:text-gray-400'
              ]"
            />
            <span
              :class="[
                'font-medium',
                sport.active
                  ? 'text-appGreenDark dark:text-appGreenLight'
                  : 'text-gray-700 dark:text-gray-300'
              ]"
            >
              {{ sport.name }}
            </span>
            <div v-if="isLoading" class="absolute inset-0 flex items-center justify-center bg-white/50 dark:bg-gray-800/50">
              <span class="loading loading-spinner loading-sm"></span>
            </div>
          </button>
        </div>

        <!-- Empty State for Sports -->
        <div v-if="!sports.some(s => s.active)" class="mt-4 text-center text-sm text-gray-500">
          Pilih minimal satu olahraga untuk mendapatkan rekomendasi venue yang lebih baik
        </div>
      </div>

      <!-- Venues Section -->
      <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="flex items-center justify-between">
          <h3 class="flex items-center gap-2 text-lg font-medium text-gray-900 dark:text-white">
            <Building2 class="h-5 w-5 text-appBlueLight" />
            Venue Favorit
          </h3>

          <button
            @click="showAddModal = true"
            class="inline-flex items-center gap-2 rounded-lg bg-appGreenLight px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-appGreenMedium"
          >
            <Plus class="h-4 w-4" />
            Tambah Venue
          </button>
        </div>

        <TransitionGroup
          tag="div"
          enter-active-class="transition-all duration-300 ease-out"
          enter-from-class="opacity-0 transform -translate-x-4"
          enter-to-class="opacity-100 transform translate-x-0"
          leave-active-class="transition-all duration-300 ease-in"
          leave-from-class="opacity-100 transform translate-x-0"
          leave-to-class="opacity-0 transform translate-x-4"
          class="mt-6 space-y-4"
        >
          <div
            v-for="venue in venues"
            :key="venue.id"
            class="group relative flex cursor-pointer items-center justify-between rounded-xl border border-gray-100 bg-white p-4 transition-all hover:border-appGreenLight/50 hover:shadow-md dark:border-gray-700 dark:bg-gray-800"
          >
            <div class="flex items-center gap-4">
              <div class="rounded-lg bg-gray-100 p-2 dark:bg-gray-700">
                <Building2 class="h-6 w-6 text-appBlueLight" />
              </div>

              <div>
                <h4 class="font-medium text-gray-900 dark:text-white">
                  {{ venue.name }}
                </h4>
                <div class="mt-1 flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
                  <span class="flex items-center gap-1">
                    <MapPin class="h-4 w-4" />
                    {{ venue.location }}
                  </span>
                  <span class="flex items-center gap-1">
                    <Star class="h-4 w-4 text-yellow-400" />
                    {{ venue.rating }}
                  </span>
                </div>
              </div>
            </div>

            <div class="flex items-center gap-2">
              <button
                @click.stop="confirmDelete(venue.id)"
                class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-red-50 hover:text-red-500"
                title="Hapus dari favorit"
              >
                <Trash2 class="h-5 w-5" />
              </button>
            </div>
          </div>
        </TransitionGroup>

        <!-- Empty State for Venues -->
        <div v-if="venues.length === 0" class="mt-6 text-center">
          <Building2 class="mx-auto h-12 w-12 text-gray-400" />
          <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada venue favorit</h3>
          <p class="mt-1 text-sm text-gray-500">Mulai tambahkan venue favorit Anda untuk akses lebih cepat</p>
        </div>
      </div>

      <!-- Delete Confirmation Dialog -->
      <AnimatedDialog :open="showDeleteDialog" @close="showDeleteDialog = false">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Hapus Venue Favorit</h3>
        <p class="mt-2 text-sm text-gray-500">Apakah Anda yakin ingin menghapus venue ini dari daftar favorit?</p>

        <div class="mt-4 flex justify-end gap-3">
          <button
            class="rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
            @click="showDeleteDialog = false"
          >
            Batal
          </button>
          <button
            class="rounded-lg bg-red-500 px-4 py-2 text-sm font-medium text-white hover:bg-red-600"
            @click="handleRemoveVenue"
          >
            Hapus
          </button>
        </div>
      </AnimatedDialog>

      <!-- Add the modal component -->
      <AddVenueModal
        :is-open="showAddModal"
        @close="showAddModal = false"
        @venue-added="handleVenueAdded"
      />
    </div>
  </AuthenticatedLayout>
</template>
