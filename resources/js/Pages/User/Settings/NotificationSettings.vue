<script setup>
import { ref } from 'vue';
import { Switch } from '@headlessui/vue';
import { Bell, Mail, Phone, Calendar } from 'lucide-vue-next';

const notifications = ref({
  booking: {
    email: true,
    push: true,
    sms: false
  },
  promo: {
    email: true,
    push: false,
    sms: false
  },
  reminder: {
    email: true,
    push: true,
    sms: true
  }
});

const notificationTypes = [
  {
    id: 'booking',
    name: 'Booking',
    description: 'Notifikasi terkait booking venue',
    icon: Calendar
  },
  {
    id: 'promo',
    name: 'Promo & Penawaran',
    description: 'Informasi promo dan penawaran khusus',
    icon: Bell
  },
  {
    id: 'reminder',
    name: 'Pengingat',
    description: 'Pengingat jadwal booking Anda',
    icon: Clock
  }
];

const channels = [
  { id: 'email', name: 'Email', icon: Mail },
  { id: 'push', name: 'Push Notification', icon: Bell },
  { id: 'sms', name: 'SMS', icon: Phone }
];
</script>

<template>
  <div class="space-y-6">
    <div v-for="type in notificationTypes"
         :key="type.id"
         class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
      <div class="flex items-start gap-4">
        <component
          :is="type.icon"
          class="h-5 w-5 text-gray-400"
        />
        <div class="flex-1">
          <h3 class="text-base font-medium text-gray-900 dark:text-white">
            {{ type.name }}
          </h3>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            {{ type.description }}
          </p>

          <!-- Channel Toggles -->
          <div class="mt-4 space-y-3">
            <div v-for="channel in channels"
                 :key="channel.id"
                 class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <component
                  :is="channel.icon"
                  class="h-4 w-4 text-gray-400"
                />
                <span class="text-sm text-gray-600 dark:text-gray-300">
                  {{ channel.name }}
                </span>
              </div>
              <Switch
                v-model="notifications[type.id][channel.id]"
                :class="[
                  notifications[type.id][channel.id] ? 'bg-appGreenMedium' : 'bg-gray-200 dark:bg-gray-700',
                  'relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full transition-colors duration-200 ease-in-out'
                ]"
              >
                <span
                  :class="[
                    notifications[type.id][channel.id] ? 'translate-x-4' : 'translate-x-0',
                    'pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out'
                  ]"
                />
              </Switch>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
