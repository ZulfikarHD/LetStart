<template>
    <AuthenticatedLayout>
        <div
            class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 px-4 sm:px-6 lg:px-8"
        >
            <div class="max-w-7xl mx-auto">
                <!-- Header with Search -->
                <div class="mb-8">
                    <h1
                        class="text-3xl font-bold text-appBlack dark:text-white"
                    >
                        Pemesanan Saya
                    </h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Kelola semua pemesanan venue olahraga Anda di sini
                    </p>

                    <!-- Search and Filter Section -->
                    <div class="mt-4 flex flex-col sm:flex-row gap-4">
                        <div class="relative flex-1">
                            <LucideSearch
                                class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400"
                            />
                            <input
                                type="text"
                                v-model="searchQuery"
                                @focus="showRecentSearches = true"
                                placeholder="Cari pemesanan..."
                                class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-appGreenLight"
                            />
                            <!-- Recent Searches Dropdown -->
                            <div v-if="showRecentSearches && recentSearches.length"
                                class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
                                <div class="p-2">
                                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Pencarian Terakhir</div>
                                    <div v-for="search in recentSearches" :key="search"
                                        class="flex items-center justify-between p-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg cursor-pointer"
                                        @click="searchQuery = search">
                                        <span>{{ search }}</span>
                                        <LucideClock class="h-4 w-4 text-gray-400" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Date Range Picker -->
                        <Popover class="relative">
                            <PopoverButton
                                class="flex items-center px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700"
                            >
                                <LucideCalendar
                                    class="h-5 w-5 mr-2 text-gray-500"
                                />
                                <span>Filter Tanggal</span>
                            </PopoverButton>
                            <PopoverPanel
                                class="absolute z-10 mt-2 w-screen max-w-sm px-4 sm:px-0"
                            >
                                <!-- Add your date picker content here -->
                                <div
                                    class="overflow-hidden rounded-lg shadow-lg ring-1 ring-black ring-opacity-5"
                                >
                                    <div
                                        class="relative bg-white dark:bg-gray-800 p-4"
                                    >
                                        <!-- Date picker implementation goes here -->
                                    </div>
                                </div>
                            </PopoverPanel>
                        </Popover>

                        <!-- Add sorting dropdown -->
                        <select v-model="sortBy" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800">
                            <option value="date-desc">Terbaru</option>
                            <option value="date-asc">Terlama</option>
                            <option value="price-desc">Harga Tertinggi</option>
                            <option value="price-asc">Harga Terendah</option>
                        </select>
                    </div>
                </div>

                <!-- Enhanced Tabs with Counter -->
                <div class="mb-6">
                    <nav class="flex flex-wrap gap-2" aria-label="Tabs">
                        <button
                            v-for="tab in tabs"
                            :key="tab.name"
                            @click="currentTab = tab.id"
                            :class="[
                                currentTab === tab.id
                                    ? 'bg-appGreenLight text-white ring-2 ring-offset-2 ring-appGreenLight'
                                    : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white',
                                'px-4 py-2 rounded-lg font-medium text-sm transition duration-150 ease-in-out flex items-center gap-2',
                            ]"
                        >
                            {{ tab.name }}
                            <span
                                class="px-2 py-0.5 text-xs rounded-full bg-white/20"
                            >
                                {{ getBookingCount(tab.id) }}
                            </span>
                        </button>
                    </nav>
                </div>

                <!-- Loading State -->
                <div v-if="loading" class="space-y-4">
                    <div v-for="n in 3" :key="n" class="animate-pulse">
                        <div
                            class="h-40 bg-gray-200 dark:bg-gray-700 rounded-lg"
                        ></div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="filteredBookings.length === 0" class="text-center py-12">
                    <div class="max-w-sm mx-auto">
                        <img src="/images/empty-bookings.svg" alt="No bookings" class="w-48 h-48 mx-auto mb-6" />
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ getEmptyStateMessage() }}
                        </h3>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">
                            {{ getEmptyStateDescription() }}
                        </p>
                        <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
                            <Link href="/venues"
                                class="inline-flex items-center px-4 py-2 rounded-lg bg-appGreenLight text-white hover:bg-appGreenMedium transition-colors">
                                <LucideSearch class="h-4 w-4 mr-2" />
                                Cari Venue
                            </Link>
                            <button @click="showTutorial"
                                class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600">
                                <LucidePlay class="h-4 w-4 mr-2" />
                                Lihat Tutorial
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Booking Cards List -->
                <div v-else class="space-y-6">
                    <TransitionGroup
                        name="booking-list"
                        appear
                        class="space-y-6"
                        tag="div"
                    >
                        <div
                            v-for="booking in filteredBookings"
                            :key="booking.id"
                            class="group relative bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 hover:shadow-md transition-all duration-300 ease-in-out border-l-4"
                            :class="getStatusBorderColor(booking.status)"
                        >
                            <!-- Countdown for upcoming bookings -->
                            <div
                                v-if="booking.status === 'active'"
                                class="mb-3"
                            >
                                <div
                                    class="text-sm text-appGreenMedium dark:text-appGreenLight"
                                >
                                    <CountdownTimer
                                        :date="booking.date"
                                        :startTime="booking.start_time"
                                    />
                                </div>
                            </div>

                            <!-- Booking Main Info -->
                            <div
                                class="flex flex-col sm:flex-row justify-between gap-4"
                            >
                                <div class="flex-1">
                                    <h3
                                        class="text-lg font-semibold text-appBlack dark:text-white"
                                    >
                                        {{ booking.venue_name }}
                                    </h3>

                                    <!-- Date and Time -->
                                    <div
                                        class="mt-2 flex items-center text-gray-600 dark:text-gray-300"
                                    >
                                        <LucideCalendar class="h-4 w-4 mr-2" />
                                        <span>{{
                                            formatDate(booking.date)
                                        }}</span>
                                    </div>

                                    <div
                                        class="mt-1 flex items-center text-gray-600 dark:text-gray-300"
                                    >
                                        <LucideClock class="h-4 w-4 mr-2" />
                                        <span
                                            >{{ booking.start_time }} -
                                            {{ booking.end_time }}</span
                                        >
                                    </div>

                                    <!-- Location -->
                                    <div
                                        class="mt-1 flex items-center text-gray-600 dark:text-gray-300"
                                    >
                                        <LucideMapPin class="h-4 w-4 mr-2" />
                                        <span>{{
                                            booking.venue_location
                                        }}</span>
                                    </div>

                                    <!-- Price -->
                                    <div
                                        class="mt-3 text-lg font-medium text-appGreenDark dark:text-appGreenLight"
                                    >
                                        Rp
                                        {{ formatPrice(booking.total_price) }}
                                    </div>
                                </div>

                                <!-- Status Badge -->
                                <div
                                    class="flex flex-col items-end justify-between"
                                >
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                        :class="statusColors[booking.status]"
                                    >
                                        {{
                                            booking.status === "active"
                                                ? "Aktif"
                                                : booking.status === "completed"
                                                ? "Selesai"
                                                : "Dibatalkan"
                                        }}
                                    </span>
                                </div>
                            </div>

                            <!-- Quick Actions Menu -->

                            <div class="mt-4 flex justify-end">
                                <button
                                    v-if="booking.status === 'active'"
                                    @click="showBookingDetail(booking)"
                                    class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 bg-appGreenLight/10 text-appGreenDark hover:bg-appGreenLight hover:text-white dark:bg-appGreenLight/20 dark:text-appGreenLight dark:hover:bg-appGreenLight dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-appGreenLight"
                                >
                                    <LucideTicket class="h-4 w-4 mr-2" />
                                    Lihat Detail
                                </button>
                            </div>

                            <!-- Add swipe actions for mobile -->
                            <div
                                v-touch:swipe.left="() => showQuickActions(booking)"
                                v-touch:swipe.right="() => hideQuickActions(booking)"
                                class="relative overflow-hidden"
                            >
                                <!-- Add quick action buttons that appear on swipe -->
                                <div class="absolute right-0 top-0 h-full flex items-center gap-2 px-4">
                                    <button class="p-2 bg-appGreenLight rounded-full">
                                        <LucideShare2 class="h-5 w-5 text-white" />
                                    </button>
                                    <button class="p-2 bg-red-500 rounded-full">
                                        <LucideTrash class="h-5 w-5 text-white" />
                                    </button>
                                </div>
                            </div>

                            <!-- Add helpful tooltips -->
                            <div class="relative group">
                                <button class="p-1 hover:bg-gray-100 rounded-full">
                                    <LucideInfo class="h-4 w-4 text-gray-400" />
                                </button>
                                <div class="absolute hidden group-hover:block w-64 p-2 bg-gray-800 text-white text-sm rounded-lg -top-2 left-full ml-2">
                                    Pembatalan dapat dilakukan maksimal 24 jam sebelum jadwal bermain
                                </div>
                            </div>
                        </div>
                    </TransitionGroup>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

    <Dialog
        :open="isDetailOpen"
        @close="isDetailOpen = false"
        class="relative z-50"
    >
        <div
            class="fixed inset-0 bg-black/30 backdrop-blur-sm"
            aria-hidden="true"
        />

        <div class="fixed inset-0 flex items-center justify-center p-4">
            <DialogPanel
                class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 p-6 text-left align-middle shadow-xl transition-all"
            >
                <!-- Ticket Header -->
                <div class="relative">
                    <div class="absolute top-0 right-0">
                        <button
                            @click="isDetailOpen = false"
                            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full"
                        >
                            <LucideX class="h-5 w-5 text-gray-500" />
                        </button>
                    </div>

                    <h3
                        class="text-lg font-semibold text-appBlack dark:text-white mb-4"
                    >
                        Detail Pemesanan
                    </h3>
                </div>

                <!-- Progress Tracker -->
                <div class="mb-6 relative">
                    <div class="flex justify-between items-center">
                        <div v-for="(step, index) in bookingSteps" :key="step.id"
                            class="flex flex-col items-center relative z-10">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center"
                                :class="[
                                    currentStep > index ? 'bg-appGreenLight' : 'bg-gray-200',
                                    'transition-colors duration-200'
                                ]">
                                <component :is="step.icon" class="h-4 w-4 text-white" />
                            </div>
                            <div class="text-xs mt-2 text-center">{{ step.label }}</div>
                        </div>
                    </div>
                    <!-- Progress Line -->
                    <div class="absolute top-4 left-0 h-0.5 bg-gray-200 w-full -z-10">
                        <div class="h-full bg-appGreenLight transition-all duration-500"
                            :style="{ width: `${(currentStep - 1) * 33.33}%` }" />
                    </div>
                </div>

                <!-- Ticket Content -->
                <div class="space-y-4">
                    <!-- Venue Info -->
                    <div class="flex items-start space-x-3">
                        <div class="p-2 bg-appGreenLight/10 rounded-lg">
                            <LucideMapPin class="h-5 w-5 text-appGreenLight" />
                        </div>
                        <div>
                            <h4
                                class="font-medium text-appBlack dark:text-white"
                            >
                                {{ selectedBooking?.venue_name }}
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ selectedBooking?.venue_location }}
                            </p>
                        </div>
                    </div>

                    <!-- Date & Time -->
                    <div class="flex items-start space-x-3">
                        <div class="p-2 bg-appGreenLight/10 rounded-lg">
                            <LucideCalendar
                                class="h-5 w-5 text-appGreenLight"
                            />
                        </div>
                        <div>
                            <h4
                                class="font-medium text-appBlack dark:text-white"
                            >
                                {{ formatDate(selectedBooking?.date) }}
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ selectedBooking?.start_time }} -
                                {{ selectedBooking?.end_time }}
                            </p>
                        </div>
                    </div>

                    <!-- Booking ID -->
                    <div class="flex items-start space-x-3">
                        <div class="p-2 bg-appGreenLight/10 rounded-lg">
                            <LucideTicket class="h-5 w-5 text-appGreenLight" />
                        </div>
                        <div>
                            <h4
                                class="font-medium text-appBlack dark:text-white"
                            >
                                ID Pemesanan
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                #{{
                                    selectedBooking?.id
                                        .toString()
                                        .padStart(6, "0")
                                }}
                            </p>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="flex items-start space-x-3">
                        <div class="p-2 bg-appGreenLight/10 rounded-lg">
                            <LucideCreditCard
                                class="h-5 w-5 text-appGreenLight"
                            />
                        </div>
                        <div>
                            <h4
                                class="font-medium text-appBlack dark:text-white"
                            >
                                Total Pembayaran
                            </h4>
                            <p
                                class="text-lg font-semibold text-appGreenDark dark:text-appGreenLight"
                            >
                                Rp
                                {{ formatPrice(selectedBooking?.total_price) }}
                            </p>
                        </div>
                    </div>

                    <!-- QR Code Section -->
                    <div
                        class="mt-6 flex flex-col items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg"
                    >
                        <div
                            class="w-48 h-48 bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm"
                        >
                            <!-- Add QR Code component here -->
                            <img
                                :src="`https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=BOOKING-${selectedBooking?.id}`"
                                alt="QR Code"
                                class="w-full h-full"
                            />
                        </div>
                        <p
                            class="mt-3 text-sm text-gray-600 dark:text-gray-400 text-center"
                        >
                            Tunjukkan QR Code ini kepada petugas venue saat
                            check-in
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 flex gap-3">
                        <button
                            class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-appGreenLight hover:bg-appGreenMedium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-appGreenLight transition-colors"
                        >
                            <LucideShare2 class="h-4 w-4 mr-2" />
                            Bagikan
                        </button>
                        <button
                            class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-appGreenLight transition-colors"
                        >
                            <LucideDownload class="h-4 w-4 mr-2" />
                            Unduh
                        </button>
                    </div>
                </div>
            </DialogPanel>
        </div>
    </Dialog>

    <!-- Add toast notifications -->
    <TransitionGroup
        enter-active-class="transform ease-out duration-300 transition"
        enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
        leave-active-class="transition ease-in duration-100"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
        class="fixed bottom-0 right-0 z-50 p-4 space-y-4"
    >
        <!-- Toast notification content -->
    </TransitionGroup>

    <!-- Calendar View Toggle -->
    <div class="mb-6 flex justify-end">
        <button
            @click="viewMode = viewMode === 'list' ? 'calendar' : 'list'"
            class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-colors"
            :class="viewMode === 'calendar' ? 'bg-appGreenLight text-white' : 'bg-gray-100 text-gray-700'"
        >
            <LucideCalendar class="h-4 w-4 mr-2" />
            {{ viewMode === 'calendar' ? 'Tampilan List' : 'Tampilan Kalender' }}
        </button>
    </div>

    <!-- Calendar View -->
    <div v-if="viewMode === 'calendar'" class="rounded-lg border border-gray-200 dark:border-gray-700">
        <!-- Add your calendar implementation here -->
        <!-- Show bookings as events on the calendar -->
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {
    Calendar as LucideCalendar,
    Clock as LucideClock,
    MapPin as LucideMapPin,
    Search as LucideSearch,
    CalendarX as LucideCalendarX,
    X as LucideX,
    Info as LucideInfo,
    MoreVertical as LucideMoreVertical,
    Ticket as LucideTicket,
    CreditCard as LucideCreditCard,
    Share2 as LucideShare2,
    Download as LucideDownload,
    Trash as LucideTrash,
    Check as LucideCheck,
    Play as LucidePlay,
} from "lucide-vue-next";
import {
    Menu,
    MenuButton,
    MenuItems,
    Popover,
    PopoverButton,
    PopoverPanel,
    Dialog,
    DialogPanel,
} from "@headlessui/vue";
import CountdownTimer from "@/Components/CountDownTimer.vue";

const loading = ref(true);
const currentTab = ref("active");
const bookings = ref([]);
const searchQuery = ref("");
const isDetailOpen = ref(false);
const selectedBooking = ref(null);
const showRecentSearches = ref(false);
const recentSearches = ref([]);
const viewMode = ref('list');
const sortBy = ref('date-desc');
const currentStep = ref(1);
const bookingSteps = [
    { id: 1, label: 'Pemesanan', icon: LucideTicket },
    { id: 2, label: 'Pembayaran', icon: LucideCreditCard },
    { id: 3, label: 'Selesai', icon: LucideCheck }
];

const tabs = [
    { id: "active", name: "Aktif" },
    { id: "completed", name: "Selesai" },
    { id: "cancelled", name: "Dibatalkan" },
];

const statusColors = {
    active: "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300",
    completed: "bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300",
    cancelled: "bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300",
};

const filteredBookings = computed(() => {
    let filtered = bookings.value.filter(
        (booking) => booking.status === currentTab.value
    );

    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(
            (booking) =>
                booking.venue_name.toLowerCase().includes(query) ||
                booking.venue_location.toLowerCase().includes(query)
        );
    }

    // Add sorting logic
    return filtered.sort((a, b) => {
        switch (sortBy.value) {
            case 'date-desc':
                return new Date(b.date) - new Date(a.date);
            case 'date-asc':
                return new Date(a.date) - new Date(b.date);
            case 'price-desc':
                return b.total_price - a.total_price;
            case 'price-asc':
                return a.total_price - b.total_price;
            default:
                return 0;
        }
    });
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString("id-ID", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};

const formatPrice = (price) => {
    return new Intl.NumberFormat("id-ID").format(price);
};

const dummyBookings = [
    {
        id: 1,
        venue_name: "Lapangan Futsal Bintang",
        date: "2024-02-15",
        start_time: "18:00",
        end_time: "20:00",
        venue_location: "Jl. Ahmad Yani No. 123, Jakarta Selatan",
        status: "active",
        total_price: 250000,
    },
    {
        id: 2,
        venue_name: "GOR Badminton Sejahtera",
        date: "2024-02-14",
        start_time: "16:00",
        end_time: "18:00",
        venue_location: "Jl. Sudirman No. 45, Jakarta Pusat",
        status: "completed",
        total_price: 180000,
    },
    {
        id: 3,
        venue_name: "Lapangan Basket Indoor Champion",
        date: "2024-02-13",
        start_time: "19:00",
        end_time: "21:00",
        venue_location: "Jl. Gatot Subroto No. 78, Jakarta Selatan",
        status: "cancelled",
        total_price: 300000,
    },
    {
        id: 4,
        venue_name: "Lapangan Tennis Ace",
        date: "2024-02-16",
        start_time: "07:00",
        end_time: "09:00",
        venue_location: "Jl. Pemuda No. 90, Jakarta Timur",
        status: "active",
        total_price: 220000,
    },
    {
        id: 5,
        venue_name: "Kolam Renang Olympia",
        date: "2024-02-12",
        start_time: "14:00",
        end_time: "16:00",
        venue_location: "Jl. Asia Afrika No. 56, Jakarta Pusat",
        status: "completed",
        total_price: 150000,
    },
];

const fetchBookings = async () => {
    try {
        loading.value = true;
        // Simulating API call with dummy data
        await new Promise((resolve) => setTimeout(resolve, 1000));
        bookings.value = dummyBookings;
    } catch (error) {
        console.error("Error fetching bookings:", error);
    } finally {
        loading.value = false;
    }
};

const cancelBooking = async (bookingId) => {
    if (!confirm("Apakah Anda yakin ingin membatalkan pemesanan ini?")) return;

    try {
        // Simulating API call
        await new Promise((resolve) => setTimeout(resolve, 500));
        const bookingIndex = bookings.value.findIndex(
            (b) => b.id === bookingId
        );
        if (bookingIndex !== -1) {
            bookings.value[bookingIndex].status = "cancelled";
        }
    } catch (error) {
        console.error("Error cancelling booking:", error);
    }
};

const showBookingDetail = (booking) => {
    selectedBooking.value = booking;
    isDetailOpen.value = true;
};

const getBookingCount = (status) => {
    return bookings.value.filter((booking) => booking.status === status).length;
};

const getStatusBorderColor = (status) => {
    const colors = {
        active: "border-appGreenLight",
        completed: "border-appBlueMedium",
        cancelled: "border-red-500",
    };
    return colors[status];
};

// Add empty state functions
const getEmptyStateMessage = () => {
    if (searchQuery.value) {
        return 'Tidak ada hasil pencarian';
    }
    switch (currentTab.value) {
        case 'active':
            return 'Belum ada pemesanan aktif';
        case 'completed':
            return 'Belum ada pemesanan selesai';
        case 'cancelled':
            return 'Belum ada pemesanan dibatalkan';
        default:
            return 'Belum ada pemesanan';
    }
};

const getEmptyStateDescription = () => {
    if (searchQuery.value) {
        return 'Coba kata kunci lain atau ubah filter pencarian';
    }
    switch (currentTab.value) {
        case 'active':
            return 'Mulai pesan venue olahraga favoritmu sekarang';
        case 'completed':
            return 'Pemesanan yang telah selesai akan muncul di sini';
        case 'cancelled':
            return 'Pemesanan yang dibatalkan akan muncul di sini';
        default:
            return 'Mulai pesan venue olahraga favoritmu sekarang';
    }
};

// Add tutorial function
const showTutorial = () => {
    // Implement your tutorial logic here
    console.log('Show tutorial');
};

// Add quick actions functions
const showQuickActions = (booking) => {
    // Implement show quick actions logic
    console.log('Show quick actions for booking:', booking.id);
};

const hideQuickActions = (booking) => {
    // Implement hide quick actions logic
    console.log('Hide quick actions for booking:', booking.id);
};

// Add formatDateTime function
const formatDateTime = (datetime) => {
    return new Date(datetime).toLocaleString('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

onMounted(() => {
    fetchBookings();
});
</script>

<style scoped>
.booking-list-move, /* apply transition to moving elements */
.booking-list-enter-active,
.booking-list-leave-active {
    transition: all 0.5s ease;
}

.booking-list-enter-from {
    opacity: 0;
    transform: translateX(-30px);
}

.booking-list-leave-to {
    opacity: 0;
    transform: translateX(30px);
}

/* ensure leaving items are taken out of layout flow so that moving
   animations can be calculated correctly */
.booking-list-leave-active {
    position: absolute;
}
</style>
