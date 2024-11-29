<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Menu, X, List, Dumbbell, HelpCircle, LogIn, UserPlus, Facebook, Instagram, Twitter, Mail, Phone } from 'lucide-vue-next';
import Footer from '@/Layouts/Footer/Index.vue';
import Logo from '@/Components/Logo.vue';
const showingNavigationDropdown = ref(false);

const navigationItems = [
  { name: 'List Venue', href: '/venue-listing', icon: List },
  { name: 'Kategori Olahraga', href: '/sport-category', icon: Dumbbell },
  { name: 'Bantuan', href: '/help', icon: HelpCircle },
];
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
                  class="flex items-center">
              <Logo />
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

    <!-- Replace footer with new component -->
    <Footer />
  </div>
</template>
