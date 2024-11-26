<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {
  User, MapPin, Phone, Mail, Camera, Calendar,
  Trophy, Clock, Heart, Edit2, CheckCircle2,
  Dumbbell, Star
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
  preferredSports: ['Futsal', 'Badminton', 'Basket'],
  recentBookings: [
    {
      id: 1,
      venue: 'Lapangan Futsal ABC',
      date: '2024-03-20',
      time: '19:00',
      sport: 'Futsal',
      status: 'upcoming'
    },
    {
      id: 2,
      venue: 'GOR XYZ',
      date: '2024-03-15',
      time: '16:00',
      sport: 'Badminton',
      status: 'completed'
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

    <div class="min-h-screen bg-gray-50 py-8 dark:bg-gray-900">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <!-- Profile Header -->
        <div class="relative mb-8 overflow-hidden rounded-xl bg-gradient-to-r from-appGreenLight to-appBlueMedium p-8">
          <div class="absolute inset-0 bg-black/10"></div>

          <div class="relative flex flex-col items-center gap-4 md:flex-row md:gap-8">
            <!-- Avatar -->
            <div class="group relative h-32 w-32">
              <img
                :src="photoPreview || '/dummy-avatar.jpg'"
                class="h-32 w-32 rounded-full border-4 border-white object-cover shadow-lg transition-transform group-hover:scale-105 dark:border-gray-700"
                alt="Profile photo"
              />
              <div
                @click="$refs.photo.click()"
                class="absolute inset-0 flex cursor-pointer items-center justify-center rounded-full bg-black bg-opacity-50 opacity-0 transition-opacity group-hover:opacity-100"
              >
                <Camera class="h-8 w-8 text-white" />
              </div>
              <input
                ref="photo"
                type="file"
                class="hidden"
                @change="updatePhoto"
                accept="image/*"
              />
            </div>

            <!-- User Info -->
            <div class="text-center md:text-left">
              <h1 class="text-2xl font-bold text-white">{{ user.name }}</h1>
              <p class="mt-1 text-white/80">Member sejak {{ user.memberSince }}</p>

              <!-- Stats -->
              <div class="mt-4 flex gap-6">
                <div class="text-center">
                  <div class="text-xl font-bold text-white">{{ user.totalBookings }}</div>
                  <div class="text-sm text-white/80">Booking</div>
                </div>
                <div class="text-center">
                  <div class="text-xl font-bold text-white">{{ user.favoriteVenues }}</div>
                  <div class="text-sm text-white/80">Favorit</div>
                </div>
                <div class="text-center">
                  <div class="text-xl font-bold text-white">{{ user.rating }}</div>
                  <div class="text-sm text-white/80">Rating</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="grid gap-8 lg:grid-cols-3">
          <!-- Main Info -->
          <div class="lg:col-span-2">
            <div class="rounded-xl bg-white p-6 shadow-sm dark:bg-gray-800">
              <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Pribadi</h2>
                <button
                  @click="toggleEdit"
                  class="flex items-center gap-2 rounded-lg px-3 py-1.5 text-sm font-medium text-gray-600 transition-colors hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
                >
                  <component :is="isEditing ? CheckCircle2 : Edit2" class="h-4 w-4" />
                  {{ isEditing ? 'Simpan' : 'Edit' }}
                </button>
              </div>

              <div class="mt-6 grid gap-6 md:grid-cols-2">
                <div class="space-y-2">
                  <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                    <User class="h-4 w-4" />
                    <span>Nama Lengkap</span>
                  </div>
                  <input
                    v-if="isEditing"
                    v-model="user.name"
                    type="text"
                    class="w-full rounded-lg border border-gray-200 px-3 py-2 dark:border-gray-700 dark:bg-gray-900"
                  />
                  <div v-else class="text-gray-900 dark:text-white">{{ user.name }}</div>
                </div>

                <div class="space-y-2">
                  <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                    <Mail class="h-4 w-4" />
                    <span>Email</span>
                  </div>
                  <input
                    v-if="isEditing"
                    v-model="user.email"
                    type="email"
                    class="w-full rounded-lg border border-gray-200 px-3 py-2 dark:border-gray-700 dark:bg-gray-900"
                  />
                  <div v-else class="text-gray-900 dark:text-white">{{ user.email }}</div>
                </div>

                <div class="space-y-2">
                  <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                    <Phone class="h-4 w-4" />
                    <span>Nomor Telepon</span>
                  </div>
                  <input
                    v-if="isEditing"
                    v-model="user.phone"
                    type="tel"
                    class="w-full rounded-lg border border-gray-200 px-3 py-2 dark:border-gray-700 dark:bg-gray-900"
                  />
                  <div v-else class="text-gray-900 dark:text-white">{{ user.phone }}</div>
                </div>

                <div class="space-y-2">
                  <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                    <MapPin class="h-4 w-4" />
                    <span>Alamat</span>
                  </div>
                  <input
                    v-if="isEditing"
                    v-model="user.address"
                    type="text"
                    class="w-full rounded-lg border border-gray-200 px-3 py-2 dark:border-gray-700 dark:bg-gray-900"
                  />
                  <div v-else class="text-gray-900 dark:text-white">{{ user.address }}</div>
                </div>
              </div>
            </div>

            <!-- Recent Bookings -->
            <div class="mt-8 rounded-xl bg-white p-6 shadow-sm dark:bg-gray-800">
              <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Booking Terakhir</h2>
                <a href="/my-bookings" class="text-sm text-appBlueMedium hover:text-appBlueDark dark:text-appBlueLight">
                  Lihat Semua
                </a>
              </div>

              <div class="mt-6 space-y-4">
                <div v-for="booking in user.recentBookings"
                     :key="booking.id"
                     class="flex items-center justify-between rounded-lg border border-gray-100 p-4 dark:border-gray-700">
                  <div class="flex items-center gap-4">
                    <div class="rounded-lg bg-gray-100 p-2 dark:bg-gray-700">
                      <Dumbbell class="h-6 w-6 text-gray-500 dark:text-gray-400" />
                    </div>
                    <div>
                      <div class="font-medium text-gray-900 dark:text-white">{{ booking.venue }}</div>
                      <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ booking.sport }} • {{ booking.date }} • {{ booking.time }}
                      </div>
                    </div>
                  </div>
                  <span
                    :class="[
                      'rounded-full px-3 py-1 text-sm font-medium',
                      booking.status === 'upcoming'
                        ? 'bg-appGreenLight/10 text-appGreenDark'
                        : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300'
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
            <div class="rounded-xl bg-white p-6 shadow-sm dark:bg-gray-800">
              <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Olahraga Favorit</h2>
              <div class="mt-4 flex flex-wrap gap-2">
                <span
                  v-for="sport in user.preferredSports"
                  :key="sport"
                  class="inline-flex items-center gap-1 rounded-full bg-appGreenLight/10 px-3 py-1 text-sm font-medium text-appGreenDark dark:text-appGreenLight"
                >
                  <Dumbbell class="h-4 w-4" />
                  {{ sport }}
                </span>
              </div>
            </div>

            <!-- Achievements -->
            <div class="rounded-xl bg-white p-6 shadow-sm dark:bg-gray-800">
              <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Pencapaian</h2>
              <div class="mt-4 space-y-4">
                <div class="flex items-center gap-3">
                  <div class="rounded-lg bg-yellow-100 p-2 dark:bg-yellow-900">
                    <Trophy class="h-5 w-5 text-yellow-600 dark:text-yellow-400" />
                  </div>
                  <div>
                    <div class="font-medium text-gray-900 dark:text-white">Regular Booker</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">10+ booking dalam sebulan</div>
                  </div>
                </div>
                <div class="flex items-center gap-3">
                  <div class="rounded-lg bg-blue-100 p-2 dark:bg-blue-900">
                    <Star class="h-5 w-5 text-blue-600 dark:text-blue-400" />
                  </div>
                  <div>
                    <div class="font-medium text-gray-900 dark:text-white">Top Rated</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Rating 4.8/5.0</div>
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
