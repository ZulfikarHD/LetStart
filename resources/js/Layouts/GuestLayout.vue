<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Menu, X, List, Dumbbell, HelpCircle, LogIn, UserPlus, Facebook, Instagram, Twitter, Mail, Phone } from 'lucide-vue-next';

const showingNavigationDropdown = ref(false);

const navigationItems = [
  { name: 'List Venue', href: '/venue-listing', icon: List },
  { name: 'Kategori Olahraga', href: '/sport-categories', icon: Dumbbell },
  { name: 'Bantuan', href: '/help', icon: HelpCircle },
];

const footerLinks = {
  quickLinks: [
    { name: 'Tentang Kami', href: '/about' },
    { name: 'List Venue', href: '/venue-listing' },
    { name: 'Kategori Olahraga', href: '/sport-categories' },
    { name: 'Bantuan', href: '/help' },
  ],
  support: [
    { name: 'FAQ', href: '/faq' },
    { name: 'Kebijakan Privasi', href: '/privacy-policy' },
    { name: 'Syarat & Ketentuan', href: '/terms' },
  ],
  social: [
    { name: 'Facebook', href: '#', icon: Facebook },
    { name: 'Instagram', href: '#', icon: Instagram },
    { name: 'Twitter', href: '#', icon: Twitter },
  ]
};
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
              SportVenue
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

          <!-- Auth Buttons -->
          <div class="hidden lg:flex items-center gap-4">
            <Link href="/login"
                  class="flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-gray-600 transition-colors hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
              <LogIn class="h-4 w-4" />
              Masuk
            </Link>
            <Link href="/register"
                  class="flex items-center gap-2 rounded-lg bg-appGreenLight px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-appGreenMedium">
              <UserPlus class="h-4 w-4" />
              Daftar
            </Link>
          </div>

          <!-- Mobile menu button -->
          <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                  class="lg:hidden rounded-lg p-2 text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700">
            <Menu v-if="!showingNavigationDropdown" class="h-6 w-6" />
            <X v-else class="h-6 w-6" />
          </button>
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

            <!-- Mobile Auth Buttons -->
            <div class="mt-4 space-y-2">
              <Link href="/login"
                    class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-base font-medium text-gray-600 transition-colors hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                <LogIn class="h-5 w-5" />
                Masuk
              </Link>
              <Link href="/register"
                    class="flex w-full items-center gap-2 rounded-lg bg-appGreenLight px-3 py-2 text-base font-medium text-white transition-colors hover:bg-appGreenMedium">
                <UserPlus class="h-5 w-5" />
                Daftar
              </Link>
            </div>
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
          <!-- Brand Section -->
          <div class="space-y-4">
            <Link href="/"
                  class="text-2xl font-bold text-appGreenDark dark:text-appGreenLight">
              SportVenue
            </Link>
            <p class="text-sm text-gray-600 dark:text-gray-400">
              Platform booking lapangan olahraga terpercaya dengan ribuan venue di seluruh Indonesia
            </p>
            <div class="flex space-x-4">
              <a v-for="item in footerLinks.social"
                 :key="item.name"
                 :href="item.href"
                 class="text-gray-500 transition-colors hover:text-appGreenLight dark:text-gray-400 dark:hover:text-appGreenLight">
                <component :is="item.icon" class="h-5 w-5" />
              </a>
            </div>
          </div>

          <!-- Quick Links -->
          <div>
            <h3 class="mb-4 text-sm font-semibold uppercase text-gray-900 dark:text-white">Quick Links</h3>
            <ul class="space-y-2">
              <li v-for="link in footerLinks.quickLinks" :key="link.href">
                <Link :href="link.href"
                      class="text-gray-600 transition-colors hover:text-appGreenLight dark:text-gray-400">
                  {{ link.name }}
                </Link>
              </li>
            </ul>
          </div>

          <!-- Support -->
          <div>
            <h3 class="mb-4 text-sm font-semibold uppercase text-gray-900 dark:text-white">Support</h3>
            <ul class="space-y-2">
              <li v-for="link in footerLinks.support" :key="link.href">
                <Link :href="link.href"
                      class="text-gray-600 transition-colors hover:text-appGreenLight dark:text-gray-400">
                  {{ link.name }}
                </Link>
              </li>
            </ul>
          </div>

          <!-- Contact -->
          <div>
            <h3 class="mb-4 text-sm font-semibold uppercase text-gray-900 dark:text-white">Kontak</h3>
            <ul class="space-y-2">
              <li class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                <Mail class="h-5 w-5" />
                <span>support@sportvenue.id</span>
              </li>
              <li class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                <Phone class="h-5 w-5" />
                <span>+62 821-2345-6789</span>
              </li>
            </ul>
          </div>
        </div>

        <div class="mt-8 border-t border-gray-200 pt-8 dark:border-gray-700">
          <p class="text-center text-sm text-gray-600 dark:text-gray-400">
            © {{ new Date().getFullYear() }} SportVenue. All rights reserved.
          </p>
        </div>
      </div>
    </footer>
  </div>
</template>
