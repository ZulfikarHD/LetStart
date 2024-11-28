<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {
    Search,
    Filter,
    MapPin,
    Star,
    Clock,
    Calendar,
    Loader2,
    Dumbbell,      // For general sports/fitness
    BadgePlus,     // For Badminton
    CircleDot,     // For general ball sports
    Trophy,        // For competitive sports
    Activity,      // For general activity
    Map,        // For map view toggle
    LayoutGrid, // For grid view toggle
    Heart,      // For favorites
    Share2,     // For sharing
    Info        // For quick view
} from 'lucide-vue-next';
import { TransitionRoot, Dialog, DialogPanel, DialogTitle } from '@headlessui/vue';

// Add debounce utility function
const debounce = (fn, delay) => {
    let timeoutId;
    return (...args) => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => fn(...args), delay);
    };
};

// Props definition with updated icon names based on available Lucide icons
const props = defineProps({
    auth: {
        type: Object,
        required: true
    },
    categories: {
        type: Array,
        required: false,
        default: () => ([
            { id: 1, name: 'Futsal', icon: 'CircleDot' },
            { id: 2, name: 'Badminton', icon: 'BadgePlus' },
            { id: 3, name: 'Basket', icon: 'CircleDot' },
            { id: 4, name: 'Tenis', icon: 'Trophy' },
            { id: 5, name: 'Voli', icon: 'Activity' },
        ])
    },
    initialFilters: {
        type: Object,
        default: () => ({})
    }
});

// Compute which layout to use based on auth status
const Layout = computed(() => {
    return props.auth?.user ? AuthenticatedLayout : GuestLayout;
});

// Update getCategoryIcon function with new icon names
const getCategoryIcon = (categoryId) => {
    const iconMap = {
        'CircleDot': CircleDot,
        'BadgePlus': BadgePlus,
        'Trophy': Trophy,
        'Activity': Activity,
        'Dumbbell': Dumbbell,
    };

    const category = props.categories.find(cat => cat.id === categoryId);
    return category ? iconMap[category.icon] : Activity;
};

// Dummy venues data (expanded)
const venues = ref([
    {
        id: 1,
        name: 'Lapangan Futsal Bintang',
        type: 'Futsal',
        location: 'Jakarta Selatan',
        rating: 4.8,
        price: 'Rp 150.000/jam',
        image: 'https://placehold.co/600x400/70BC2A/white?text=Futsal+Bintang',
        availableNow: true,
        category_id: 1,
        facilities: ['Parkir', 'Toilet', 'Musholla', 'Kantin'],
    },
    {
        id: 2,
        name: 'GOR Badminton Sejahtera',
        type: 'Badminton',
        location: 'Jakarta Pusat',
        rating: 4.5,
        price: 'Rp 100.000/jam',
        image: 'https://placehold.co/600x400/52A7E8/white?text=Badminton+Sejahtera',
        availableNow: false,
        category_id: 2,
        facilities: ['Parkir', 'Toilet', 'Wifi'],
    },
    {
        id: 3,
        name: 'Lapangan Basket Champion',
        type: 'Basket',
        location: 'Jakarta Timur',
        rating: 4.7,
        price: 'Rp 200.000/jam',
        image: 'https://placehold.co/600x400/47981F/white?text=Basket+Champion',
        availableNow: true,
        category_id: 3,
        facilities: ['Parkir', 'Toilet', 'Loker', 'Kantin'],
    },
    {
        id: 4,
        name: 'Tenis Center Pro',
        type: 'Tenis',
        location: 'Jakarta Utara',
        rating: 4.6,
        price: 'Rp 180.000/jam',
        image: 'https://placehold.co/600x400/1B66DA/white?text=Tenis+Pro',
        availableNow: true,
        category_id: 4,
        facilities: ['Parkir', 'Toilet', 'Pro Shop'],
    },
    {
        id: 5,
        name: 'Lapangan Voli Garuda',
        type: 'Voli',
        location: 'Jakarta Barat',
        rating: 4.4,
        price: 'Rp 120.000/jam',
        image: 'https://placehold.co/600x400/2F5D1E/white?text=Voli+Garuda',
        availableNow: false,
        category_id: 5,
        facilities: ['Parkir', 'Toilet'],
    },
]);

// Dummy facilities data
const facilities = ref([
    { id: 1, name: 'Parkir' },
    { id: 2, name: 'Toilet' },
    { id: 3, name: 'Wifi' },
    { id: 4, name: 'Musholla' },
    { id: 5, name: 'Kantin' },
    { id: 6, name: 'Loker' },
    { id: 7, name: 'Pro Shop' },
]);

const searchQuery = ref('');
const selectedType = ref('all');

// Replace static sportTypes with dynamic categories
const sportTypes = computed(() => [
    { id: 'all', name: 'Semua' },
    ...props.categories.map(category => ({
        id: category.id,
        name: category.name
    }))
]);

const isLoading = ref(false);
const isSearching = ref(false);
const searchSuggestions = ref([]);
const priceRange = ref({
    min: 0,
    max: 1000000
});
const sortBy = ref('recommended');

const loadMoreVenues = async () => {
    if (isLoading.value) return;
    isLoading.value = true;
    // Load more logic
    isLoading.value = false;
};

const selectedVenue = ref(null);
const isQuickViewOpen = ref(false);

const openQuickView = (venue, event) => {
    event.stopPropagation();
    selectedVenue.value = venue;
    isQuickViewOpen.value = true;
};

const isNavigating = ref(false);

const navigateToVenue = (venueId) => {
    if (isNavigating.value) return;
    isNavigating.value = true;

    router.visit(`/venues/${venueId}`, {
        onFinish: () => {
            isNavigating.value = false;
        }
    });
};

const searchResults = ref([]);

// Update the search function to use our debounce utility
const handleSearch = (query) => {
    if (!query.trim()) {
        searchResults.value = [];
        return;
    }
    isSearching.value = true;
    // Simulate API delay
    setTimeout(() => {
        searchResults.value = venues.value.filter(v =>
            v.name.toLowerCase().includes(query.toLowerCase()) ||
            v.location.toLowerCase().includes(query.toLowerCase())
        );
        isSearching.value = false;
    }, 300);
};

// Create debounced search
const debouncedSearch = debounce(handleSearch, 300);

const showFilters = ref(false);
const filters = ref({
    price: { min: 0, max: 1000000 },
    rating: null,
    facilities: [],
    availability: 'all',
    category_id: props.initialFilters.category_id || 'all',
    sort: props.initialFilters.sort || 'recommended',
    date: props.initialFilters.date || null
});

const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(price);
};

// Add method to update URL with filters
const updateUrlWithFilters = () => {
    router.get(
        route('venues.index'),
        { ...filters.value },
        { preserveState: true, preserveScroll: true }
    );
};

const error = ref(null);
const isInitialLoading = ref(true);

// Add error handling to venue fetching
const fetchVenues = async () => {
    try {
        isLoading.value = true;
        error.value = null;
        // Simulate API delay
        await new Promise(resolve => setTimeout(resolve, 1000));
        // Use dummy data
        venues.value = venues.value;
    } catch (e) {
        error.value = 'Terjadi kesalahan saat memuat data. Silakan coba lagi.';
    } finally {
        isLoading.value = false;
        isInitialLoading.value = false;
    }
};

// Add view mode state
const viewMode = ref('grid'); // 'grid' or 'map'
const mapCenter = ref([106.8456, -6.2088]); // Jakarta coordinates
const mapZoom = ref(12);

// Add favorite functionality
const toggleFavorite = (venue, event) => {
    event.stopPropagation();
    venue.isFavorite = !venue.isFavorite;
    // Add animation class
    const el = document.getElementById(`fav-${venue.id}`);
    el.classList.add('scale-125');
    setTimeout(() => el.classList.remove('scale-125'), 200);
};
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
        <section class="relative bg-appGreenDark pt-20 pb-32 z-10">
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
                            <div class="relative flex-1">
                                <div class="flex items-center px-4">
                                    <Search class="h-5 w-5 text-gray-400" />
                                    <input
                                        v-model="searchQuery"
                                        @input="debouncedSearch(searchQuery)"
                                        type="text"
                                        placeholder="Cari nama atau lokasi lapangan..."
                                        class="w-full border-0 px-4 py-4 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-0"
                                    />
                                    <Transition name="fade">
                                        <Loader2 v-if="isSearching" class="h-5 w-5 animate-spin text-gray-400" />
                                    </Transition>
                                </div>

                                <!-- Search Results Dropdown -->
                                <Transition
                                    enter-active-class="transition duration-200 ease-out"
                                    enter-from-class="transform scale-95 opacity-0"
                                    enter-to-class="transform scale-100 opacity-100"
                                    leave-active-class="transition duration-75 ease-in"
                                    leave-from-class="transform scale-100 opacity-100"
                                    leave-to-class="transform scale-95 opacity-0"
                                >
                                    <div v-if="searchQuery && searchResults.length > 0"
                                        class="absolute left-0 right-0 mt-2 max-h-60 overflow-y-auto rounded-xl bg-white py-1 shadow-lg dark:bg-gray-800">
                                        <div v-for="result in searchResults" :key="result.id"
                                            @click="navigateToVenue(result.id)"
                                            class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                            <img :src="result.image" :alt="result.name" class="h-12 w-12 rounded-lg object-cover" />
                                            <div>
                                                <h4 class="font-medium text-gray-900 dark:text-white">{{ result.name }}</h4>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ result.location }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </Transition>
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

        <!-- Category Pills - Move inside a new container section -->
        <section class="relative z-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="relative -mt-24 mb-8">
                    <div class="rounded-2xl bg-white/95 backdrop-blur-sm p-6 shadow-lg dark:bg-gray-800/95 transform-gpu">
                        <div class="flex gap-2 overflow-x-auto no-scrollbar pb-2">
                            <button
                                v-for="category in sportTypes"
                                :key="category.id"
                                @click="filters.category_id = category.id; updateUrlWithFilters()"
                                class="flex items-center gap-2 rounded-full px-4 py-2 whitespace-nowrap transition-all duration-300 ease-in-out"
                                :class="[
                                    filters.category_id === category.id
                                        ? 'bg-appGreenLight text-white'
                                        : 'bg-gray-50 text-gray-700 hover:bg-gray-100 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600'
                                ]"
                            >
                                <component
                                    :is="getCategoryIcon(category.id)"
                                    class="h-5 w-5"
                                    v-if="getCategoryIcon(category.id)"
                                />
                                {{ category.name }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- View Mode Toggle -->
        <div class="fixed right-4 bottom-4 z-30">
            <button @click="viewMode = viewMode === 'grid' ? 'map' : 'grid'"
                class="flex items-center gap-2 rounded-full bg-white px-4 py-2 shadow-lg hover:bg-gray-50 transition-colors dark:bg-gray-800 dark:hover:bg-gray-700"
            >
                <component :is="viewMode === 'grid' ? Map : LayoutGrid" class="h-5 w-5" />
                <span class="text-sm font-medium">
                    {{ viewMode === 'grid' ? 'Lihat Peta' : 'Lihat Grid' }}
                </span>
            </button>
        </div>

        <!-- Grid View -->
        <section v-if="viewMode === 'grid'" class="relative z-10 bg-gray-50 dark:bg-gray-900 pt-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Venue Tersedia</h2>
                    <p class="mt-2 text-gray-600 dark:text-gray-300">Pilihan terbaik untuk olahraga favoritmu</p>
                </div>
                <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                    <div v-for="venue in venues" :key="venue.id"
                        class="group relative overflow-hidden rounded-3xl bg-white shadow-lg dark:bg-gray-800
                               transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-xl">
                        <!-- Image Section with Overlay -->
                        <div class="relative aspect-[4/3] overflow-hidden">
                            <img :src="venue.image" :alt="venue.name"
                                class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-110" />

                            <!-- Action Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent
                                      opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="absolute bottom-3 left-3 right-3 flex justify-between items-center">
                                    <!-- Quick Actions -->
                                    <div class="flex gap-2">
                                        <button @click="toggleFavorite(venue, $event)"
                                            :id="`fav-${venue.id}`"
                                            class="rounded-full bg-white/90 p-1.5 text-gray-900 hover:bg-white transition-all duration-300">
                                            <Heart :class="['h-4 w-4 transition-colors',
                                                venue.isFavorite ? 'fill-red-500 text-red-500' : 'text-gray-700']" />
                                        </button>
                                        <button @click="openQuickView(venue, $event)"
                                            class="rounded-full bg-white/90 p-1.5 text-gray-900 hover:bg-white transition-all duration-300">
                                            <Info class="h-4 w-4" />
                                        </button>
                                    </div>
                                    <!-- Availability Badge -->
                                    <span :class="[
                                        'rounded-full px-3 py-1 text-xs font-medium',
                                        venue.availableNow
                                            ? 'bg-green-500/90 text-white'
                                            : 'bg-gray-500/90 text-white'
                                    ]">
                                        {{ venue.availableNow ? 'Tersedia' : 'Penuh' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Category Badge -->
                            <div class="absolute right-3 top-3 rounded-full bg-white/95 px-4 py-1
                                      text-sm font-semibold text-appGreenDark backdrop-blur-sm">
                                {{ venue.type }}
                            </div>
                        </div>

                        <!-- Content Section -->
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
                                <Link :href="`/venues/${venue.id}`"
                                    class="rounded-full bg-appGreenLight px-6 py-2 text-sm font-semibold text-white
                                           transition-all hover:bg-appGreenMedium focus:ring-2 focus:ring-appGreenLight focus:ring-offset-2"
                                >
                                    Booking
                                </Link>
                            </div>
                            <!-- Enhanced Facilities -->
                            <div class="mt-4 flex flex-wrap gap-2">
                                <span v-for="facility in venue.facilities" :key="facility"
                                    class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300"
                                >
                                    {{ facility }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Map View -->
        <section v-else class="relative z-20 -mt-20 h-[calc(100vh-64px)]">
            <!-- Map implementation placeholder -->
            <div class="absolute inset-0 bg-gray-100">
                <!-- Venue list overlay -->
                <div class="absolute left-4 top-4 max-w-sm w-full bg-white rounded-xl shadow-lg p-4
                            max-h-[calc(100vh-120px)] overflow-y-auto dark:bg-gray-800">
                    <!-- ... venue list items ... -->
                </div>
            </div>
        </section>

        <!-- Filter Dialog -->
        <TransitionRoot appear :show="showFilters" as="template">
            <Dialog as="div" @close="showFilters = false" class="relative z-50">
                <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" aria-hidden="true" />

                <div class="fixed inset-0 overflow-y-auto">
                    <div class="flex min-h-full items-center justify-center p-4">
                        <DialogPanel class="w-full max-w-md rounded-2xl bg-white p-6 dark:bg-gray-800">
                            <DialogTitle class="text-lg font-medium text-gray-900 dark:text-white">
                                Filter Pencarian
                            </DialogTitle>

                            <!-- Price Range -->
                            <div class="mt-4">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Range Harga
                                </label>
                                <div class="mt-2 space-y-4">
                                    <!-- Min Price -->
                                    <div>
                                        <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                            <span>Minimum</span>
                                            <span>{{ formatPrice(priceRange.min) }}</span>
                                        </div>
                                        <input
                                            type="range"
                                            v-model="priceRange.min"
                                            :min="0"
                                            :max="1000000"
                                            step="50000"
                                            class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700 accent-appGreenLight"
                                        />
                                    </div>

                                    <!-- Max Price -->
                                    <div>
                                        <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                            <span>Maximum</span>
                                            <span>{{ formatPrice(priceRange.max) }}</span>
                                        </div>
                                        <input
                                            type="range"
                                            v-model="priceRange.max"
                                            :min="0"
                                            :max="1000000"
                                            step="50000"
                                            class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700 accent-appGreenLight"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Other filters -->
                            <!-- ... -->

                            <div class="mt-6 flex justify-end gap-3">
                                <button
                                    @click="showFilters = false"
                                    class="rounded-lg px-4 py-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700"
                                >
                                    Batal
                                </button>
                                <button
                                    @click="applyFilters"
                                    class="rounded-lg bg-appGreenLight px-4 py-2 text-white hover:bg-appGreenMedium"
                                >
                                    Terapkan Filter
                                </button>
                            </div>
                        </DialogPanel>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- Empty state when no venues found -->
        <div v-if="venues.length === 0" class="flex flex-col items-center justify-center py-12">
            <img src="/images/empty-state.svg" alt="No venues found" class="mb-6 h-48 w-48" />
            <h3 class="mb-2 text-xl font-semibold text-gray-900 dark:text-white">
                Tidak Ada Lapangan Ditemukan
            </h3>
            <p class="text-gray-600 dark:text-gray-400">
                Coba ubah filter atau kata kunci pencarian Anda
            </p>
        </div>

        <!-- Loading skeleton -->
        <div v-if="isLoading" class="grid gap-8 md:grid-cols-3">
            <div v-for="n in 6" :key="n" class="animate-pulse rounded-3xl bg-gray-200 dark:bg-gray-700">
                <div class="aspect-[4/3] rounded-t-3xl bg-gray-300 dark:bg-gray-600"></div>
                <div class="p-6 space-y-3">
                    <div class="h-6 w-2/3 rounded bg-gray-300 dark:bg-gray-600"></div>
                    <div class="h-4 w-1/2 rounded bg-gray-300 dark:bg-gray-600"></div>
                    <div class="flex justify-between">
                        <div class="h-6 w-1/3 rounded bg-gray-300 dark:bg-gray-600"></div>
                        <div class="h-10 w-24 rounded-full bg-gray-300 dark:bg-gray-600"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Error message display -->
        <div v-if="error" class="p-4 rounded-lg bg-red-50 text-red-600 dark:bg-red-900/50 dark:text-red-400">
            {{ error }}
            <button @click="fetchVenues" class="underline ml-2">Coba lagi</button>
        </div>
    </Layout>
</template>
