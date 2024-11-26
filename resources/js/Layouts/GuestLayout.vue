<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { List, Dumbbell, HelpCircle, LogIn, UserPlus, Facebook, Instagram, Twitter, Mail, Phone } from 'lucide-vue-next';

const mobileMenuOpen = ref(false);
</script>

<template>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Navigation -->
        <nav class="fixed top-0 z-50 w-full bg-white/95 shadow-sm backdrop-blur-sm dark:bg-gray-800/95">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <Link href="/" class="text-2xl font-bold text-appGreenDark transition-colors dark:text-appGreenLight">
                            SportVenue
                        </Link>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:block">
                        <div class="flex items-center space-x-4">
                            <Link href="/venue-listing" class="flex items-center gap-2 rounded-lg px-3 py-2 text-gray-600 transition-colors hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                                <List class="h-5 w-5" />
                                <span>List Venue</span>
                            </Link>

                            <Link href="/sports-category" class="flex items-center gap-2 rounded-lg px-3 py-2 text-gray-600 transition-colors hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                                <Dumbbell class="h-5 w-5" />
                                <span>Kategori Olahraga</span>
                            </Link>

                            <Link href="/help" class="flex items-center gap-2 rounded-lg px-3 py-2 text-gray-600 transition-colors hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                                <HelpCircle class="h-5 w-5" />
                                <span>Bantuan</span>
                            </Link>

                            <Link href="/login" class="flex items-center gap-2 rounded-lg px-4 py-2 font-medium text-appGreenDark transition-colors hover:bg-gray-100 dark:text-appGreenLight dark:hover:bg-gray-700">
                                <LogIn class="h-5 w-5" />
                                <span>Masuk</span>
                            </Link>

                            <Link href="/register" class="flex items-center gap-2 rounded-lg bg-appGreenLight px-4 py-2 font-medium text-white transition-colors hover:bg-appGreenMedium">
                                <UserPlus class="h-5 w-5" />
                                <span>Daftar</span>
                            </Link>
                        </div>
                    </div>

                    <!-- Mobile Navigation Button -->
                    <div class="md:hidden">
                        <button
                            class="inline-flex items-center justify-center rounded-md p-2 text-gray-600 transition-colors hover:bg-gray-100 hover:text-gray-900 focus:outline-none dark:text-gray-300 dark:hover:bg-gray-700"
                            @click="mobileMenuOpen = !mobileMenuOpen"
                        >
                            <span class="sr-only">Open main menu</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <div
                class="md:hidden"
                :class="{ 'block': mobileMenuOpen, 'hidden': !mobileMenuOpen }"
            >
                <div class="space-y-1 px-4 pb-3 pt-2">
                    <Link href="/venue-listing" class="flex items-center gap-2 rounded-lg px-3 py-2 text-gray-600 transition-colors hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                        <List class="h-5 w-5" />
                        <span>List Venue</span>
                    </Link>

                    <Link href="/categories" class="flex items-center gap-2 rounded-lg px-3 py-2 text-gray-600 transition-colors hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                        <Dumbbell class="h-5 w-5" />
                        <span>Kategori Olahraga</span>
                    </Link>

                    <Link href="/help" class="flex items-center gap-2 rounded-lg px-3 py-2 text-gray-600 transition-colors hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                        <HelpCircle class="h-5 w-5" />
                        <span>Bantuan</span>
                    </Link>

                    <Link href="/login" class="flex items-center gap-2 rounded-lg px-3 py-2 text-gray-600 transition-colors hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                        <LogIn class="h-5 w-5" />
                        <span>Masuk</span>
                    </Link>

                    <Link href="/register" class="flex items-center gap-2 rounded-lg bg-appGreenLight px-3 py-2 text-white transition-colors hover:bg-appGreenMedium">
                        <UserPlus class="h-5 w-5" />
                        <span>Daftar</span>
                    </Link>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="pt-16">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="w-full bg-white border-t border-gray-100 dark:border-gray-800 dark:bg-gray-800">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-8 md:grid-cols-4">
                    <!-- Brand Section -->
                    <div class="space-y-4">
                        <Link href="/" class="text-2xl font-bold text-appGreenDark dark:text-appGreenLight">
                            SportVenue
                        </Link>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Platform booking lapangan olahraga terpercaya dengan ribuan venue di seluruh Indonesia
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-500 transition-colors hover:text-appGreenLight dark:text-gray-400 dark:hover:text-appGreenLight">
                                <Facebook class="h-5 w-5" />
                            </a>
                            <a href="#" class="text-gray-500 transition-colors hover:text-appGreenLight dark:text-gray-400 dark:hover:text-appGreenLight">
                                <Instagram class="h-5 w-5" />
                            </a>
                            <a href="#" class="text-gray-500 transition-colors hover:text-appGreenLight dark:text-gray-400 dark:hover:text-appGreenLight">
                                <Twitter class="h-5 w-5" />
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="mb-4 text-sm font-semibold uppercase text-gray-900 dark:text-white">Quick Links</h3>
                        <ul class="space-y-2">
                            <li>
                                <Link href="/about" class="text-gray-600 transition-colors hover:text-appGreenLight dark:text-gray-400">Tentang Kami</Link>
                            </li>
                            <li>
                                <Link href="/venue-listing" class="text-gray-600 transition-colors hover:text-appGreenLight dark:text-gray-400">List Venue</Link>
                            </li>
                            <li>
                                <Link href="/categories" class="text-gray-600 transition-colors hover:text-appGreenLight dark:text-gray-400">Kategori Olahraga</Link>
                            </li>
                            <li>
                                <Link href="/help" class="text-gray-600 transition-colors hover:text-appGreenLight dark:text-gray-400">Bantuan</Link>
                            </li>
                        </ul>
                    </div>

                    <!-- Support -->
                    <div>
                        <h3 class="mb-4 text-sm font-semibold uppercase text-gray-900 dark:text-white">Support</h3>
                        <ul class="space-y-2">
                            <li>
                                <Link href="/faq" class="text-gray-600 transition-colors hover:text-appGreenLight dark:text-gray-400">FAQ</Link>
                            </li>
                            <li>
                                <Link href="/privacy-policy" class="text-gray-600 transition-colors hover:text-appGreenLight dark:text-gray-400">Kebijakan Privasi</Link>
                            </li>
                            <li>
                                <Link href="/terms" class="text-gray-600 transition-colors hover:text-appGreenLight dark:text-gray-400">Syarat & Ketentuan</Link>
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
