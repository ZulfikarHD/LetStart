<script setup>
import { ref } from 'vue';
import { Switch } from '@headlessui/vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {
  Bell, Mail, Phone, Calendar, Tag, Clock,
  MessageSquare, Trophy, Shield, AlertCircle
} from 'lucide-vue-next';

const notificationGroups = [
  {
    id: 'booking',
    name: 'Booking & Jadwal',
    icon: Calendar,
    description: 'Notifikasi terkait booking dan jadwal olahraga Anda',
    notifications: [
      {
        id: 'booking_confirmation',
        name: 'Konfirmasi Booking',
        description: 'Saat booking Anda berhasil dikonfirmasi'
      },
      {
        id: 'booking_reminder',
        name: 'Pengingat Jadwal',
        description: 'Pengingat 1 jam sebelum jadwal booking Anda'
      },
      {
        id: 'booking_changes',
        name: 'Perubahan Booking',
        description: 'Saat ada perubahan pada booking Anda'
      }
    ]
  },
  {
    id: 'promo',
    name: 'Promo & Penawaran',
    icon: Tag,
    description: 'Informasi promo dan penawaran khusus',
    notifications: [
      {
        id: 'special_offers',
        name: 'Penawaran Khusus',
        description: 'Promo dan diskon khusus untuk Anda'
      },
      {
        id: 'new_venues',
        name: 'Venue Baru',
        description: 'Informasi venue baru yang tersedia'
      }
    ]
  },
  {
    id: 'community',
    name: 'Komunitas',
    icon: MessageSquare,
    description: 'Update dari komunitas olahraga',
    notifications: [
      {
        id: 'event_updates',
        name: 'Event Olahraga',
        description: 'Informasi event dan turnamen'
      },
      {
        id: 'achievement_updates',
        name: 'Pencapaian',
        description: 'Saat Anda mendapatkan pencapaian baru'
      }
    ]
  }
];

const channels = [
  { id: 'email', name: 'Email', icon: Mail },
  { id: 'push', name: 'Push Notification', icon: Bell },
  { id: 'whatsapp', name: 'WhatsApp', icon: Phone }
];

const settings = ref({});

// Initialize settings
notificationGroups.forEach(group => {
  group.notifications.forEach(notification => {
    settings.value[notification.id] = {
      email: true,
      push: true,
      whatsapp: false
    };
  });
});

const saveSettings = () => {
  // Dummy function for now
  console.log('Settings saved:', settings.value);
};
</script>

<template>
  <AuthenticatedLayout>
    <div class="min-h-screen space-y-8 bg-gray-50 px-4 py-8 dark:bg-gray-900 sm:px-6 lg:px-8">
      <!-- Header with Save Button -->
      <div class="mx-auto max-w-2xl">
        <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
          <div>
            <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
              Pengaturan Notifikasi
            </h2>
            <p class="mt-2 text-base text-gray-500 dark:text-gray-400">
              Atur bagaimana Anda ingin menerima notifikasi dari kami
            </p>
          </div>
          <button
            @click="saveSettings"
            class="group inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-appGreenLight to-appGreenMedium px-6 py-3 text-sm font-semibold text-white shadow-lg transition-all hover:from-appGreenMedium hover:to-appGreenDark hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-appGreenLight focus:ring-offset-2 dark:focus:ring-offset-gray-900"
          >
            <Bell class="h-5 w-5 transition-transform group-hover:scale-110" />
            Simpan Pengaturan
          </button>
        </div>

        <!-- Notification Groups -->
        <div class="mt-8 space-y-6">
          <div
            v-for="group in notificationGroups"
            :key="group.id"
            class="transform rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition-all hover:shadow-md dark:border-gray-700 dark:bg-gray-800"
          >
            <!-- Group Header -->
            <div class="flex items-start gap-4">
              <div class="rounded-xl bg-gradient-to-br from-appGreenLight/10 to-appBlueMedium/10 p-3 dark:from-appGreenLight/20 dark:to-appBlueMedium/20">
                <component :is="group.icon" class="h-6 w-6 text-appGreenMedium dark:text-appGreenLight" />
              </div>
              <div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                  {{ group.name }}
                </h3>
                <p class="mt-1 text-base text-gray-500 dark:text-gray-400">
                  {{ group.description }}
                </p>
              </div>
            </div>

            <!-- Notification Items -->
            <div class="mt-6 space-y-6">
              <div
                v-for="notification in group.notifications"
                :key="notification.id"
                class="transform rounded-lg border border-gray-100 bg-gray-50/50 p-5 transition-all hover:bg-white dark:border-gray-700 dark:bg-gray-800/50 dark:hover:bg-gray-800"
              >
                <div class="mb-4">
                  <h4 class="text-base font-semibold text-gray-900 dark:text-white">
                    {{ notification.name }}
                  </h4>
                  <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ notification.description }}
                  </p>
                </div>

                <!-- Channel Toggles -->
                <div class="space-y-4">
                  <div
                    v-for="channel in channels"
                    :key="channel.id"
                    class="flex items-center justify-between rounded-lg bg-white p-3 shadow-sm dark:bg-gray-800/50"
                  >
                    <div class="flex items-center gap-3">
                      <div class="rounded-full bg-gray-100 p-2 dark:bg-gray-700">
                        <component :is="channel.icon" class="h-4 w-4 text-appGreenMedium dark:text-appGreenLight" />
                      </div>
                      <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ channel.name }}
                      </span>
                    </div>
                    <Switch
                      v-model="settings[notification.id][channel.id]"
                      :class="[
                        settings[notification.id][channel.id]
                          ? 'bg-gradient-to-r from-appGreenLight to-appGreenMedium'
                          : 'bg-gray-200 dark:bg-gray-700',
                        'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full transition-colors duration-200 ease-in-out'
                      ]"
                    >
                      <span
                        :class="[
                          settings[notification.id][channel.id]
                            ? 'translate-x-6'
                            : 'translate-x-1',
                          'pointer-events-none relative inline-block h-4 w-4 transform rounded-full bg-white shadow-md ring-0 transition duration-200 ease-in-out'
                        ]"
                      />
                    </Switch>
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
