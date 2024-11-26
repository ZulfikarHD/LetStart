<script setup>
import { ref, watch } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { Tab } from '@headlessui/vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ProfileSettings from '@/Components/Settings/ProfileSettings.vue';
import SecuritySettings from '@/Components/Settings/SecuritySettings.vue';
import PaymentSettings from '@/Components/Settings/PaymentSettings.vue';
import NotificationSettings from '@/Components/Settings/NotificationSettings.vue';

const page = usePage();
const currentTab = ref(0);

const tabs = [
  { name: 'Notifikasi', component: NotificationSettings },
  { name: 'Pembayaran', component: PaymentSettings },
  { name: 'Keamanan', component: SecuritySettings }
];

// Sync tab with URL
watch(() => page.props.ziggy.location, (newUrl) => {
  const urlParams = new URLSearchParams(window.location.search);
  const tabParam = urlParams.get('tab');
  const tabIndex = tabs.findIndex(tab => tab.name.toLowerCase() === tabParam);
  if (tabIndex !== -1) {
    currentTab.value = tabIndex;
  }
}, { immediate: true });

// Update URL when tab changes
const handleTabChange = (index) => {
  currentTab.value = index;
  const tabName = tabs[index].name.toLowerCase();
  router.get(`/settings?tab=${tabName}`, {}, { preserveState: true, preserveScroll: true });
};
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Pengaturan" />

    <div class="py-8">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Pengaturan
          </h1>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Kelola preferensi dan pengaturan akun Anda
          </p>
        </div>

        <!-- Settings Tabs -->
        <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
          <Tab.Group :selectedIndex="currentTab" @change="handleTabChange">
            <Tab.List class="border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-900">
              <div class="flex space-x-4 px-4">
                <Tab v-for="tab in tabs"
                     :key="tab.name"
                     v-slot="{ selected }"
                     as="template">
                  <button
                    :class="[
                      'border-b-2 py-4 text-sm font-medium transition-colors focus:outline-none',
                      selected
                        ? 'border-appGreenMedium text-appGreenDark dark:text-appGreenLight'
                        : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'
                    ]"
                  >
                    {{ tab.name }}
                  </button>
                </Tab>
              </div>
            </Tab.List>

            <Tab.Panels>
              <Tab.Panel v-for="(tab, index) in tabs"
                        :key="index"
                        class="p-6">
                <component :is="tab.component" />
              </Tab.Panel>
            </Tab.Panels>
          </Tab.Group>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
