<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { MapPin, Star, Trophy, Timer, Shield, Search, HelpCircle, LogIn, UserPlus, List, Dumbbell, X } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';

const page = usePage();
const isAuthenticated = computed(() => page.props.auth.user !== null);

// Add reactive references
const searchQuery = ref('');
const showSuggestions = ref(false);

// Mock data - replace with actual API data
const featuredVenues = [
    {
        id: 1,
        name: 'Lapangan Futsal Senayan',
        location: 'Senayan, Jakarta Selatan',
        rating: 4.8,
        sport: 'Futsal',
        image: '/images/venues/futsal-1.jpg',
        price: 'Rp 200.000/jam'
    },
    {
        id: 2,
        name: 'GOR Badminton Kelapa Gading',
        location: 'Kelapa Gading, Jakarta Utara',
        rating: 4.7,
        sport: 'Badminton',
        image: '/images/venues/badminton-1.jpg',
        price: 'Rp 150.000/jam'
    },
    {
        id: 3,
        name: 'Lapangan Basket Indoor BSD',
        location: 'BSD City, Tangerang',
        rating: 4.9,
        sport: 'Basket',
        image: '/images/venues/basket-1.jpg',
        price: 'Rp 250.000/jam'
    }
];

const promotions = [
    {
        id: 1,
        title: 'Early Bird Discount',
        description: 'Hemat 20% untuk booking sebelum jam 10 pagi',
        validUntil: '31 Des 2023',
        discount: '20%'
    },
    {
        id: 2,
        title: 'Weekend Package',
        description: 'Booking di akhir pekan dapat cashback 15%',
        validUntil: '31 Des 2023',
        discount: '15%'
    },
    {
        id: 3,
        title: 'Group Booking',
        description: 'Diskon 25% untuk booking tim (min. 10 orang)',
        validUntil: '31 Des 2023',
        discount: '25%'
    }
];

const popularSports = ['Futsal', 'Badminton', 'Basket'];

const handleSearch = () => {
    // Implement search functionality
    if (searchQuery.value.length > 2) {
        showSuggestions.value = true;
        // Add your search logic here
    } else {
        showSuggestions.value = false;
    }
};

const clearSearch = () => {
    searchQuery.value = '';
    showSuggestions.value = false;
};
</script>

<template>
    <Head title="Selamat Datang di SportVenue" />

    <AuthenticatedLayout v-if="isAuthenticated">
        <main class="min-h-screen bg-gray-50 dark:bg-gray-900">
            <!-- Hero Section -->
            <section class="relative bg-appGreenDark pt-20 pb-32 z-10">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="absolute inset-0 bg-[url('/images/pattern-2.svg')] bg-center opacity-5"></div>
                </div>
                <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="mx-auto max-w-3xl text-center">
                        <h1 class="mb-6 text-4xl font-extrabold text-white md:text-6xl">
                            Temukan dan Booking
                            <span class="text-appGreenLight">Lapangan Olahraga</span>
                            Favoritmu
                        </h1>
                        <p class="mb-8 text-lg text-gray-300">
                            Platform booking lapangan olahraga terpercaya dengan ribuan venue di seluruh Indonesia
                        </p>

                        <!-- Search Bar -->
                        <div class="mx-auto max-w-2xl">
                            <div class="flex overflow-hidden rounded-full bg-white shadow-lg">
                                <div class="flex flex-1 items-center px-4">
                                    <Search class="h-5 w-5 text-gray-400" />
                                    <input type="text"
                                        placeholder="Cari lapangan olahraga..."
                                        class="w-full border-0 px-4 py-4 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-0" />
                                </div>
                                <button class="bg-appGreenLight px-8 font-semibold text-white transition-colors hover:bg-appGreenMedium">
                                    Cari
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Stats Section -->
            <section class="relative z-20">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="relative -mt-24 grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div class="rounded-2xl bg-white/95 backdrop-blur-sm p-8 shadow-lg transition-all duration-300 hover:-translate-y-1 dark:bg-gray-800/95 transform-gpu">
                            <Trophy class="mb-4 h-10 w-10 text-appGreenLight" />
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">500+</h3>
                            <p class="text-gray-600 dark:text-gray-300">Venue Olahraga</p>
                        </div>
                        <div class="rounded-2xl bg-white/95 backdrop-blur-sm p-8 shadow-lg transition-all duration-300 hover:-translate-y-1 dark:bg-gray-800/95 transform-gpu">
                            <Timer class="mb-4 h-10 w-10 text-appGreenLight" />
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">24/7</h3>
                            <p class="text-gray-600 dark:text-gray-300">Layanan Booking</p>
                        </div>
                        <div class="rounded-2xl bg-white/95 backdrop-blur-sm p-8 shadow-lg transition-all duration-300 hover:-translate-y-1 dark:bg-gray-800/95 transform-gpu">
                            <Shield class="mb-4 h-10 w-10 text-appGreenLight" />
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">100%</h3>
                            <p class="text-gray-600 dark:text-gray-300">Pembayaran Aman</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Featured Venues -->
            <section class="relative z-10 bg-gray-50 dark:bg-gray-900 pt-16">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="mb-12">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Venue Populer</h2>
                        <p class="mt-2 text-gray-600 dark:text-gray-300">Pilihan terbaik untuk olahraga favoritmu</p>
                    </div>
                    <div class="grid gap-8 md:grid-cols-3">
                        <div v-for="venue in featuredVenues" :key="venue.id"
                            class="group relative overflow-hidden rounded-3xl bg-white shadow-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-xl dark:bg-gray-800 transform-gpu">
                            <div class="aspect-[4/3] overflow-hidden">
                                <img :src="venue.image" :alt="venue.name"
                                    class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-110" />
                                <div class="absolute right-4 top-4 rounded-full bg-white px-4 py-1 text-sm font-semibold text-appGreenDark">
                                    {{ venue.sport }}
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="mb-2 flex items-center justify-between">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ venue.name }}</h3>
                                    <div class="flex items-center gap-1">
                                        <Star class="h-5 w-5 fill-yellow-400 text-yellow-400" />
                                        <span class="font-medium text-gray-700 dark:text-gray-300">{{ venue.rating }}</span>
                                    </div>
                                </div>
                                <p class="mb-4 flex items-center gap-2 text-gray-600 dark:text-gray-300">
                                    <MapPin class="h-4 w-4" />
                                    {{ venue.location }}
                                </p>
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-bold text-appGreenMedium">{{ venue.price }}</span>
                                    <Link :href="route('venues.show', venue.id)"
                                        class="rounded-full bg-appGreenLight px-6 py-2 text-sm font-semibold text-white transition-all hover:bg-appGreenMedium">
                                        Booking
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Promotions Section -->
            <section class="bg-gradient-to-b from-white to-gray-50 py-20 dark:from-gray-800 dark:to-gray-900">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="mb-12 text-center">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Promo Spesial</h2>
                        <p class="mt-2 text-gray-600 dark:text-gray-300">Hemat lebih banyak dengan promo menarik</p>
                    </div>
                    <div class="grid gap-8 md:grid-cols-3">
                        <div v-for="promo in promotions" :key="promo.id"
                            class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-appBlueLight to-appBlueDark p-1">
                            <div class="relative h-full rounded-[23px] bg-white p-6 dark:bg-gray-800">
                                <div class="absolute -right-8 -top-8 h-24 w-24 rotate-12 bg-appBlueLight opacity-10"></div>
                                <div class="relative">
                                    <span class="mb-4 inline-block rounded-lg bg-blue-100 px-3 py-1 text-sm font-semibold text-appBlueDark dark:bg-blue-900 dark:text-blue-200">
                                        Hingga {{ promo.validUntil }}
                                    </span>
                                    <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">{{ promo.title }}</h3>
                                    <p class="mb-4 text-gray-600 dark:text-gray-300">{{ promo.description }}</p>
                                    <div class="text-3xl font-bold text-appBlueDark dark:text-appBlueLight">{{ promo.discount }}</div>
                                    <button class="mt-4 w-full rounded-xl bg-appBlueLight py-2 text-center font-semibold text-white transition-all hover:bg-appBlueMedium">
                                        Klaim Sekarang
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- How It Works Section -->
            <section class="py-20 bg-white dark:bg-gray-900">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Cara Booking</h2>
                        <p class="mt-2 text-gray-600 dark:text-gray-300">Booking lapangan dalam 3 langkah mudah</p>
                    </div>

                    <div class="grid md:grid-cols-3 gap-8">
                        <div class="relative">
                            <div class="flex items-center justify-center w-16 h-16 mb-6 rounded-full bg-appGreenLight/10 text-appGreenLight">
                                <Search class="h-8 w-8" />
                            </div>
                            <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">1. Cari Venue</h3>
                            <p class="text-gray-600 dark:text-gray-400">Temukan venue olahraga favoritmu dengan mudah</p>
                            <!-- Connector Line (hidden on mobile) -->
                            <div class="hidden md:block absolute top-8 left-full w-full h-0.5 bg-gradient-to-r from-appGreenLight to-transparent"></div>
                        </div>
                        <!-- Add similar blocks for steps 2 and 3 -->
                    </div>
                </div>
            </section>

            <!-- Mobile App Section -->
            <section class="relative overflow-hidden bg-appGreenDark py-20">
                <div class="absolute inset-0">
                    <div class="absolute inset-0 bg-[url('/images/pattern-2.svg')] bg-center opacity-5"></div>
                </div>
                <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="grid items-center gap-12 lg:grid-cols-2">
                        <div>
                            <h2 class="text-3xl font-bold text-white md:text-4xl">Download Aplikasi SportVenue</h2>
                            <p class="mt-4 text-lg text-gray-300">Booking lebih mudah dengan aplikasi mobile kami</p>
                            <div class="mt-8 flex flex-wrap gap-4">
                                <a href="#" class="transform transition-transform hover:scale-105">
                                    <img src="/images/app-store-badge.png" alt="Download on App Store" class="h-12" />
                                </a>
                                <a href="#" class="transform transition-transform hover:scale-105">
                                    <img src="/images/play-store-badge.png" alt="Get it on Google Play" class="h-12" />
                                </a>
                            </div>
                        </div>
                        <div class="relative">
                            <img src="/images/app-preview.png" alt="SportVenue App"
                                class="mx-auto w-full max-w-md transform transition-transform hover:scale-105" />
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </AuthenticatedLayout>

    <GuestLayout v-else>
        <main class="min-h-screen bg-gray-50 dark:bg-gray-900">
            <!-- Hero Section with Search -->
            <section class="relative bg-appGreenDark pt-20 pb-32">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="absolute inset-0 bg-[url('/images/pattern-2.svg')] bg-center opacity-5"></div>
                </div>
                <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="mx-auto max-w-3xl text-center">
                        <h1 class="mb-6 text-4xl font-extrabold text-white md:text-6xl">
                            Temukan dan Booking
                            <span class="text-appGreenLight">Lapangan Olahraga</span>
                            Favoritmu
                        </h1>
                        <p class="mb-8 text-lg text-gray-300">
                            Platform booking lapangan olahraga terpercaya dengan ribuan venue di seluruh Indonesia
                        </p>

                        <!-- Search Bar -->
                        <div class="mx-auto max-w-2xl">
                            <div class="flex overflow-hidden rounded-full bg-white shadow-lg">
                                <div class="flex flex-1 items-center px-4">
                                    <Search class="h-5 w-5 text-gray-400" />
                                    <input type="text" placeholder="Cari lapangan olahraga..."
                                        class="w-full border-0 px-4 py-4 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-0" />
                                </div>
                                <button class="bg-appGreenLight px-8 font-semibold text-white transition-colors hover:bg-appGreenMedium">
                                    Cari
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Stats Section -->
            <section class="relative z-20 bg-transparent">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="relative -mt-24 grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div class="rounded-2xl bg-white p-8 shadow-lg transition-transform hover:-translate-y-1 dark:bg-gray-800">
                            <Trophy class="mb-4 h-10 w-10 text-appGreenLight" />
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">500+</h3>
                            <p class="text-gray-600 dark:text-gray-300">Venue Olahraga</p>
                        </div>
                        <div class="rounded-2xl bg-white p-8 shadow-lg transition-transform hover:-translate-y-1 dark:bg-gray-800">
                            <Timer class="mb-4 h-10 w-10 text-appGreenLight" />
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">24/7</h3>
                            <p class="text-gray-600 dark:text-gray-300">Layanan Booking</p>
                        </div>
                        <div class="rounded-2xl bg-white p-8 shadow-lg transition-transform hover:-translate-y-1 dark:bg-gray-800">
                            <Shield class="mb-4 h-10 w-10 text-appGreenLight" />
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">100%</h3>
                            <p class="text-gray-600 dark:text-gray-300">Pembayaran Aman</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Featured Venues -->
            <section class="relative z-10 bg-gray-50 dark:bg-gray-900 pt-16">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="mb-12">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Venue Populer</h2>
                        <p class="mt-2 text-gray-600 dark:text-gray-300">Pilihan terbaik untuk olahraga favoritmu</p>
                    </div>
                    <div class="grid gap-8 md:grid-cols-3">
                        <div v-for="venue in featuredVenues" :key="venue.id"
                            class="group relative overflow-hidden rounded-3xl bg-white shadow-lg transition-all hover:-translate-y-1 hover:shadow-xl dark:bg-gray-800">
                            <div class="aspect-[4/3] overflow-hidden">
                                <img :src="venue.image" :alt="venue.name"
                                    class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-110" />
                                <div class="absolute right-4 top-4 rounded-full bg-white px-4 py-1 text-sm font-semibold text-appGreenDark">
                                    {{ venue.sport }}
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="mb-2 flex items-center justify-between">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ venue.name }}</h3>
                                    <div class="flex items-center gap-1">
                                        <Star class="h-5 w-5 fill-yellow-400 text-yellow-400" />
                                        <span class="font-medium text-gray-700 dark:text-gray-300">{{ venue.rating }}</span>
                                    </div>
                                </div>
                                <p class="mb-4 flex items-center gap-2 text-gray-600 dark:text-gray-300">
                                    <MapPin class="h-4 w-4" />
                                    {{ venue.location }}
                                </p>
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-bold text-appGreenMedium">{{ venue.price }}</span>
                                    <Link :href="route('venues.show', venue.id)"
                                        class="rounded-full bg-appGreenLight px-6 py-2 text-sm font-semibold text-white transition-all hover:bg-appGreenMedium">
                                        Booking
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Promotions -->
            <section class="bg-gradient-to-b from-white to-gray-50 py-20 dark:from-gray-800 dark:to-gray-900">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="mb-12 text-center">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Promo Spesial</h2>
                        <p class="mt-2 text-gray-600 dark:text-gray-300">Hemat lebih banyak dengan promo menarik</p>
                    </div>
                    <div class="grid gap-8 md:grid-cols-3">
                        <div v-for="promo in promotions" :key="promo.id"
                            class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-appBlueLight to-appBlueDark p-1">
                            <div class="relative h-full rounded-[23px] bg-white p-6 dark:bg-gray-800">
                                <div class="absolute -right-8 -top-8 h-24 w-24 rotate-12 bg-appBlueLight opacity-10"></div>
                                <div class="relative">
                                    <span class="mb-4 inline-block rounded-lg bg-blue-100 px-3 py-1 text-sm font-semibold text-appBlueDark dark:bg-blue-900 dark:text-blue-200">
                                        Hingga {{ promo.validUntil }}
                                    </span>
                                    <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">{{ promo.title }}</h3>
                                    <p class="mb-4 text-gray-600 dark:text-gray-300">{{ promo.description }}</p>
                                    <div class="text-3xl font-bold text-appBlueDark dark:text-appBlueLight">{{ promo.discount }}</div>
                                    <button class="mt-4 w-full rounded-xl bg-appBlueLight py-2 text-center font-semibold text-white transition-all hover:bg-appBlueMedium">
                                        Klaim Sekarang
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- How It Works Section -->
            <section class="py-20 bg-white dark:bg-gray-900">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Cara Booking</h2>
                        <p class="mt-2 text-gray-600 dark:text-gray-300">Booking lapangan dalam 3 langkah mudah</p>
                    </div>

                    <div class="grid md:grid-cols-3 gap-8">
                        <div class="relative">
                            <div class="flex items-center justify-center w-16 h-16 mb-6 rounded-full bg-appGreenLight/10 text-appGreenLight">
                                <Search class="h-8 w-8" />
                            </div>
                            <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">1. Cari Venue</h3>
                            <p class="text-gray-600 dark:text-gray-400">Temukan venue olahraga favoritmu dengan mudah</p>
                            <!-- Connector Line (hidden on mobile) -->
                            <div class="hidden md:block absolute top-8 left-full w-full h-0.5 bg-gradient-to-r from-appGreenLight to-transparent"></div>
                        </div>
                        <!-- Add similar blocks for steps 2 and 3 -->
                    </div>
                </div>
            </section>

            <!-- Mobile App Section -->
            <section class="relative overflow-hidden bg-appGreenDark py-20">
                <div class="absolute inset-0">
                    <div class="absolute inset-0 bg-[url('/images/pattern-2.svg')] bg-center opacity-5"></div>
                </div>
                <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="grid items-center gap-12 lg:grid-cols-2">
                        <div>
                            <h2 class="text-3xl font-bold text-white md:text-4xl">Download Aplikasi SportVenue</h2>
                            <p class="mt-4 text-lg text-gray-300">Booking lebih mudah dengan aplikasi mobile kami</p>
                            <div class="mt-8 flex flex-wrap gap-4">
                                <a href="#" class="transform transition-transform hover:scale-105">
                                    <img src="/images/app-store-badge.png" alt="Download on App Store" class="h-12" />
                                </a>
                                <a href="#" class="transform transition-transform hover:scale-105">
                                    <img src="/images/play-store-badge.png" alt="Get it on Google Play" class="h-12" />
                                </a>
                            </div>
                        </div>
                        <div class="relative">
                            <img src="/images/app-preview.png" alt="SportVenue App"
                                class="mx-auto w-full max-w-md transform transition-transform hover:scale-105" />
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </GuestLayout>
</template>
