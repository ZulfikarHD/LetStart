<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { Menu, X, ChevronDown, Home, Calendar, Settings, LogOut, List, Dumbbell, HelpCircle, ShoppingCart, Bell, User, CreditCard, Shield } from 'lucide-vue-next';

const showingNavigationDropdown = ref(false);
const showUserMenu = ref(false);
const page = usePage();

const navigationItems = [
  { name: 'Beranda', href: '/', icon: Home },
  { name: 'List Venue', href: '/venue-listing', icon: List },
  { name: 'Kategori', href: '/sport-category', icon: Dumbbell },
  { name: 'Bantuan', href: '/help', icon: HelpCircle },
];

const userMenuItems = [
  // Quick Actions Group
  {
    type: 'quick-actions',
    items: [
      {
        name: 'Profil Saya',
        href: route('profile.show'),
        icon: User,
        description: 'Lihat dan edit profil Anda'
      },
      {
        name: 'Booking Saya',
        href: route('bookings.index'),
        icon: Calendar,
        description: 'Lihat booking aktif dan riwayat'
      },
    ]
  },
  // Settings Group
  {
    type: 'settings',
    items: [
      {
        name: 'Notifikasi',
        href: route('settings.index', { tab: 'notifications' }),
        icon: Bell,
        badge: '2'
      },
      {
        name: 'Pembayaran',
        href: route('settings.index', { tab: 'payments' }),
        icon: CreditCard
      },
      {
        name: 'Keamanan',
        href: route('settings.index', { tab: 'security' }),
        icon: Shield
      },
    ]
  },
  // Logout Group
  {
    type: 'actions',
    items: [
      {
        name: 'Keluar',
        href: '/logout',
        icon: LogOut,
        method: 'post',
        as: 'button',
        class: 'w-full text-left text-red-600 dark:text-red-400'
      }
    ]
  }
];

// Click outside handler for user menu
const userMenuRef = ref(null);
const handleClickOutside = (event) => {
  if (userMenuRef.value && !userMenuRef.value.contains(event.target)) {
    showUserMenu.value = false;
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Navigation -->
    <nav class="sticky top-0 z-50 backdrop-blur-lg border-b border-gray-100 bg-white/80 dark:border-gray-800 dark:bg-gray-800/80">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
          <!-- Logo & Main Nav -->
          <div class="flex items-center gap-8">
            <Link href="/"
                  class="text-2xl font-bold text-appGreenDark transition-colors hover:text-appGreenMedium dark:text-appGreenLight">
              MainNow
            </Link>

            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center space-x-2">
              <Link v-for="item in navigationItems"
                    :key="item.href"
                    :href="item.href"
                    :class="[
                      'group flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition-colors',
                      route().current(item.href)
                        ? 'bg-appGreenLight/10 text-appGreenDark dark:text-appGreenLight'
                        : 'text-gray-600 hover:bg-appGreenLight/10 hover:text-appGreenDark dark:text-gray-300 dark:hover:text-appGreenLight'
                    ]">
                <component :is="item.icon" class="h-4 w-4" />
                {{ item.name }}
              </Link>
            </div>
          </div>

          <!-- Right Section -->
          <div class="flex items-center gap-4">
            <!-- Notifications -->
            <button class="relative p-2 hover:bg-gray-100 rounded-full dark:hover:bg-gray-700 transition-colors">
              <Bell class="h-5 w-5 text-gray-500 dark:text-gray-400" />
              <span class="absolute -right-0 -top-0 flex h-4 w-4 items-center justify-center rounded-full bg-appGreenLight text-xs font-bold text-white">
                2
              </span>
            </button>

            <!-- Cart -->
            <Link href="/cart"
                  class="relative p-2 hover:bg-gray-100 rounded-full dark:hover:bg-gray-700 transition-colors">
              <ShoppingCart class="h-5 w-5 text-gray-500 dark:text-gray-400" />
              <span class="absolute -right-0 -top-0 flex h-4 w-4 items-center justify-center rounded-full bg-appGreenLight text-xs font-bold text-white">
                2
              </span>
            </Link>

            <!-- User Menu -->
            <div class="relative" ref="userMenuRef">
              <button @click="showUserMenu = !showUserMenu"
                      class="flex items-center gap-3 rounded-full bg-white px-3 py-1.5 shadow-sm transition-all hover:shadow-md dark:bg-gray-700">
                <img class="h-8 w-8 rounded-full object-cover"
                     :src="page.props.auth.user.avatar || '/images/avatar-placeholder.jpg'"
                     :alt="page.props.auth.user.name" />
                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">
                  {{ page.props.auth.user.name }}
                </span>
                <ChevronDown class="h-4 w-4 text-gray-400"
                            :class="{ 'rotate-180': showUserMenu }" />
              </button>

              <!-- Dropdown Menu -->
              <transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
              >
                <div v-show="showUserMenu"
                     class="absolute right-0 mt-2 w-80 rounded-lg bg-white py-2 shadow-lg ring-1 ring-black ring-opacity-5 dark:bg-gray-700">
                  <!-- User Info Header -->
                  <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-600">
                    <div class="flex items-center space-x-3">
                      <img class="h-10 w-10 rounded-full object-cover"
                           :src="page.props.auth.user.avatar || '/images/avatar-placeholder.jpg'"
                           :alt="page.props.auth.user.name" />
                      <div>
                        <div class="font-medium text-gray-900 dark:text-white">{{ page.props.auth.user.name }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ page.props.auth.user.email }}</div>
                      </div>
                    </div>
                  </div>

                  <!-- Menu Groups -->
                  <div v-for="(group, index) in userMenuItems" :key="index">
                    <!-- Quick Actions -->
                    <div v-if="group.type === 'quick-actions'" class="px-2 py-2">
                      <Link v-for="item in group.items"
                            :key="item.href"
                            :href="item.href"
                            class="flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        <div class="flex-shrink-0">
                          <component :is="item.icon"
                                    class="h-5 w-5 text-gray-500 dark:text-gray-400" />
                        </div>
                        <div class="flex-1">
                          <div class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ item.name }}
                          </div>
                          <div v-if="item.description"
                               class="text-xs text-gray-500 dark:text-gray-400">
                            {{ item.description }}
                          </div>
                        </div>
                      </Link>
                    </div>

                    <!-- Settings Group -->
                    <div v-if="group.type === 'settings'"
                         class="border-t border-gray-100 dark:border-gray-600 px-2 py-2">
                      <div class="px-3 py-1 text-xs font-semibold text-gray-500 dark:text-gray-400">
                        Pengaturan
                      </div>
                      <Link v-for="item in group.items"
                            :key="item.href"
                            :href="item.href"
                            class="flex items-center justify-between px-3 py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        <div class="flex items-center gap-2">
                          <component :is="item.icon"
                                    class="h-4 w-4 text-gray-500 dark:text-gray-400" />
                          <span class="text-sm text-gray-700 dark:text-gray-200">
                            {{ item.name }}
                          </span>
                        </div>
                        <span v-if="item.badge"
                              class="bg-appGreenLight text-white text-xs font-bold px-2 py-0.5 rounded-full">
                          {{ item.badge }}
                        </span>
                      </Link>
                    </div>

                    <!-- Logout -->
                    <div v-if="group.type === 'actions'"
                         class="border-t border-gray-100 dark:border-gray-600 px-2 py-2">
                      <Link v-for="item in group.items"
                            :key="item.href"
                            :href="item.href"
                            :method="item.method"
                            :as="item.as"
                            :class="[
                              'flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors',
                              item.class
                            ]">
                        <component :is="item.icon" class="h-4 w-4" />
                        <span class="text-sm">{{ item.name }}</span>
                      </Link>
                    </div>
                  </div>
                </div>
              </transition>
            </div>

            <!-- Mobile menu button -->
            <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                    class="lg:hidden rounded-lg p-2 text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700">
              <Menu v-if="!showingNavigationDropdown" class="h-6 w-6" />
              <X v-else class="h-6 w-6" />
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile Navigation -->
      <transition
        enter-active-class="transition-all duration-300 ease-in-out"
        enter-from-class="opacity-0 max-h-0"
        enter-to-class="opacity-100 max-h-[400px]"
        leave-active-class="transition-all duration-200 ease-in-out"
        leave-from-class="opacity-100 max-h-[400px]"
        leave-to-class="opacity-0 max-h-0"
      >
        <div v-show="showingNavigationDropdown" class="lg:hidden overflow-hidden">
          <div class="space-y-1 px-4 pb-3 pt-2">
            <Link v-for="item in navigationItems"
                  :key="item.href"
                  :href="item.href"
                  :class="[
                    'flex items-center gap-2 rounded-lg px-3 py-2 text-base font-medium transition-colors',
                    route().current(item.href)
                      ? 'bg-appGreenLight/10 text-appGreenDark dark:text-appGreenLight'
                      : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
                  ]">
              <component :is="item.icon" class="h-5 w-5" />
              {{ item.name }}
            </Link>
          </div>
        </div>
      </transition>
    </nav>

    <!-- Page Content -->
    <main class="min-h-[calc(100vh-4rem)]">
      <slot />
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 dark:bg-gray-800 dark:border-gray-700">
      <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-8 md:grid-cols-4">
          <!-- Brand -->
          <div class="space-y-4">
            <Link href="/" class="text-2xl font-bold text-appGreenDark transition-colors dark:text-appGreenLight">
              MainNow
            </Link>
            <p class="text-sm text-gray-500 dark:text-gray-400">
              Platform booking venue olahraga terpercaya di Indonesia.
            </p>
          </div>

          <!-- Links -->
          <div>
            <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-400">Layanan</h3>
            <div class="mt-4 space-y-2">
              <Link href="/venue-listing" class="block text-sm text-gray-600 hover:text-appGreenMedium dark:text-gray-300 dark:hover:text-appGreenLight">List Venue</Link>
              <Link href="/categories" class="block text-sm text-gray-600 hover:text-appGreenMedium dark:text-gray-300 dark:hover:text-appGreenLight">Kategori Olahraga</Link>
              <Link href="/bookings" class="block text-sm text-gray-600 hover:text-appGreenMedium dark:text-gray-300 dark:hover:text-appGreenLight">Booking</Link>
            </div>
          </div>

          <div>
            <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-400">Perusahaan</h3>
            <div class="mt-4 space-y-2">
              <Link href="/about" class="block text-sm text-gray-600 hover:text-appGreenMedium dark:text-gray-300 dark:hover:text-appGreenLight">Tentang Kami</Link>
              <Link href="/contact" class="block text-sm text-gray-600 hover:text-appGreenMedium dark:text-gray-300 dark:hover:text-appGreenLight">Hubungi Kami</Link>
              <Link href="/help" class="block text-sm text-gray-600 hover:text-appGreenMedium dark:text-gray-300 dark:hover:text-appGreenLight">Bantuan</Link>
            </div>
          </div>

          <div>
            <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-400">Legal</h3>
            <div class="mt-4 space-y-2">
              <Link href="/privacy" class="block text-sm text-gray-600 hover:text-appGreenMedium dark:text-gray-300 dark:hover:text-appGreenLight">Privasi</Link>
              <Link href="/terms" class="block text-sm text-gray-600 hover:text-appGreenMedium dark:text-gray-300 dark:hover:text-appGreenLight">Syarat & Ketentuan</Link>
            </div>
          </div>
        </div>

        <div class="mt-8 border-t border-gray-200 pt-8 dark:border-gray-700">
          <p class="text-center text-sm text-gray-400">
            © {{ new Date().getFullYear() }} Sports Venue. All rights reserved.
          </p>
        </div>
      </div>
    </footer>
  </div>
</template>
