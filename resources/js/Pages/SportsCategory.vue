<script setup>
import { ref, computed, onMounted, watch, onUnmounted } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {
    MapPin,
    Star,
    Trophy,
    Timer,
    Shield,
    Search,
    Filter,
    Heart,
    Share2,
    ArrowUpRight,
    ChevronDown,
    Dumbbell,      // For gym/fitness
    Target,        // For precision sports
    CircleDot,     // For ball sports
    Activity,      // For active sports
    Map,        // For map view toggle
    LayoutGrid, // For grid view toggle
    Clock,      // For operating hours
    Info,        // For additional info
    Loader2,      // Add this
    X,            // Add this for close button
} from 'lucide-vue-next';
import {
    TransitionRoot,
    Dialog,
    DialogPanel,
    DialogTitle,
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    TransitionChild
} from '@headlessui/vue';

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

// Add icon mapping
const categoryIcons = {
    'Futsal': CircleDot,      // Ball sports
    'Badminton': Target,      // Precision sports
    'Basket': CircleDot,      // Ball sports
    'Tenis': Activity,        // Active sports
};

// Add these for infinite scroll
const page = ref(1);
const perPage = 6;
const hasMore = ref(true);
const isLoadingMore = ref(false);

// Modify categories to start empty
const categories = ref([]);

// Initial data loading
const loadCategories = async (pageNumber = 1) => {
    if (pageNumber === 1) {
        isLoading.value = true;
    } else {
        isLoadingMore.value = true;
    }

    // Simulate API call with delay
    await new Promise(resolve => setTimeout(resolve, 1000));

    // Generate 6 items per page
    const newCategories = Array.from({ length: 6 }, (_, index) => ({
        id: (pageNumber - 1) * 6 + index + 1,
        name: ['Futsal', 'Badminton', 'Basket', 'Tenis'][index % 4],
        image: '/images/categories/futsal.jpg',
        venueCount: Math.floor(Math.random() * 100) + 50,
        description: 'Lapangan berkualitas untuk olahraga favorit Anda',
        popularLocations: ['Jakarta Selatan', 'Jakarta Pusat', 'Tangerang'],
        rating: (4 + Math.random()).toFixed(1),
        reviews: Math.floor(Math.random() * 500),
        isFavorite: false,
        openHours: '06:00 - 23:00',
        facilities: ['Parkir Luas', 'Toilet', 'Musholla'],
        priceRange: 'Rp 150.000 - Rp 300.000',
    }));

    if (pageNumber === 1) {
        categories.value = newCategories;
    } else {
        categories.value = [...categories.value, ...newCategories];
    }

    // Simulate end of data after 5 pages (30 items total)
    hasMore.value = pageNumber < 5;
    isLoading.value = false;
    isLoadingMore.value = false;
};

// Infinite scroll handler
const handleScroll = debounce(async () => {
    if (!hasMore.value || isLoadingMore.value) return;

    const scrollPosition = window.innerHeight + window.pageYOffset;
    const bottomOfPage = document.documentElement.offsetHeight - 100;

    if (scrollPosition >= bottomOfPage) {
        page.value++;
        await loadCategories(page.value);
    }
}, 100);

// Setup scroll listener
onMounted(() => {
    loadCategories();
    window.addEventListener('scroll', handleScroll);
});

// Cleanup
onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

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

// Add function to handle popular times
const getPopularityLevel = (hour) => {
    const now = new Date();
    const currentHour = now.getHours();
    const isWeekend = now.getDay() === 0 || now.getDay() === 6;

    return {
        level: isWeekend ? 'high' : 'medium',
        text: hour === currentHour ? 'Ramai saat ini' : 'Biasanya ramai'
    };
};

// Add view state
const viewMode = ref('grid'); // 'grid' or 'map'

// Add map related state
const mapCenter = ref([106.8456, -6.2088]); // Jakarta coordinates
const mapZoom = ref(12);
const selectedLocation = ref(null);

// Function to toggle view mode
const toggleViewMode = () => {
    viewMode.value = viewMode.value === 'grid' ? 'map' : 'grid';
};

// Expanded card state
const expandedCard = ref(null);

// Toggle card expansion
const toggleCardExpansion = (categoryId) => {
    expandedCard.value = expandedCard.value === categoryId ? null : categoryId;
};

// Add new refs for enhanced filtering
const filters = ref({
    venueCount: 'all',
    rating: null,
    facilities: []
});

const facilities = [
    'Parkir Luas',
    'Toilet',
    'Musholla',
    'Kantin',
    'Wifi',
    'Loker'
];

// Filter methods
const toggleFacilityFilter = (facility) => {
    const index = filters.value.facilities.indexOf(facility);
    if (index === -1) {
        filters.value.facilities.push(facility);
    } else {
        filters.value.facilities.splice(index, 1);
    }
};

const resetFilters = () => {
    filters.value = {
        venueCount: 'all',
        rating: null,
        facilities: []
    };
};

const applyFilters = () => {
    // Apply filters to categories
    showFilters.value = false;
};

// Enhanced search with debounce
const debouncedSearch = debounce((query) => {
    if (!query.trim()) {
        searchResults.value = [];
        return;
    }
    isSearching.value = true;
    setTimeout(() => {
        searchResults.value = categories.value.filter(c =>
            c.name.toLowerCase().includes(query.toLowerCase()) ||
            c.description.toLowerCase().includes(query.toLowerCase())
        );
        isSearching.value = false;
    }, 300);
}, 300);
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

                    <!-- Enhanced Search and Filter Section -->
                    <div class="mx-auto max-w-2xl relative">
                        <!-- Search Bar with Dropdown -->
                        <div class="flex overflow-hidden rounded-full bg-white/95 shadow-lg backdrop-blur-sm transition-all duration-300 focus-within:shadow-2xl">
                            <!-- Search Section -->
                            <div class="relative flex-1">
                                <div class="flex items-center px-4">
                                    <Search class="h-5 w-5 text-gray-400" />
                                    <input
                                        v-model="searchQuery"
                                        @input="debouncedSearch(searchQuery)"
                                        type="text"
                                        placeholder="Cari kategori olahraga..."
                                        class="w-full border-0 px-4 py-4 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-0 bg-transparent"
                                    />
                                    <Transition name="fade">
                                        <Loader2 v-if="isSearching" class="h-5 w-5 animate-spin text-gray-400" />
                                        <button v-else-if="searchQuery" @click="searchQuery = ''"
                                            class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                                            <X class="h-5 w-5 text-gray-400" />
                                        </button>
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
                                    <div v-if="searchQuery && filteredCategories.length > 0"
                                        class="absolute left-0 right-0 mt-2 max-h-60 overflow-y-auto rounded-xl bg-white py-1 shadow-lg dark:bg-gray-800">
                                        <div v-for="category in filteredCategories" :key="category.id"
                                            @click="navigateToVenues(category.id)"
                                            class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                            <component :is="categoryIcons[category.name]"
                                                class="h-10 w-10 p-2 rounded-lg bg-gray-100 text-appGreenLight" />
                                            <div>
                                                <h4 class="font-medium text-gray-900 dark:text-white">{{ category.name }}</h4>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ category.venueCount }}+ Venue
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </Transition>
                            </div>

                            <!-- Filter Button -->
                            <button @click="showFilters = true"
                                class="border-l border-gray-200 px-6 text-gray-600 hover:bg-gray-50 transition-colors flex items-center gap-2">
                                <Filter class="h-5 w-5" />
                                Filter
                            </button>
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

                    <!-- Filter Dialog -->
                    <TransitionRoot appear :show="showFilters" as="template">
                        <Dialog as="div" @close="showFilters = false" class="relative z-50">
                            <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" aria-hidden="true" />

                            <div class="fixed inset-0 overflow-y-auto">
                                <div class="flex min-h-full items-center justify-center p-4">
                                    <DialogPanel class="w-full max-w-md rounded-2xl bg-white p-6 dark:bg-gray-800">
                                        <DialogTitle class="text-lg font-medium text-gray-900 dark:text-white">
                                            Filter Kategori
                                        </DialogTitle>

                                        <!-- Filter Options -->
                                        <div class="mt-4 space-y-4">
                                            <!-- Venue Count Range -->
                                            <div>
                                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Jumlah Venue
                                                </label>
                                                <select v-model="filters.venueCount"
                                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-appGreenLight focus:ring-appGreenLight">
                                                    <option value="all">Semua</option>
                                                    <option value="50+">50+ Venue</option>
                                                    <option value="100+">100+ Venue</option>
                                                    <option value="200+">200+ Venue</option>
                                                </select>
                                            </div>

                                            <!-- Rating Filter -->
                                            <div>
                                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Rating Minimum
                                                </label>
                                                <div class="mt-2 flex items-center gap-2">
                                                    <template v-for="rating in [4, 4.2, 4.5, 4.8]" :key="rating">
                                                        <button
                                                            @click="filters.rating = rating"
                                                            :class="[
                                                                'px-3 py-1 rounded-full text-sm transition-colors',
                                                                filters.rating === rating
                                                                    ? 'bg-appGreenLight text-white'
                                                                    : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                                                            ]"
                                                        >
                                                            {{ rating }}+
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>

                                            <!-- Facility Requirements -->
                                            <div>
                                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Fasilitas
                                                </label>
                                                <div class="mt-2 flex flex-wrap gap-2">
                                                    <button v-for="facility in facilities" :key="facility"
                                                        @click="toggleFacilityFilter(facility)"
                                                        :class="[
                                                            'px-3 py-1 rounded-full text-sm transition-colors',
                                                            filters.facilities.includes(facility)
                                                                ? 'bg-appGreenLight text-white'
                                                                : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                                                        ]"
                                                    >
                                                        {{ facility }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="mt-6 flex justify-end gap-3">
                                            <button @click="resetFilters"
                                                class="rounded-lg px-4 py-2 text-gray-600 hover:bg-gray-100">
                                                Reset
                                            </button>
                                            <button @click="applyFilters"
                                                class="rounded-lg bg-appGreenLight px-4 py-2 text-white hover:bg-appGreenMedium">
                                                Terapkan Filter
                                            </button>
                                        </div>
                                    </DialogPanel>
                                </div>
                            </div>
                        </Dialog>
                    </TransitionRoot>
                </div>
            </div>
        </section>

        <!-- View Mode Toggle -->
        <div class="fixed right-4 bottom-4 z-30">
            <button @click="toggleViewMode"
                class="flex items-center gap-2 rounded-full bg-white px-4 py-2 shadow-lg hover:bg-gray-50 transition-colors"
            >
                <component :is="viewMode === 'grid' ? Map : LayoutGrid" class="h-5 w-5" />
                <span class="text-sm font-medium">
                    {{ viewMode === 'grid' ? 'Lihat Peta' : 'Lihat Grid' }}
                </span>
            </button>
        </div>

        <!-- Grid View -->
        <section v-if="viewMode === 'grid'"
            class="relative z-20 bg-gray-50 dark:bg-gray-900 -mt-20 pb-20 px-4 sm:px-6 lg:px-8">
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

                <!-- Enhanced Loading Skeleton -->
                <TransitionRoot appear :show="isLoading" as="div"
                    class="grid gap-4 sm:gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <div v-for="n in perPage" :key="n"
                        class="group relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800 animate-pulse"
                    >
                        <!-- Skeleton Image -->
                        <div class="relative aspect-[4/3] bg-gray-200 dark:bg-gray-700"></div>

                        <!-- Skeleton Content -->
                        <div class="p-4 space-y-3">
                            <!-- Title and Icon -->
                            <div class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-full bg-gray-200 dark:bg-gray-700"></div>
                                <div class="h-6 w-1/2 rounded-lg bg-gray-200 dark:bg-gray-700"></div>
                            </div>

                            <!-- Description -->
                            <div class="space-y-2">
                                <div class="h-4 w-3/4 rounded bg-gray-200 dark:bg-gray-700"></div>
                                <div class="h-4 w-full rounded bg-gray-200 dark:bg-gray-700"></div>
                            </div>

                            <!-- Stats -->
                            <div class="flex gap-4">
                                <div class="h-4 w-24 rounded bg-gray-200 dark:bg-gray-700"></div>
                                <div class="h-4 w-24 rounded bg-gray-200 dark:bg-gray-700"></div>
                            </div>

                            <!-- Button -->
                            <div class="h-10 w-full rounded-xl bg-gray-200 dark:bg-gray-700"></div>
                        </div>
                    </div>
                </TransitionRoot>

                <!-- Categories Grid with Infinite Scroll -->
                <TransitionRoot appear :show="!isLoading" as="div"
                    class="grid gap-4 sm:gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
                    enter="transition-opacity duration-500"
                    enter-from="opacity-0"
                    enter-to="opacity-100">
                    <div v-for="category in filteredCategories"
                        :key="category.id"
                        class="category-card group relative overflow-hidden rounded-2xl bg-white shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-xl dark:bg-gray-800"
                    >
                        <!-- Basic Card Content -->
                        <div class="relative aspect-[4/3] overflow-hidden">
                            <img :src="category.image" :alt="category.name"
                                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" />

                            <!-- Overlay with Quick Actions -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="absolute bottom-3 left-3 right-3 flex justify-between items-center">
                                    <button @click.stop="toggleFavorite(category)"
                                        :id="`fav-${category.id}`"
                                        class="rounded-full bg-white/90 p-1.5 text-gray-900 hover:bg-white transition-all duration-300">
                                        <Heart :class="['h-4 w-4 transition-colors', category.isFavorite ? 'fill-red-500 text-red-500' : 'text-gray-700']" />
                                    </button>
                                    <div class="flex gap-2">
                                        <Star class="h-4 w-4 text-yellow-400 fill-yellow-400" />
                                        <span class="text-sm font-medium text-white">{{ category.rating }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            <div class="absolute right-3 top-3 rounded-full bg-appGreenLight/90 px-2.5 py-0.5 text-xs font-medium text-white backdrop-blur-sm">
                                <div class="flex items-center gap-1">
                                    <div class="h-1.5 w-1.5 rounded-full bg-white animate-pulse"></div>
                                    Buka
                                </div>
                            </div>
                        </div>

                        <div class="p-4">
                            <div class="flex items-center gap-3 mb-3">
                                <component :is="categoryIcons[category.name]"
                                    class="h-6 w-6 text-appGreenLight" />
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                    {{ category.name }}
                                </h3>
                            </div>

                            <!-- Quick Stats -->
                            <div class="flex items-center gap-4 text-sm mb-3">
                                <div class="flex items-center gap-1">
                                    <Trophy class="h-4 w-4 text-appGreenLight" />
                                    <span class="text-gray-600 dark:text-gray-300">{{ category.venueCount }}+ Venue</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <Timer class="h-4 w-4 text-appGreenLight" />
                                    <span class="text-gray-600 dark:text-gray-300">{{ category.openHours }}</span>
                                </div>
                            </div>

                            <!-- Expandable Details Section -->
                            <Disclosure v-slot="{ open }" :defaultOpen="expandedCard === category.id">
                                <DisclosureButton
                                    @click="toggleCardExpansion(category.id)"
                                    class="flex w-full items-center justify-between text-sm text-gray-500 hover:text-gray-700 py-2 mt-2 transition-colors duration-200"
                                >
                                    <span class="flex items-center gap-1">
                                        <Info class="h-4 w-4" />
                                        Detail Lainnya
                                    </span>
                                    <ChevronDown
                                        :class="[
                                            'w-4 h-4 transform transition-transform duration-300 ease-in-out',
                                            open ? 'rotate-180' : ''
                                        ]"
                                    />
                                </DisclosureButton>

                                <transition
                                    enter-active-class="transition duration-300 ease-out"
                                    enter-from-class="transform scale-95 opacity-0"
                                    enter-to-class="transform scale-100 opacity-100"
                                    leave-active-class="transition duration-200 ease-in"
                                    leave-from-class="transform scale-100 opacity-100"
                                    leave-to-class="transform scale-95 opacity-0"
                                >
                                    <DisclosurePanel class="text-sm text-gray-600 space-y-4 pt-2">
                                        <!-- Operating Hours -->
                                        <transition
                                            enter-active-class="transition-all duration-300 delay-[0ms]"
                                            enter-from-class="opacity-0 translate-y-2"
                                            enter-to-class="opacity-100 translate-y-0"
                                        >
                                            <div v-if="open" class="space-y-2">
                                                <h4 class="font-medium flex items-center gap-1">
                                                    <Clock class="h-4 w-4" />
                                                    Jam Operasional
                                                </h4>
                                                <div class="grid grid-cols-2 gap-2 text-sm">
                                                    <div>
                                                        <span class="text-gray-500">Senin - Jumat:</span>
                                                        <p>06:00 - 23:00</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Sabtu - Minggu:</span>
                                                        <p>06:00 - 22:00</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </transition>

                                        <!-- Facilities -->
                                        <transition
                                            enter-active-class="transition-all duration-300 delay-[100ms]"
                                            enter-from-class="opacity-0 translate-y-2"
                                            enter-to-class="opacity-100 translate-y-0"
                                        >
                                            <div v-if="open" class="space-y-2">
                                                <h4 class="font-medium">Fasilitas</h4>
                                                <div class="flex flex-wrap gap-2">
                                                    <span v-for="facility in category.facilities"
                                                        :key="facility"
                                                        class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs"
                                                    >
                                                        {{ facility }}
                                                    </span>
                                                </div>
                                            </div>
                                        </transition>

                                        <!-- Popular Times -->
                                        <transition
                                            enter-active-class="transition-all duration-300 delay-[200ms]"
                                            enter-from-class="opacity-0 translate-y-2"
                                            enter-to-class="opacity-100 translate-y-0"
                                        >
                                            <div v-if="open" class="space-y-2">
                                                <h4 class="font-medium">Jam Ramai</h4>
                                                <div class="flex flex-wrap gap-1">
                                                    <div v-for="hour in ['18:00', '19:00', '20:00']"
                                                        :key="hour"
                                                        class="px-2 py-0.5 bg-gray-100 rounded-full text-xs"
                                                    >
                                                        {{ hour }}
                                                    </div>
                                                </div>
                                            </div>
                                        </transition>

                                        <!-- Location -->
                                        <transition
                                            enter-active-class="transition-all duration-300 delay-[300ms]"
                                            enter-from-class="opacity-0 translate-y-2"
                                            enter-to-class="opacity-100 translate-y-0"
                                        >
                                            <div v-if="open" class="space-y-2">
                                                <h4 class="font-medium flex items-center gap-1">
                                                    <MapPin class="h-4 w-4" />
                                                    Lokasi Populer
                                                </h4>
                                                <div class="flex flex-wrap gap-2">
                                                    <span v-for="location in category.popularLocations"
                                                        :key="location"
                                                        class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs"
                                                    >
                                                        {{ location }}
                                                    </span>
                                                </div>
                                            </div>
                                        </transition>
                                    </DisclosurePanel>
                                </transition>
                            </Disclosure>

                            <!-- Action Button -->
                            <button @click="navigateToVenues(category.id)"
                                class="mt-4 w-full rounded-xl bg-appGreenLight py-2 text-sm font-semibold text-white transition-colors hover:bg-appGreenMedium flex items-center justify-center gap-1"
                            >
                                Lihat Venue
                                <ArrowUpRight class="h-4 w-4" />
                            </button>
                        </div>
                    </div>
                </TransitionRoot>

                <!-- Loading More Indicator -->
                <div v-if="isLoadingMore"
                    class="flex justify-center items-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-appGreenLight"></div>
                </div>

                <!-- End of Content Message -->
                <div v-if="!hasMore && !isLoading && categories.length > 0"
                    class="text-center py-8 text-gray-600 dark:text-gray-400">
                    Semua kategori telah ditampilkan
                </div>

                <!-- Add this at the bottom of the categories grid -->
                <div v-if="hasMore && !isLoadingMore && !isLoading"
                    class="col-span-full flex justify-center py-8">
                    <button
                        @click="loadCategories(page + 1)"
                        class="px-6 py-2 bg-appGreenLight text-white rounded-full hover:bg-appGreenMedium transition-colors duration-200 flex items-center gap-2"
                    >
                        Muat Lebih Banyak
                        <ChevronDown class="h-4 w-4" />
                    </button>
                </div>
            </div>
        </section>

        <!-- Map View -->
        <section v-else class="relative z-20 -mt-20 h-[calc(100vh-64px)]">
            <!-- You'll need to implement your preferred map component here -->
            <div class="absolute inset-0 bg-gray-100">
                <div class="p-4 text-center">
                    <!-- Placeholder for map implementation -->
                    <p class="text-gray-600">
                        Implementasi peta akan ditampilkan di sini.
                        Anda dapat menggunakan Google Maps, Mapbox, atau layanan peta lainnya.
                    </p>
                </div>
            </div>

            <!-- Category List Overlay -->
            <div class="absolute left-4 top-4 max-w-sm w-full bg-white rounded-xl shadow-lg p-4 max-h-[calc(100vh-120px)] overflow-y-auto">
                <div class="space-y-4">
                    <div v-for="category in filteredCategories"
                        :key="category.id"
                        class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg cursor-pointer"
                        @click="selectedLocation = category"
                    >
                        <img :src="category.image" :alt="category.name"
                            class="w-16 h-16 rounded-lg object-cover" />
                        <div>
                            <h3 class="font-medium">{{ category.name }}</h3>
                            <p class="text-sm text-gray-600">{{ category.venueCount }}+ Venue</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </Layout>
</template>
