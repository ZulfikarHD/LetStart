<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {
  User, MapPin, Phone, Mail, Camera, Calendar,
  Trophy, Clock, Heart, Edit2, CheckCircle2,
  Dumbbell, Star, Award, Target
} from 'lucide-vue-next';

// Dummy data
const user = ref({
  name: 'John Doe',
  email: 'john@example.com',
  phone: '081234567890',
  address: 'Jl. Contoh No. 123, Jakarta',
  avatar: null,
  memberSince: '2023',
  totalBookings: 45,
  favoriteVenues: 12,
  rating: 4.8,
  level: 'Gold Member',
  preferredSports: ['Futsal', 'Badminton', 'Basket'],
  recentBookings: [
    {
      id: 1,
      venue: 'Lapangan Futsal ABC',
      date: '2024-03-20',
      time: '19:00',
      sport: 'Futsal',
      status: 'upcoming',
      price: 250000
    },
    {
      id: 2,
      venue: 'GOR XYZ',
      date: '2024-03-15',
      time: '16:00',
      sport: 'Badminton',
      status: 'completed',
      price: 180000
    }
  ]
});

const photoPreview = ref(null);
const photoInput = ref(null);

const updatePhoto = (e) => {
  const file = e.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = (e) => {
      photoPreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
  }
};

const isEditing = ref(false);
const toggleEdit = () => {
  isEditing.value = !isEditing.value;
};
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Profil Saya" />

    <div class="min-h-screen bg-gray-50/50 py-8 dark:bg-gray-900">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <!-- Profile Header -->
        <div class="relative mb-8 overflow-hidden rounded-2xl bg-gradient-to-r from-appGreenLight via-appGreenMedium to-appBlueMedium p-8 shadow-xl shadow-appGreenLight/20">
          <div class="absolute inset-0 bg-black/10 backdrop-blur-sm"></div>

          <div class="relative flex flex-col items-center gap-6 md:flex-row md:gap-10">
            <!-- Avatar -->
            <div class="group relative h-36 w-36">
              <img
                :src="photoPreview || '/dummy-avatar.jpg'"
                class="h-36 w-36 rounded-full border-4 border-white object-cover shadow-2xl transition-all duration-300 group-hover:scale-105 dark:border-gray-700"
                alt="Profile photo"
              />
              <div
                @click="$refs.photo.click()"
                class="absolute inset-0 flex cursor-pointer items-center justify-center rounded-full bg-black/60 opacity-0 backdrop-blur-sm transition-all duration-300 group-hover:opacity-100"
              >
                <Camera class="h-10 w-10 text-white" />
              </div>
              <input
                ref="photo"
                type="file"
                class="hidden"
                @change="updatePhoto"
                accept="image/*"
              />
              <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 rounded-full bg-appBlueMedium px-4 py-1 text-sm font-medium text-white shadow-lg">
                {{ user.level }}
              </div>
            </div>

            <!-- User Info -->
            <div class="text-center md:text-left">
              <h1 class="text-3xl font-bold text-white">{{ user.name }}</h1>
              <p class="mt-2 text-lg text-white/90">Member sejak {{ user.memberSince }}</p>

              <!-- Stats -->
              <div class="mt-6 flex gap-8">
                <div class="text-center">
                  <div class="text-2xl font-bold text-white">{{ user.totalBookings }}</div>
                  <div class="mt-1 text-sm font-medium text-white/90">Total Booking</div>
                </div>
                <div class="text-center">
                  <div class="text-2xl font-bold text-white">{{ user.favoriteVenues }}</div>
                  <div class="mt-1 text-sm font-medium text-white/90">Tempat Favorit</div>
                </div>
                <div class="text-center">
                  <div class="flex items-center justify-center gap-1 text-2xl font-bold text-white">
                    {{ user.rating }}
                    <Star class="h-5 w-5 fill-current" />
                  </div>
                  <div class="mt-1 text-sm font-medium text-white/90">Rating</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="grid gap-8 lg:grid-cols-3">
          <!-- Main Info -->
          <div class="lg:col-span-2">
            <div class="rounded-2xl bg-white p-6 shadow-lg shadow-gray-200/50 transition-all duration-300 hover:shadow-xl dark:bg-gray-800 dark:shadow-none">
              <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Informasi Pribadi</h2>
                <button
                  @click="toggleEdit"
                  class="flex items-center gap-2 rounded-xl bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 transition-all duration-300 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
                >
                  <component :is="isEditing ? CheckCircle2 : Edit2" class="h-4 w-4" />
                  {{ isEditing ? 'Simpan' : 'Edit' }}
                </button>
              </div>

              <div class="mt-8 grid gap-8 md:grid-cols-2">
                <div class="space-y-3">
                  <div class="flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                    <User class="h-4 w-4" />
                    <span>Nama Lengkap</span>
                  </div>
                  <input
                    v-if="isEditing"
                    v-model="user.name"
                    type="text"
                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 transition-colors focus:border-appBlueMedium focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                  />
                  <div v-else class="text-lg text-gray-900 dark:text-white">{{ user.name }}</div>
                </div>

                <div class="space-y-3">
                  <div class="flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                    <Mail class="h-4 w-4" />
                    <span>Email</span>
                  </div>
                  <input
                    v-if="isEditing"
                    v-model="user.email"
                    type="email"
                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 transition-colors focus:border-appBlueMedium focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                  />
                  <div v-else class="text-lg text-gray-900 dark:text-white">{{ user.email }}</div>
                </div>

                <div class="space-y-3">
                  <div class="flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                    <Phone class="h-4 w-4" />
                    <span>Nomor Telepon</span>
                  </div>
                  <input
                    v-if="isEditing"
                    v-model="user.phone"
                    type="tel"
                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 transition-colors focus:border-appBlueMedium focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                  />
                  <div v-else class="text-lg text-gray-900 dark:text-white">{{ user.phone }}</div>
                </div>

                <div class="space-y-3">
                  <div class="flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                    <MapPin class="h-4 w-4" />
                    <span>Alamat</span>
                  </div>
                  <input
                    v-if="isEditing"
                    v-model="user.address"
                    type="text"
                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 transition-colors focus:border-appBlueMedium focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                  />
                  <div v-else class="text-lg text-gray-900 dark:text-white">{{ user.address }}</div>
                </div>
              </div>
            </div>

            <!-- Recent Bookings -->
            <div class="mt-8 rounded-2xl bg-white p-6 shadow-lg shadow-gray-200/50 transition-all duration-300 hover:shadow-xl dark:bg-gray-800 dark:shadow-none">
              <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Booking Terakhir</h2>
                <a href="/my-bookings" class="group flex items-center gap-2 text-sm font-medium text-appBlueMedium transition-colors hover:text-appBlueDark dark:text-appBlueLight">
                  Lihat Semua
                  <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M5 12h14M12 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </a>
              </div>

              <div class="mt-6 space-y-4">
                <div v-for="booking in user.recentBookings"
                     :key="booking.id"
                     class="group flex items-center justify-between rounded-xl border border-gray-100 bg-white p-4 shadow-sm transition-all duration-300 hover:border-appBlueMedium hover:shadow-lg dark:border-gray-700 dark:bg-gray-800/50">
                  <div class="flex items-center gap-4">
                    <div class="rounded-xl bg-appGreenLight/10 p-3 transition-colors group-hover:bg-appGreenLight/20 dark:bg-gray-700">
                      <Dumbbell class="h-6 w-6 text-appGreenDark dark:text-appGreenLight" />
                    </div>
                    <div>
                      <div class="font-medium text-gray-900 dark:text-white">{{ booking.venue }}</div>
                      <div class="mt-1 flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                        <span>{{ booking.sport }}</span>
                        <span class="h-1 w-1 rounded-full bg-gray-300 dark:bg-gray-600"></span>
                        <span>{{ booking.date }}</span>
                        <span class="h-1 w-1 rounded-full bg-gray-300 dark:bg-gray-600"></span>
                        <span>{{ booking.time }}</span>
                      </div>
                      <div class="mt-1 text-sm font-medium text-appGreenDark dark:text-appGreenLight">
                        Rp {{ booking.price.toLocaleString() }}
                      </div>
                    </div>
                  </div>
                  <span
                    :class="[
                      'rounded-xl px-4 py-2 text-sm font-medium transition-colors',
                      booking.status === 'upcoming'
                        ? 'bg-appGreenLight/10 text-appGreenDark group-hover:bg-appGreenLight/20 dark:text-appGreenLight'
                        : 'bg-gray-100 text-gray-600 group-hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300'
                    ]"
                  >
                    {{ booking.status === 'upcoming' ? 'Akan Datang' : 'Selesai' }}
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- Sidebar -->
          <div class="space-y-8">
            <!-- Preferred Sports -->
            <div class="rounded-2xl bg-white p-6 shadow-lg shadow-gray-200/50 transition-all duration-300 hover:shadow-xl dark:bg-gray-800 dark:shadow-none">
              <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Olahraga Favorit</h2>
              <div class="mt-6 flex flex-wrap gap-3">
                <span
                  v-for="sport in user.preferredSports"
                  :key="sport"
                  class="inline-flex items-center gap-2 rounded-xl bg-appGreenLight/10 px-4 py-2 text-sm font-medium text-appGreenDark transition-all duration-300 hover:bg-appGreenLight/20 hover:scale-105 dark:text-appGreenLight"
                >
                  <Dumbbell class="h-4 w-4" />
                  {{ sport }}
                </span>
              </div>
            </div>

            <!-- Achievements -->
            <div class="rounded-2xl bg-white p-6 shadow-lg shadow-gray-200/50 transition-all duration-300 hover:shadow-xl dark:bg-gray-800 dark:shadow-none">
              <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Pencapaian</h2>
              <div class="mt-6 space-y-6">
                <div class="group flex items-center gap-4">
                  <div class="rounded-xl bg-yellow-100 p-3 transition-all duration-300 group-hover:scale-110 dark:bg-yellow-900">
                    <Trophy class="h-6 w-6 text-yellow-600 dark:text-yellow-400" />
                  </div>
                  <div>
                    <div class="font-medium text-gray-900 dark:text-white">Regular Booker</div>
                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">10+ booking dalam sebulan</div>
                  </div>
                </div>
                <div class="group flex items-center gap-4">
                  <div class="rounded-xl bg-blue-100 p-3 transition-all duration-300 group-hover:scale-110 dark:bg-blue-900">
                    <Star class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                  </div>
                  <div>
                    <div class="font-medium text-gray-900 dark:text-white">Top Rated</div>
                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">Rating 4.8/5.0</div>
                  </div>
                </div>
                <div class="group flex items-center gap-4">
                  <div class="rounded-xl bg-purple-100 p-3 transition-all duration-300 group-hover:scale-110 dark:bg-purple-900">
                    <Award class="h-6 w-6 text-purple-600 dark:text-purple-400" />
                  </div>
                  <div>
                    <div class="font-medium text-gray-900 dark:text-white">Gold Member</div>
                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">Member aktif selama 1 tahun</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
