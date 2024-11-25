<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Search, Filter, MapPin, Star, Clock, Calendar } from 'lucide-vue-next';

const props = defineProps({
    auth: {
        type: Object,
        required: true
    }
});

// Compute which layout to use based on auth status
const Layout = computed(() => {
    return props.auth?.user ? AuthenticatedLayout : GuestLayout;
});

const venues = ref([
    {
        id: 1,
        name: 'Lapangan Futsal Bintang',
        type: 'Futsal',
        location: 'Jakarta Selatan',
        rating: 4.8,
        price: 150000,
        image: '/images/venues/futsal-1.jpg',
        availableNow: true
    },
    {
        id: 2,
        name: 'GOR Badminton Sejahtera',
        type: 'Badminton',
        location: 'Jakarta Pusat',
        rating: 4.5,
        price: 100000,
        image: '/images/venues/badminton-1.jpg',
        availableNow: false
    },
    // Add more dummy data as needed
]);

const searchQuery = ref('');
const selectedType = ref('all');

const sportTypes = [
    { id: 'all', name: 'Semua' },
    { id: 'futsal', name: 'Futsal' },
    { id: 'badminton', name: 'Badminton' },
    { id: 'basketball', name: 'Basket' },
    { id: 'tennis', name: 'Tenis' },
];
</script>

<template>
    <Layout>
        <Head title="Cari Lapangan Olahraga" />

        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Cari Lapangan
            </h2>
        </template>

        <!-- Hero Section with Search -->
        <section class="relative bg-appGreenDark py-20">
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute inset-0 bg-[url('/images/pattern-2.svg')] bg-center opacity-5"></div>
            </div>
            <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-3xl text-center">
                    <h1 class="mb-6 text-4xl font-extrabold text-white md:text-6xl">
                        Temukan
                        <span class="text-appGreenLight">Lapangan Olahraga</span>
                        Favoritmu
                    </h1>
                    <p class="mb-8 text-lg text-gray-300">
                        Pilih dan booking lapangan favoritmu dengan mudah dan cepat
                    </p>

                    <!-- Search and Filter Section -->
                    <div class="mx-auto max-w-2xl">
                        <div class="flex overflow-hidden rounded-full bg-white shadow-lg">
                            <div class="flex flex-1 items-center px-4">
                                <Search class="h-5 w-5 text-gray-400" />
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Cari nama atau lokasi lapangan..."
                                    class="w-full border-0 px-4 py-4 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-0"
                                />
                            </div>
                            <div class="flex items-center gap-2 pr-2">
                                <select
                                    v-model="selectedType"
                                    class="rounded-full border-0 bg-gray-50 px-4 py-3 text-gray-700 focus:ring-2 focus:ring-appGreenLight"
                                >
                                    <option v-for="type in sportTypes" :key="type.id" :value="type.id">
                                        {{ type.name }}
                                    </option>
                                </select>

                                <button class="flex items-center gap-2 rounded-full bg-appGreenLight px-6 py-3 font-semibold text-white transition-colors hover:bg-appGreenMedium">
                                    <Filter class="h-5 w-5" />
                                    Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Venue Grid -->
        <div class="mx-auto -mt-10 max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-8 md:grid-cols-3">
                <Link
                    v-for="venue in venues"
                    :key="venue.id"
                    :href="`/venue-detail/${venue.id}`"
                    class="group relative overflow-hidden rounded-3xl bg-white shadow-lg transition-all hover:-translate-y-1 hover:shadow-xl dark:bg-gray-800"
                >
                    <div class="aspect-[4/3] overflow-hidden">
                        <img :src="venue.image" :alt="venue.name"
                            class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-110" />
                        <div class="absolute right-4 top-4 rounded-full bg-white/95 px-4 py-1 text-sm font-semibold text-appGreenDark backdrop-blur-sm dark:bg-gray-800/95 dark:text-appGreenLight">
                            Rp {{ venue.price.toLocaleString() }}/jam
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

                        <div class="mb-4 space-y-2">
                            <p class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                                <MapPin class="h-4 w-4" />
                                {{ venue.location }}
                            </p>
                            <p class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                                <Calendar class="h-4 w-4" />
                                {{ venue.type }}
                            </p>
                        </div>

                        <div class="flex items-center justify-between">
                            <span :class="[
                                'flex items-center gap-2',
                                venue.availableNow
                                    ? 'text-appGreenLight'
                                    : 'text-gray-500 dark:text-gray-400'
                            ]">
                                <Clock class="h-4 w-4" />
                                {{ venue.availableNow ? 'Tersedia Sekarang' : 'Tidak Tersedia' }}
                            </span>

                            <button class="rounded-full bg-appGreenLight px-6 py-2 text-sm font-semibold text-white transition-all hover:bg-appGreenMedium">
                                Booking
                            </button>
                        </div>
                    </div>
                </Link>
            </div>
        </div>
    </Layout>
</template>
