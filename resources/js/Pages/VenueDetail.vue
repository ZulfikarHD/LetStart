<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { MapPin, Calendar, DollarSign, Star, Info, Shield, Dumbbell, ChevronRight, X } from 'lucide-vue-next';

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

// Mock data - replace with actual API data
const venue = ref({
    name: 'GOR Sahabat Sport Center',
    rating: 4.5,
    address: 'Jl. Raya Sport Center No. 123, Jakarta Selatan',
    description: 'Fasilitas olahraga modern dengan berbagai lapangan berkualitas tinggi. Dilengkapi dengan fasilitas pendukung lengkap untuk kenyamanan pengunjung.',
    images: [
        '/images/venue1.jpg',
        '/images/venue2.jpg',
        '/images/venue3.jpg'
    ],
    amenities: [
        'Parkir Luas',
        'Toilet Bersih',
        'Musholla',
        'Kantin',
        'Ruang Ganti'
    ],
    sports: ['Badminton', 'Basket', 'Futsal'],
    fields: [
        {
            id: 1,
            name: 'Lapangan Badminton 1',
            type: 'Badminton',
            image: '/images/badminton1.jpg',
            price: 100000,
            equipment: ['Net', 'Lighting'],
            available: true,
            images: [
                '/images/badminton1-1.jpg',
                '/images/badminton1-2.jpg',
                '/images/badminton1-3.jpg'
            ],
            description: 'Lapangan badminton dengan lantai vinyl kualitas internasional'
        },
        {
            id: 2,
            name: 'Lapangan Badminton 1',
            type: 'Badminton',
            image: '/images/badminton1.jpg',
            price: 100000,
            equipment: ['Net', 'Lighting'],
            available: true,
            images: [
                '/images/badminton1-1.jpg',
                '/images/badminton1-2.jpg',
                '/images/badminton1-3.jpg'
            ],
            description: 'Lapangan badminton dengan lantai vinyl kualitas internasional'
        },
        {
            id: 3,
            name: 'Lapangan Badminton 1',
            type: 'Badminton',
            image: '/images/badminton1.jpg',
            price: 100000,
            equipment: ['Net', 'Lighting'],
            available: true,
            images: [
                '/images/badminton1-1.jpg',
                '/images/badminton1-2.jpg',
                '/images/badminton1-3.jpg'
            ],
            description: 'Lapangan badminton dengan lantai vinyl kualitas internasional'
        },
        {
            id: 4,
            name: 'Lapangan Basket Indoor',
            type: 'Basket',
            image: '/images/basket1.jpg',
            price: 150000,
            equipment: ['Ring Standar', 'Lighting'],
            available: false,
            images: [
                '/images/basket1-1.jpg',
                '/images/basket1-2.jpg',
                '/images/basket1-3.jpg'
            ],
            description: 'Lapangan basket indoor dengan lantai parquette'
        }
    ]
});

const showBookingModal = ref(false);
const bookingStep = ref(1); // 1: Select Fields, 2: Select Time, 3: Review & Pay
const selectedDate = ref(new Date());
const selectedFields = ref(new Map());
const filterSport = ref(null);
const selectedFieldId = ref(null);
const showingFieldDetail = ref(false);
const currentImageIndex = ref(0);

// Simplified selection logic
const addToBooking = (field) => {
    if (!field.available) return;

    selectedFields.value.set(field.id, {
        field,
        timeSlots: []
    });
    showBookingModal.value = true;
};

// Step management
const nextStep = () => {
    if (bookingStep.value < 3) bookingStep.value++;
};

const prevStep = () => {
    if (bookingStep.value > 1) bookingStep.value--;
};

const totalPrice = computed(() => {
    return selectedFields.value.size > 0 ? Array.from(selectedFields.value.values()).reduce((total, field) => total + field.field.price * field.timeSlots.length, 0) : 0;
});

const timeSlots = [
    '08:00', '09:00', '10:00', '11:00', '12:00', '13:00',
    '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'
];

const timeSlotGroups = computed(() => {
  return timeSlots.reduce((groups, slot) => {
    const hour = parseInt(slot.split(':')[0]);
    const period = hour < 12 ? 'Pagi' : hour < 17 ? 'Siang' : 'Malam';
    if (!groups[period]) groups[period] = [];
    groups[period].push(slot);
    return groups;
  }, {});
});

const book = () => {
    // Implement booking logic
    console.log('Booking:', {
        fields: Array.from(selectedFields.value.values()),
        date: selectedDate.value,
        totalPrice: totalPrice.value
    });
};

const showNotification = (message) => {
  toastMessage.value = message;
  showToast.value = true;
  setTimeout(() => {
    showToast.value = false;
  }, 3000);
};

// Add smooth scrolling
const scrollToBooking = () => {
  const bookingSection = document.querySelector('#booking-section');
  bookingSection?.scrollIntoView({ behavior: 'smooth' });
};

const filteredFields = computed(() => {
    if (!filterSport.value) return venue.value.fields;
    return venue.value.fields.filter(field => field.type === filterSport.value);
});

// Remove if it exists
// const selectedTimeSlots = ref([]);

// Update time slot selection logic
const isTimeSlotSelected = (fieldId, slot) => {
    const fieldData = selectedFields.value.get(fieldId);
    return fieldData?.timeSlots.includes(slot) || false;
};

const selectTimeSlot = (fieldId, slot) => {
    const fieldData = selectedFields.value.get(fieldId);
    if (!fieldData) return;

    const timeSlots = fieldData.timeSlots;
    const index = timeSlots.indexOf(slot);

    if (index === -1) {
        timeSlots.push(slot);
    } else {
        timeSlots.splice(index, 1);
    }
};

const showFieldDetail = (field) => {
    selectedFieldId.value = field.id;
    currentImageIndex.value = 0;
    showingFieldDetail.value = true;
};

const closeFieldDetail = () => {
    showingFieldDetail.value = false;
    selectedFieldId.value = null;
    currentImageIndex.value = 0;
};

const nextImage = () => {
    const field = selectedFields.value.get(selectedFieldId.value)?.field;
    if (field && currentImageIndex.value < field.images.length - 1) {
        currentImageIndex.value++;
    }
};

const prevImage = () => {
    if (currentImageIndex.value > 0) {
        currentImageIndex.value--;
    }
};
</script>

<template>
    <Layout>
        <Head :title="venue.name" />

        <div class="min-h-screen bg-gray-50 py-8 dark:bg-gray-900">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Venue Images Gallery -->
                <div class="mb-8 grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div v-for="(image, index) in venue.images" :key="index"
                        class="aspect-[4/3] overflow-hidden rounded-2xl">
                        <img :src="image" :alt="`${venue.name} - Image ${index + 1}`"
                            class="h-full w-full object-cover transition-transform duration-300 hover:scale-105" />
                    </div>
                </div>

                <!-- Venue Info -->
                <div class="mb-8 grid gap-8 lg:grid-cols-3">
                    <div class="lg:col-span-2">
                        <h1 class="mb-4 text-3xl font-bold text-gray-900 dark:text-white">{{ venue.name }}</h1>

                        <div class="mb-6 flex items-center gap-4">
                            <div class="flex items-center gap-1">
                                <Star class="h-5 w-5 fill-yellow-400 text-yellow-400" />
                                <span class="font-medium text-gray-900 dark:text-white">{{ venue.rating }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                                <MapPin class="h-5 w-5" />
                                {{ venue.address }}
                            </div>
                        </div>

                        <p class="mb-6 text-gray-600 dark:text-gray-400">{{ venue.description }}</p>

                        <!-- Amenities -->
                        <div class="mb-6">
                            <h2 class="mb-4 text-xl font-semibold text-gray-900 dark:text-white">Fasilitas</h2>
                            <div class="flex flex-wrap gap-3">
                                <span v-for="amenity in venue.amenities" :key="amenity"
                                    class="rounded-full bg-gray-100 px-4 py-2 text-sm text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                    {{ amenity }}
                                </span>
                            </div>
                        </div>

                        <!-- Sports -->
                        <div class="mb-8">
                            <h2 class="mb-4 text-xl font-semibold text-gray-900 dark:text-white">Jenis Olahraga</h2>
                            <div class="flex flex-wrap gap-3">
                                <span v-for="sport in venue.sports" :key="sport"
                                    class="flex items-center gap-2 rounded-full bg-appGreenLight/10 px-4 py-2 text-sm font-medium text-appGreenDark dark:text-appGreenLight">
                                    <Dumbbell class="h-4 w-4" />
                                    {{ sport }}
                                </span>
                            </div>
                        </div>

                        <!-- Available Fields -->
                        <div>
                            <div class="mb-6 flex items-center justify-between">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Daftar Lapangan</h2>
                                <!-- Filter buttons -->
                                <div class="flex gap-2">
                                    <button v-for="sport in venue.sports" :key="sport"
                                        @click="filterSport = filterSport === sport ? null : sport"
                                        :class="[
                                            'rounded-full px-4 py-2 text-sm font-medium transition-all',
                                            filterSport === sport
                                                ? 'bg-appGreenLight text-white'
                                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300'
                                        ]">
                                        {{ sport }}
                                    </button>
                                </div>
                            </div>

                            <!-- Grid layout with responsive columns -->
                            <div class="grid gap-6 sm:grid-cols-2">
                                <div v-for="field in filteredFields" :key="field.id"
                                    @click="showFieldDetail(field)"
                                    class="group relative overflow-hidden rounded-2xl bg-white shadow-lg transition-all hover:-translate-y-1 hover:shadow-xl dark:bg-gray-800 cursor-pointer"
                                    :class="{'opacity-75': !field.available}">
                                    <!-- Unavailable overlay -->
                                    <div v-if="!field.available"
                                        class="absolute inset-0 z-10 flex items-center justify-center bg-black/40 backdrop-blur-[2px]">
                                        <span class="rounded-full bg-red-500/90 px-4 py-2 text-sm font-medium text-white">
                                            Tidak Tersedia
                                        </span>
                                    </div>

                                    <div class="aspect-[4/3] overflow-hidden">
                                        <img :src="field.image" :alt="field.name"
                                            class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-110" />
                                    </div>

                                    <div class="p-4">
                                        <div class="mb-2 flex items-center justify-between">
                                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ field.name }}</h3>
                                            <span class="flex items-center gap-1 rounded-full bg-appGreenLight/10 px-3 py-1 text-xs font-medium text-appGreenDark dark:text-appGreenLight">
                                                <Dumbbell class="h-3 w-3" />
                                                {{ field.type }}
                                            </span>
                                        </div>

                                        <p class="mb-4 line-clamp-2 text-sm text-gray-600 dark:text-gray-400">
                                            {{ field.description }}
                                        </p>

                                        <div class="mb-4 flex flex-wrap items-center gap-3">
                                            <span v-for="equipment in field.equipment" :key="equipment"
                                                class="flex items-center gap-1 rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                                <Shield class="h-3 w-3" />
                                                {{ equipment }}
                                            </span>
                                        </div>

                                        <div class="mb-4 flex items-center justify-between border-t border-gray-100 pt-4 dark:border-gray-700">
                                            <span class="text-lg font-bold text-appGreenDark dark:text-appGreenLight">
                                                Rp {{ field.price.toLocaleString() }}
                                                <span class="text-sm font-normal text-gray-500">/jam</span>
                                            </span>
                                        </div>

                                        <div class="flex gap-2">
                                            <button @click.stop="addToBooking(field)"
                                                :disabled="!field.available"
                                                class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-appGreenLight px-4 py-2.5 text-sm font-medium text-white transition-all hover:bg-appGreenMedium hover:shadow-lg disabled:cursor-not-allowed disabled:opacity-50">
                                                <Calendar class="h-4 w-4" />
                                                Pilih Lapangan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Empty state -->
                            <div v-if="filteredFields.length === 0"
                                class="flex flex-col items-center justify-center rounded-2xl bg-gray-50 py-12 dark:bg-gray-800">
                                <Info class="mb-4 h-12 w-12 text-gray-400" />
                                <p class="text-gray-600 dark:text-gray-400">Tidak ada lapangan yang tersedia</p>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Section -->
                    <div class="lg:sticky lg:top-20 lg:h-fit">
                        <div class="rounded-2xl bg-white p-6 shadow-lg dark:bg-gray-800">
                            <h2 class="mb-4 text-xl font-semibold text-gray-900 dark:text-white">Booking</h2>

                            <!-- Date Selection -->
                            <div class="mb-4">
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal</label>
                                <input type="date" v-model="selectedDate"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 p-3 dark:border-gray-700 dark:bg-gray-900" />
                            </div>

                            <!-- Field Selection -->
                            <div v-if="selectedFields.size > 0" class="mb-4">
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Lapangan Terpilih</label>
                                <!-- Iterate through selected fields -->
                                <div v-for="[fieldId, fieldData] in selectedFields" :key="fieldId"
                                    class="mb-4 rounded-xl border border-gray-200 p-4 dark:border-gray-700">
                                    <div class="mb-3 flex items-center justify-between">
                                        <span class="font-medium text-gray-900 dark:text-white">{{ fieldData.field.name }}</span>
                                        <span class="text-sm text-appGreenDark dark:text-appGreenLight">
                                            Rp {{ fieldData.field.price.toLocaleString() }}/jam
                                        </span>
                                    </div>

                                    <!-- Time Slots for this specific field -->
                                    <div class="mt-4">
                                        <div v-for="(slots, period) in timeSlotGroups" :key="period">
                                            <h4 class="mb-2 text-xs font-medium text-gray-600 dark:text-gray-400">{{ period }}</h4>
                                            <div class="mb-3 grid grid-cols-4 gap-1.5">
                                                <button
                                                    v-for="slot in slots"
                                                    :key="slot"
                                                    @click="selectTimeSlot(fieldId, slot)"
                                                    :class="[
                                                        'relative rounded-lg px-2 py-1.5 text-xs font-medium transition-all duration-200',
                                                        isTimeSlotSelected(fieldId, slot)
                                                            ? 'bg-appGreenLight text-white ring-2 ring-appGreenLight ring-offset-1'
                                                            : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300'
                                                    ]"
                                                >
                                                    {{ slot }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Selected time slots summary -->
                                    <div v-if="fieldData.timeSlots.length > 0" class="mt-4">
                                        <div class="flex flex-wrap gap-2">
                                            <span v-for="slot in fieldData.timeSlots" :key="slot"
                                                class="flex items-center gap-1 rounded-full bg-appGreenLight/10 px-3 py-1 text-xs font-medium text-appGreenDark dark:text-appGreenLight">
                                                {{ slot }}
                                                <button @click="selectTimeSlot(fieldId, slot)" class="text-current hover:text-red-500">
                                                    <X class="h-3 w-3" />
                                                </button>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Subtotal for this field -->
                                    <div class="mt-4 flex justify-between border-t pt-4 text-sm">
                                        <span class="text-gray-600 dark:text-gray-400">Subtotal ({{ fieldData.timeSlots.length }} jam)</span>
                                        <span class="font-medium text-appGreenDark dark:text-appGreenLight">
                                            Rp {{ (fieldData.timeSlots.length * fieldData.field.price).toLocaleString() }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Total price for all fields -->
                                <div class="mt-6 rounded-xl bg-gray-50 p-4 dark:bg-gray-800">
                                    <div class="flex justify-between text-lg font-semibold">
                                        <span class="text-gray-900 dark:text-white">Total</span>
                                        <span class="text-appGreenDark dark:text-appGreenLight">
                                            Rp {{ totalPrice.toLocaleString() }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Empty state when no fields selected -->
                            <div v-else class="rounded-xl border border-dashed border-gray-300 p-6 text-center dark:border-gray-700">
                                <Info class="mx-auto mb-3 h-8 w-8 text-gray-400" />
                                <p class="text-gray-600 dark:text-gray-400">Pilih lapangan untuk mulai booking</p>
                            </div>

                            <button @click="book"
                                :disabled="selectedFields.size === 0"
                                :class="[
                                    'w-full rounded-xl px-6 py-3 text-center font-semibold text-white transition-colors',
                                    selectedFields.size > 0
                                        ? 'bg-appGreenLight hover:bg-appGreenMedium'
                                        : 'cursor-not-allowed bg-gray-300 dark:bg-gray-700'
                                ]">
                                Booking Sekarang
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Field Detail Modal -->
        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="showingFieldDetail"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4"
                @click.self="closeFieldDetail">
                <div class="max-h-[90vh] w-full max-w-4xl overflow-y-auto rounded-3xl bg-white p-6 dark:bg-gray-800">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ selectedFields.get(selectedFieldId)?.field.name }}
                        </h3>
                        <button @click="closeFieldDetail"
                            class="rounded-full p-2 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <X class="h-6 w-6 text-gray-500 dark:text-gray-400" />
                        </button>
                    </div>

                    <!-- Image Gallery -->
                    <div class="relative mb-6 aspect-[16/9] overflow-hidden rounded-2xl">
                        <img
                            :src="selectedFields.get(selectedFieldId)?.field.images[currentImageIndex]"
                            :alt="selectedFields.get(selectedFieldId)?.field.name"
                            class="h-full w-full object-cover"
                        />

                        <!-- Navigation Buttons -->
                        <button @click.stop="prevImage"
                            :class="['absolute left-4 top-1/2 -translate-y-1/2 rounded-full bg-black/30 p-2 text-white backdrop-blur-sm transition-colors hover:bg-black/50',
                            { 'opacity-50 cursor-not-allowed': currentImageIndex === 0 }]">
                            <ChevronRight class="h-6 w-6 rotate-180" />
                        </button>
                        <button @click.stop="nextImage"
                            :class="['absolute right-4 top-1/2 -translate-y-1/2 rounded-full bg-black/30 p-2 text-white backdrop-blur-sm transition-colors hover:bg-black/50',
                            { 'opacity-50 cursor-not-allowed': currentImageIndex === (selectedFields.get(selectedFieldId)?.field.images.length - 1) }]">
                            <ChevronRight class="h-6 w-6" />
                        </button>

                        <!-- Image Counter -->
                        <div class="absolute bottom-4 right-4 rounded-full bg-black/30 px-3 py-1 text-sm text-white backdrop-blur-sm">
                            {{ currentImageIndex + 1 }}/{{ selectedFields.get(selectedFieldId)?.field.images.length }}
                        </div>
                    </div>

                    <div class="mb-6 space-y-4">
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ selectedFields.get(selectedFieldId)?.field.description }}
                        </p>

                        <div class="flex flex-wrap gap-4">
                            <div class="flex items-center gap-2">
                                <Shield class="h-5 w-5 text-gray-400" />
                                <span class="text-gray-600 dark:text-gray-400">
                                    {{ selectedFields.get(selectedFieldId)?.field.equipment.join(', ') }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <DollarSign class="h-5 w-5 text-gray-400" />
                                <span class="font-medium text-appGreenDark dark:text-appGreenLight">
                                    Rp {{ selectedFields.get(selectedFieldId)?.field.price.toLocaleString() }}/jam
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <button @click="closeFieldDetail"
                            class="flex-1 rounded-xl bg-gray-100 px-6 py-3 font-semibold text-gray-700 transition-colors hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                            Tutup
                        </button>
                        <button @click="addToBooking(selectedFields.get(selectedFieldId)?.field); closeFieldDetail()"
                            class="flex-1 rounded-xl bg-appGreenLight px-6 py-3 font-semibold text-white transition-colors hover:bg-appGreenMedium">
                            Pilih Lapangan
                        </button>
                    </div>
                </div>
            </div>
        </Transition>

        <!-- Toast Notification -->
        <Transition
            enter-active-class="transform transition-all duration-300"
            enter-from-class="translate-y-full opacity-0"
            enter-to-class="translate-y-0 opacity-100"
            leave-active-class="transform transition-all duration-300"
            leave-from-class="translate-y-0 opacity-100"
            leave-to-class="translate-y-full opacity-0"
        >
            <div
                v-if="showToast"
                class="fixed bottom-4 left-1/2 z-50 -translate-x-1/2 rounded-xl bg-gray-800 px-6 py-3 text-white shadow-lg"
            >
                {{ toastMessage }}
            </div>
        </Transition>
    </Layout>
</template>
