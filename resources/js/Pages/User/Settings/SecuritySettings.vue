<script setup>
import { ref } from 'vue';
import {
  KeyRound, Shield, Smartphone, History,
  LogOut, AlertTriangle, CheckCircle2,
  Fingerprint, Lock, Eye, EyeOff, Globe
} from 'lucide-vue-next';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import FeedbackToast from '@/Components/Settings/FeedbackToast.vue';

// Form states
const passwordForm = ref({
  current_password: '',
  new_password: '',
  confirm_password: ''
});

const showPassword = ref({
  current: false,
  new: false,
  confirm: false
});

const is2FAEnabled = ref(false);
const showQRCode = ref(false);
const isLoading = ref(false);

// Dummy login history
const loginHistory = ref([
  {
    id: 1,
    device: 'Chrome on Windows',
    location: 'Jakarta, Indonesia',
    ip: '182.xxx.xxx.xxx',
    timestamp: '2024-03-15 14:30',
    status: 'success'
  },
  {
    id: 2,
    device: 'Safari on iPhone',
    location: 'Bandung, Indonesia',
    ip: '114.xxx.xxx.xxx',
    timestamp: '2024-03-14 09:15',
    status: 'success'
  },
  {
    id: 3,
    device: 'Unknown Device',
    location: 'Singapore',
    ip: '128.xxx.xxx.xxx',
    timestamp: '2024-03-13 22:45',
    status: 'failed'
  }
]);

// Feedback states
const showFeedback = ref(false);
const feedbackMessage = ref('');
const feedbackType = ref('success');

// Methods
const updatePassword = () => {
  isLoading.value = true;
  // Simulate API call
  setTimeout(() => {
    isLoading.value = false;
    showFeedback.value = true;
    feedbackMessage.value = 'Password berhasil diperbarui';
    feedbackType.value = 'success';
    passwordForm.value = {
      current_password: '',
      new_password: '',
      confirm_password: ''
    };
  }, 1000);
};

const toggle2FA = () => {
  is2FAEnabled.value = !is2FAEnabled.value;
  showFeedback.value = true;
  feedbackMessage.value = is2FAEnabled.value
    ? '2FA berhasil diaktifkan'
    : '2FA berhasil dinonaktifkan';
};

const terminateOtherSessions = () => {
  isLoading.value = true;
  // Simulate API call
  setTimeout(() => {
    isLoading.value = false;
    showFeedback.value = true;
    feedbackMessage.value = 'Semua sesi lain berhasil diakhiri';
  }, 1000);
};
</script>

<template>
  <AuthenticatedLayout>
    <div class="space-y-8 p-8 max-w-6xl mx-auto">
      <!-- Header -->
      <div class="text-center sm:text-left">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">
          Pengaturan Keamanan
        </h2>
        <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">
          Kelola keamanan akun dan perangkat Anda
        </p>
      </div>

      <!-- Password Section -->
      <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-lg transition-all duration-300 hover:shadow-xl dark:border-gray-700 dark:bg-gray-800">
        <div class="flex items-center gap-6">
          <div class="rounded-xl bg-blue-100/80 p-4 backdrop-blur-sm dark:bg-blue-900/30">
            <Lock class="h-7 w-7 text-blue-600 dark:text-blue-400" />
          </div>
          <div>
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
              Ubah Password
            </h3>
            <p class="mt-1.5 text-base text-gray-600 dark:text-gray-400">
              Pastikan menggunakan password yang kuat dan unik
            </p>
          </div>
        </div>

        <form @submit.prevent="updatePassword" class="mt-8 space-y-6">
          <!-- Current Password -->
          <div>
            <label class="text-base font-medium text-gray-700 dark:text-gray-300">
              Password Saat Ini
            </label>
            <div class="relative mt-2">
              <input
                v-model="passwordForm.current_password"
                :type="showPassword.current ? 'text' : 'password'"
                class="w-full rounded-xl border-2 border-gray-300 px-5 py-3 pr-12 transition-all duration-200 focus:border-appGreenLight focus:ring-appGreenLight dark:border-gray-600 dark:bg-gray-700"
                required
              />
              <button
                type="button"
                @click="showPassword.current = !showPassword.current"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
              >
                <component
                  :is="showPassword.current ? EyeOff : Eye"
                  class="h-5 w-5"
                />
              </button>
            </div>
          </div>

          <!-- New Password -->
          <div>
            <label class="text-base font-medium text-gray-700 dark:text-gray-300">
              Password Baru
            </label>
            <div class="relative mt-2">
              <input
                v-model="passwordForm.new_password"
                :type="showPassword.new ? 'text' : 'password'"
                class="w-full rounded-xl border-2 border-gray-300 px-5 py-3 pr-12 transition-all duration-200 focus:border-appGreenLight focus:ring-appGreenLight dark:border-gray-600 dark:bg-gray-700"
                required
              />
              <button
                type="button"
                @click="showPassword.new = !showPassword.new"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
              >
                <component
                  :is="showPassword.new ? EyeOff : Eye"
                  class="h-5 w-5"
                />
              </button>
            </div>
          </div>

          <!-- Confirm Password -->
          <div>
            <label class="text-base font-medium text-gray-700 dark:text-gray-300">
              Konfirmasi Password
            </label>
            <div class="relative mt-2">
              <input
                v-model="passwordForm.confirm_password"
                :type="showPassword.confirm ? 'text' : 'password'"
                class="w-full rounded-xl border-2 border-gray-300 px-5 py-3 pr-12 transition-all duration-200 focus:border-appGreenLight focus:ring-appGreenLight dark:border-gray-600 dark:bg-gray-700"
                required
              />
              <button
                type="button"
                @click="showPassword.confirm = !showPassword.confirm"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
              >
                <component
                  :is="showPassword.confirm ? EyeOff : Eye"
                  class="h-5 w-5"
                />
              </button>
            </div>
          </div>

          <button
            type="submit"
            :disabled="isLoading"
            class="mt-6 inline-flex w-full items-center justify-center rounded-xl bg-appGreenMedium px-6 py-3.5 text-base font-semibold text-white shadow-lg transition-all duration-200 hover:bg-appGreenDark hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-appGreenLight focus:ring-offset-2 disabled:opacity-50 dark:focus:ring-offset-gray-900"
          >
            <span v-if="isLoading">Memperbarui...</span>
            <span v-else>Perbarui Password</span>
          </button>
        </form>
      </div>

      <!-- Two-Factor Authentication -->
      <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-lg transition-all duration-300 hover:shadow-xl dark:border-gray-700 dark:bg-gray-800">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-6">
            <div class="rounded-xl bg-purple-100/80 p-4 backdrop-blur-sm dark:bg-purple-900/30">
              <Fingerprint class="h-7 w-7 text-purple-600 dark:text-purple-400" />
            </div>
            <div>
              <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                Autentikasi Dua Faktor
              </h3>
              <p class="mt-1.5 text-base text-gray-600 dark:text-gray-400">
                Tambahan keamanan untuk akun Anda
              </p>
            </div>
          </div>
          <button
            @click="toggle2FA"
            class="relative inline-flex h-7 w-14 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-all duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-appGreenLight focus:ring-offset-2"
            :class="is2FAEnabled ? 'bg-appGreenMedium' : 'bg-gray-200'"
          >
            <span
              class="pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow-md ring-0 transition-all duration-200 ease-in-out"
              :class="is2FAEnabled ? 'translate-x-7' : 'translate-x-0'"
            />
          </button>
        </div>

        <div v-if="is2FAEnabled && showQRCode" class="mt-8">
          <div class="rounded-xl border-2 border-gray-200 p-6 dark:border-gray-700">
            <p class="text-base text-gray-600 dark:text-gray-400">
              Scan QR code ini dengan aplikasi autentikator Anda
            </p>
          </div>
        </div>
      </div>

      <!-- Login History -->
      <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-lg transition-all duration-300 hover:shadow-xl dark:border-gray-700 dark:bg-gray-800">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-6">
            <div class="rounded-xl bg-orange-100/80 p-4 backdrop-blur-sm dark:bg-orange-900/30">
              <History class="h-7 w-7 text-orange-600 dark:text-orange-400" />
            </div>
            <div>
              <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                Riwayat Login
              </h3>
              <p class="mt-1.5 text-base text-gray-600 dark:text-gray-400">
                Aktivitas login terakhir pada akun Anda
              </p>
            </div>
          </div>
          <button
            @click="terminateOtherSessions"
            class="rounded-xl bg-red-500 px-6 py-3 text-base font-semibold text-white shadow-lg transition-all duration-200 hover:bg-red-600 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-50 dark:focus:ring-offset-gray-900"
          >
            <span class="flex items-center gap-2">
              <LogOut class="h-5 w-5" />
              Akhiri Sesi Lain
            </span>
          </button>
        </div>

        <div class="mt-8 space-y-4">
          <div
            v-for="login in loginHistory"
            :key="login.id"
            class="flex items-center justify-between rounded-xl border-2 border-gray-100 p-6 transition-all duration-200 hover:border-gray-200 dark:border-gray-700 dark:hover:border-gray-600"
          >
            <div class="flex items-center gap-5">
              <Globe class="h-6 w-6 text-gray-400" />
              <div>
                <div class="text-lg font-medium text-gray-900 dark:text-white">
                  {{ login.device }}
                </div>
                <div class="mt-1.5 flex items-center gap-3 text-base text-gray-600 dark:text-gray-400">
                  <span>{{ login.location }}</span>
                  <span class="text-gray-300 dark:text-gray-600">•</span>
                  <span>{{ login.ip }}</span>
                </div>
              </div>
            </div>
            <div class="text-right">
              <div class="text-base text-gray-600 dark:text-gray-400">
                {{ login.timestamp }}
              </div>
              <div
                :class="[
                  'mt-1.5 text-base font-medium',
                  login.status === 'success'
                    ? 'text-green-600 dark:text-green-400'
                    : 'text-red-600 dark:text-red-400'
                ]"
              >
                {{ login.status === 'success' ? 'Berhasil' : 'Gagal' }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Feedback Toast -->
    <FeedbackToast
      :show="showFeedback"
      :message="feedbackMessage"
      :type="feedbackType"
      @close="showFeedback = false"
    />
  </AuthenticatedLayout>
</template>
