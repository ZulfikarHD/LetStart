<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { MapPin, Star, Trophy, Timer, Shield, Search, Filter, Heart, Share2, ArrowUpRight } from 'lucide-vue-next';
import { TransitionRoot } from '@headlessui/vue';

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

const categories = ref([
    {
        id: 1,
        name: 'Futsal',
        image: '/images/categories/futsal.jpg',
        venueCount: 120,
        description: 'Lapangan futsal indoor dan outdoor berkualitas',
        popularLocations: ['Jakarta Selatan', 'Jakarta Pusat', 'Tangerang'],
        rating: 4.8,
        reviews: 234,
        isFavorite: false,
        openHours: '06:00 - 23:00',
        facilities: ['Parkir Luas', 'Toilet', 'Musholla']
    },
    {
        id: 2,
        name: 'Badminton',
        image: '/images/categories/badminton.jpg',
        venueCount: 85,
        description: 'GOR badminton dengan fasilitas lengkap',
        popularLocations: ['Jakarta Timur', 'Bekasi', 'Depok'],
        rating: 4.7,
        reviews: 189,
        isFavorite: false,
        openHours: '05:00 - 22:00',
        facilities: ['AC', 'Kantin', 'Ruang Ganti']
    },
    {
        id: 3,
        name: 'Basket',
        image: '/images/categories/basketball.jpg',
        venueCount: 65,
        description: 'Lapangan basket indoor dan outdoor standar',
        popularLocations: ['Jakarta Barat', 'Tangerang Selatan', 'Bogor'],
        rating: 4.6,
        reviews: 156,
        isFavorite: false,
        openHours: '06:00 - 21:00',
        facilities: ['Tribun', 'Lampu Malam', 'Parkir']
    },
    {
        id: 4,
        name: 'Tenis',
        image: '/images/categories/tennis.jpg',
        venueCount: 45,
        description: 'Lapangan tenis dengan berbagai jenis permukaan',
        popularLocations: ['Jakarta Utara', 'BSD', 'Gading Serpong'],
        rating: 4.9,
        reviews: 123,
        isFavorite: false,
        openHours: '06:00 - 20:00',
        facilities: ['Pro Shop', 'Pelatih', 'Cafe']
    }
]);

const isNavigating = ref(false);
const isLoading = ref(true);
const showFilters = ref(false);
const selectedSort = ref('popular');

const navigateToVenues = (categoryId) => {
    if (isNavigating.value) return;
    isNavigating.value = true;

    router.visit(`/venues?category=${categoryId}`, {
        onFinish: () => {
            isNavigating.value = false;
        }
    });
};

const toggleFavorite = (category) => {
    category.isFavorite = !category.isFavorite;
    // Add animation class
    const el = document.getElementById(`fav-${category.id}`);
    el.classList.add('scale-125');
    setTimeout(() => el.classList.remove('scale-125'), 200);
};

onMounted(() => {
    setTimeout(() => {
        isLoading.value = false;
    }, 1000);
});

// Enhanced search and filter functionality
const searchQuery = ref('');
const isSearching = ref(false);
const activeFilters = ref([]);

const sortOptions = [
    { id: 'popular', label: 'Terpopuler' },
    { id: 'rating', label: 'Rating Tertinggi' },
    { id: 'venues', label: 'Jumlah Venue' }
];

const quickFilters = [
    { id: 'nearest', label: 'Terdekat', icon: MapPin },
    { id: 'popular', label: 'Terpopuler', icon: Star },
    { id: 'open', label: 'Buka Sekarang', icon: Timer }
];

// Add debounced search with loading state
const handleSearch = debounce(() => {
    isSearching.value = true;
    setTimeout(() => {
        isSearching.value = false;
    }, 500);
}, 300);

// Watch for search query changes
watch(searchQuery, () => {
    if (searchQuery.value) {
        handleSearch();
    } else {
        isSearching.value = false;
    }
});

const toggleFilter = (filterId) => {
    const index = activeFilters.value.indexOf(filterId);
    if (index === -1) {
        activeFilters.value.push(filterId);
    } else {
        activeFilters.value.splice(index, 1);
    }
};

const filteredCategories = computed(() => {
    let filtered = categories.value;

    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(category =>
            category.name.toLowerCase().includes(query) ||
            category.description.toLowerCase().includes(query) ||
            category.popularLocations.some(location =>
                location.toLowerCase().includes(query)
            )
        );
    }

    // Apply sorting
    switch (selectedSort.value) {
        case 'rating':
            filtered = [...filtered].sort((a, b) => b.rating - a.rating);
            break;
        case 'venues':
            filtered = [...filtered].sort((a, b) => b.venueCount - a.venueCount);
            break;
        default:
            filtered = [...filtered].sort((a, b) => b.reviews - a.reviews);
    }

    return filtered;
});

function debounce(fn, delay) {
    let timeoutId;
    return (...args) => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => fn(...args), delay);
    };
}
</script>

<template>
    <Layout>
        <Head title="Kategori Olahraga" />

        <!-- Enhanced Hero Section with Parallax Effect -->
        <section class="relative bg-gradient-to-br from-appGreenDark to-appGreenMedium pt-24 pb-36 z-10 overflow-hidden">
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute inset-0 bg-[url('/images/pattern-2.svg')] bg-center opacity-5 transform rotate-3 scale-110"></div>
            </div>
            <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-3xl text-center">
                    <h1 class="mb-6 text-4xl font-extrabold text-white md:text-6xl tracking-tight">
                        Pilih
                        <span class="text-appGreenLight relative">
                            Kategori Olahraga
                            <span class="absolute bottom-0 left-0 w-full h-1 bg-appGreenLight/30 rounded-full"></span>
                        </span>
                        Favoritmu
                    </h1>
                    <p class="mb-12 text-lg text-gray-200 leading-relaxed">
                        Temukan berbagai lapangan olahraga yang sesuai dengan kebutuhanmu
                    </p>

                    <!-- Enhanced Search Bar -->
                    <div class="mx-auto max-w-2xl relative">
                        <div class="flex overflow-hidden rounded-full bg-white/95 shadow-lg backdrop-blur-sm transition-all duration-300 focus-within:shadow-2xl">
                            <div class="flex flex-1 items-center px-4">
                                <Search class="h-5 w-5 text-gray-400" />
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Cari kategori olahraga..."
                                    class="w-full border-0 px-4 py-4 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-0 bg-transparent"
                                />
                                <button v-if="searchQuery" @click="searchQuery = ''"
                                    class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                                    <span class="sr-only">Clear search</span>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                </button>
                            </div>
                            <button @click="showFilters = true"
                                class="border-l border-gray-200 px-6 text-gray-600 hover:bg-gray-50 transition-colors flex items-center gap-2">
                                <Filter class="h-5 w-5" />
                                Filter
                            </button>
                        </div>
                    </div>

                    <!-- Quick Filters -->
                    <div class="mt-6 flex flex-wrap justify-center gap-3">
                        <button v-for="filter in quickFilters"
                            :key="filter.id"
                            @click="toggleFilter(filter.id)"
                            :class="[
                                'flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium transition-all duration-300',
                                activeFilters.includes(filter.id)
                                    ? 'bg-white text-appGreenDark shadow-lg scale-105'
                                    : 'bg-white/20 text-white hover:bg-white/30'
                            ]"
                        >
                            <component :is="filter.icon" class="h-4 w-4" />
                            {{ filter.label }}
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Categories Grid Section -->
        <section class="relative z-20 bg-gray-50 dark:bg-gray-900 -mt-20 pb-20 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl">
                <!-- Sort Controls -->
                <div class="mb-8 flex justify-between items-center">
                    <p class="text-gray-600 dark:text-gray-400">
                        Menampilkan {{ filteredCategories.length }} kategori
                    </p>
                    <div class="flex items-center gap-3">
                        <label class="text-sm text-gray-600 dark:text-gray-400">Urutkan:</label>
                        <select v-model="selectedSort"
                            class="rounded-lg border-gray-200 bg-white px-4 py-2 text-sm text-gray-700 focus:border-appGreenLight focus:ring-appGreenLight dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
                            <option v-for="option in sortOptions" :key="option.id" :value="option.id">
                                {{ option.label }}
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Loading Skeleton -->
                <TransitionRoot appear :show="isLoading" as="div" class="grid gap-8 md:grid-cols-2">
                    <div v-for="n in 4" :key="n"
                        class="animate-pulse rounded-3xl bg-gray-200 dark:bg-gray-700 overflow-hidden">
                        <div class="aspect-[16/9]"></div>
                        <div class="p-8 space-y-4">
                            <div class="h-8 w-2/3 rounded-lg bg-gray-300 dark:bg-gray-600"></div>
                            <div class="h-4 w-full rounded bg-gray-300 dark:bg-gray-600"></div>
                            <div class="h-4 w-3/4 rounded bg-gray-300 dark:bg-gray-600"></div>
                        </div>
                    </div>
                </TransitionRoot>

                <!-- Categories Grid -->
                <TransitionRoot appear :show="!isLoading" as="div"
                    class="grid gap-8 md:grid-cols-2"
                    enter="transition-opacity duration-500"
                    enter-from="opacity-0"
                    enter-to="opacity-100">
                    <div v-for="category in filteredCategories"
                        :key="category.id"
                        class="group relative overflow-hidden rounded-3xl bg-white shadow-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-xl dark:bg-gray-800"
                    >
                        <!-- Card Header -->
                        <div class="relative aspect-[16/9] overflow-hidden">
                            <img :src="category.image" :alt="category.name"
                                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" />

                            <!-- Overlay Actions -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="absolute bottom-4 left-4 right-4 flex justify-between items-center">
                                    <button @click.stop="toggleFavorite(category)"
                                        :id="`fav-${category.id}`"
                                        class="rounded-full bg-white/90 p-2 text-gray-900 hover:bg-white transition-all duration-300">
                                        <Heart :class="['h-5 w-5 transition-colors', category.isFavorite ? 'fill-red-500 text-red-500' : 'text-gray-700']" />
                                    </button>
                                    <button @click.stop
                                        class="rounded-full bg-white/90 p-2 text-gray-900 hover:bg-white transition-all duration-300">
                                        <Share2 class="h-5 w-5" />
                                    </button>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            <div class="absolute right-4 top-4 z-10 rounded-full bg-appGreenLight/90 px-3 py-1 text-sm font-medium text-white backdrop-blur-sm">
                                <div class="flex items-center gap-1">
                                    <div class="h-2 w-2 rounded-full bg-white animate-pulse"></div>
                                    Buka
                                </div>
                            </div>
                        </div>

                        <!-- Card Content -->
                        <div class="p-6 sm:p-8">
                            <div class="mb-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ category.name }}</h3>
                                    <div class="flex items-center gap-1">
                                        <Star class="h-5 w-5 text-yellow-400 fill-yellow-400" />
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ category.rating }}</span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">({{ category.reviews }})</span>
                                    </div>
                                </div>
                                <p class="text-gray-600 dark:text-gray-300">{{ category.description }}</p>
                            </div>

                            <!-- Stats Grid -->
                            <div class="mb-6 grid grid-cols-2 gap-4 rounded-xl bg-gray-50 p-4 dark:bg-gray-700/50">
                                <div class="flex items-center gap-2">
                                    <Trophy class="h-5 w-5 text-appGreenLight" />
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Venue</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ category.venueCount }}+</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <Timer class="h-5 w-5 text-appGreenLight" />
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Jam Buka</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ category.openHours }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Facilities Tags -->
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-2">Fasilitas:</h4>
                                <div class="flex flex-wrap gap-2">
                                    <span v-for="facility in category.facilities" :key="facility"
                                        class="rounded-full bg-appGreenLight/10 px-3 py-1 text-sm font-medium text-appGreenLight">
                                        {{ facility }}
                                    </span>
                                </div>
                            </div>

                            <!-- Popular Locations -->
                            <div class="space-y-2">
                                <h4 class="font-medium text-gray-900 dark:text-white">Lokasi Populer:</h4>
                                <div class="flex flex-wrap gap-2">
                                    <span v-for="location in category.popularLocations" :key="location"
                                        class="rounded-full bg-gray-100 px-3 py-1 text-sm text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                        {{ location }}
                                    </span>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <button @click="navigateToVenues(category.id)"
                                class="mt-6 w-full rounded-xl bg-appGreenLight py-3 font-semibold text-white transition-colors hover:bg-appGreenMedium flex items-center justify-center gap-2"
                                :class="{ 'opacity-75 cursor-not-allowed': isNavigating }">
                                Lihat Venue
                                <ArrowUpRight class="h-5 w-5" />
                            </button>
                        </div>
                    </div>
                </TransitionRoot>

                <!-- Empty State -->
                <TransitionRoot appear :show="filteredCategories.length === 0 && !isLoading" as="div"
                    class="text-center py-16"
                    enter="transition-opacity duration-500"
                    enter-from="opacity-0"
                    enter-to="opacity-100">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
                        <Search class="h-8 w-8 text-gray-400" />
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                        Tidak ada kategori yang ditemukan
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Coba ubah kata kunci pencarian Anda
                    </p>
                    <button @click="searchQuery = ''"
                        class="mt-4 inline-flex items-center gap-2 text-appGreenLight hover:text-appGreenMedium">
                        <ArrowUpRight class="h-5 w-5" />
                        Reset pencarian
                    </button>
                </TransitionRoot>
            </div>
        </section>
    </Layout>
</template>
