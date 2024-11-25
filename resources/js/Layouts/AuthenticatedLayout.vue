<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Menu, X, ChevronDown, Home, Calendar, MapPin, Settings, LogOut, List, Dumbbell, HelpCircle, ShoppingCart, BookOpen } from 'lucide-vue-next';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Navigation -->
        <nav class="border-b border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-800">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between">
                    <!-- Logo & Main Nav -->
                    <div class="flex">
                        <div class="flex shrink-0 items-center">
                            <Link href="/" class="text-2xl font-bold text-appGreenDark transition-colors dark:text-appGreenLight">
                                SportVenue
                            </Link>
                        </div>

                        <div class="hidden space-x-8 sm:ml-10 sm:flex">
                            <Link href="/" class="inline-flex items-center gap-2 px-1 pt-1 text-sm font-medium text-appGreenDark transition-colors hover:text-appGreenMedium dark:text-appGreenLight">
                                <Home class="h-4 w-4" />
                                Beranda
                            </Link>
                            <Link href="/venue-listing" class="inline-flex items-center gap-2 px-1 pt-1 text-sm font-medium text-gray-500 transition-colors hover:text-appGreenMedium dark:text-gray-300 dark:hover:text-appGreenLight">
                                <List class="h-4 w-4" />
                                List Venue
                            </Link>
                            <Link href="/categories" class="inline-flex items-center gap-2 px-1 pt-1 text-sm font-medium text-gray-500 transition-colors hover:text-appGreenMedium dark:text-gray-300 dark:hover:text-appGreenLight">
                                <Dumbbell class="h-4 w-4" />
                                Kategori Olahraga
                            </Link>
                            <Link href="/bookings" class="inline-flex items-center gap-2 px-1 pt-1 text-sm font-medium text-gray-500 transition-colors hover:text-appGreenMedium dark:text-gray-300 dark:hover:text-appGreenLight">
                                <Calendar class="h-4 w-4" />
                                Booking Saya
                            </Link>
                            <Link href="/help" class="inline-flex items-center gap-2 px-1 pt-1 text-sm font-medium text-gray-500 transition-colors hover:text-appGreenMedium dark:text-gray-300 dark:hover:text-appGreenLight">
                                <HelpCircle class="h-4 w-4" />
                                Bantuan
                            </Link>
                        </div>
                    </div>

                    <!-- User Menu -->
                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        <!-- Cart -->
                        <Link href="/cart" class="relative mr-4">
                            <ShoppingCart class="h-6 w-6 text-gray-500 hover:text-appGreenMedium dark:text-gray-300 dark:hover:text-appGreenLight" />
                            <span class="absolute -right-2 -top-2 flex h-5 w-5 items-center justify-center rounded-full bg-appGreenLight text-xs font-bold text-white">2</span>
                        </Link>

                        <!-- My Bookings -->
                        <Link href="/my-bookings" class="relative mr-4">
                            <BookOpen class="h-6 w-6 text-gray-500 hover:text-appGreenMedium dark:text-gray-300 dark:hover:text-appGreenLight" />
                            <span class="absolute -right-2 -top-2 flex h-5 w-5 items-center justify-center rounded-full bg-appGreenLight text-xs font-bold text-white">3</span>
                        </Link>

                        <div class="relative ml-3">
                            <div>
                                <button class="flex items-center gap-2 rounded-full bg-white p-1 text-sm focus:outline-none focus:ring-2 focus:ring-appGreenLight dark:bg-gray-800">
                                    <img class="h-8 w-8 rounded-full object-cover" src="/images/avatar-placeholder.jpg" alt="User avatar">
                                    <span class="text-gray-700 dark:text-gray-300">John Doe</span>
                                    <ChevronDown class="h-4 w-4 text-gray-400" />
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button
                            @click="showingNavigationDropdown = !showingNavigationDropdown"
                            class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none dark:text-gray-300 dark:hover:bg-gray-700"
                        >
                            <Menu v-if="!showingNavigationDropdown" class="h-6 w-6" />
                            <X v-else class="h-6 w-6" />
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div :class="{'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown}" class="sm:hidden">
                <div class="space-y-1 pb-3 pt-2">
                    <Link href="/" class="block border-l-4 border-appGreenLight bg-appGreenLight/10 py-2 pl-3 pr-4 text-base font-medium text-appGreenDark dark:text-appGreenLight">
                        Beranda
                    </Link>
                    <Link href="/venue-listing" class="block border-l-4 border-transparent py-2 pl-3 pr-4 text-base font-medium text-gray-600 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        List Venue
                    </Link>
                    <Link href="/categories" class="block border-l-4 border-transparent py-2 pl-3 pr-4 text-base font-medium text-gray-600 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        Kategori Olahraga
                    </Link>
                    <Link href="/bookings" class="block border-l-4 border-transparent py-2 pl-3 pr-4 text-base font-medium text-gray-600 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        Booking Saya
                    </Link>
                    <Link href="/help" class="block border-l-4 border-transparent py-2 pl-3 pr-4 text-base font-medium text-gray-600 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        Bantuan
                    </Link>
                    <Link href="/cart" class="flex items-center justify-between border-l-4 border-transparent py-2 pl-3 pr-4 text-base font-medium text-gray-600 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        <div class="flex items-center gap-2">
                            <ShoppingCart class="h-5 w-5" />
                            Keranjang
                        </div>
                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-appGreenLight text-xs font-bold text-white">2</span>
                    </Link>
                    <Link href="/my-bookings" class="flex items-center justify-between border-l-4 border-transparent py-2 pl-3 pr-4 text-base font-medium text-gray-600 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        <div class="flex items-center gap-2">
                            <BookOpen class="h-5 w-5" />
                            Booking Saya
                        </div>
                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-appGreenLight text-xs font-bold text-white">3</span>
                    </Link>
                </div>

                <div class="border-t border-gray-200 pb-3 pt-4 dark:border-gray-700">
                    <div class="flex items-center px-4">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full" src="/images/avatar-placeholder.jpg" alt="User avatar">
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium text-gray-800 dark:text-gray-200">John Doe</div>
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">john@example.com</div>
                        </div>
                    </div>
                    <div class="mt-3 space-y-1">
                        <Link href="/profile" class="flex items-center gap-2 px-4 py-2 text-base font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                            <Settings class="h-5 w-5" />
                            Pengaturan
                        </Link>
                        <Link href="/logout" method="post" as="button" class="flex w-full items-center gap-2 px-4 py-2 text-base font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                            <LogOut class="h-5 w-5" />
                            Keluar
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            <slot />
        </main>

        <!-- Footer -->
        <footer class="mt-auto border-t border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-800">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-8 md:grid-cols-4">
                    <!-- Brand -->
                    <div class="space-y-4">
                        <Link href="/" class="text-2xl font-bold text-appGreenDark transition-colors dark:text-appGreenLight">
                            SportVenue
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
