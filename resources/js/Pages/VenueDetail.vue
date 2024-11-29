<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Dialog, DialogPanel } from '@headlessui/vue';
import {
    ShoppingCart,
    CreditCard,
    MapPin,
    Calendar,
    DollarSign,
    Star,
    Info,
    Shield,
    Dumbbell,
    ChevronRight,
    X,
    ChevronLeft
} from 'lucide-vue-next';
import { useCartStore } from '@/Stores/cart';
import { useToast } from '@/Composables/useToast';
import AnimatedDialog from '@/Components/AnimatedDialog.vue';

const props = defineProps({
    auth: {
        type: Object,
        default: () => ({ user: null })
    },
    venue: {
        type: Object,
        required: true
    }
});

// Compute which layout to use based on auth status
const Layout = computed(() => {
    return props.auth?.user ? AuthenticatedLayout : GuestLayout;
});

const showBookingModal = ref(false);
const bookingStep = ref(1); // 1: Select Fields, 2: Select Time, 3: Review & Pay
const selectedDate = ref(new Date());
const selectedFields = ref(new Map());
const filterSport = ref(null);
const selectedFieldId = ref(null);
const showingFieldDetail = ref(false);
const currentImageIndex = ref(0);
const cartStore = useCartStore();
const showConfirmModal = ref(false);
const bookingData = ref(null);

const toast = useToast();

// Simplified selection logic
const addToBooking = (field) => {
    if (!field || !field.available) return;

    // Create booking data structure
    const bookingItem = {
        id: `${field.id}-${Date.now()}`, // Unique ID
        fieldId: field.id,
        venueName: props.venue.name,
        fieldName: field.name,
        date: selectedDate.value,
        timeSlots: [],
        price: field.price,
        image: field.image
    };

    // Clear existing selection for this field if it exists
    selectedFields.value.delete(field.id);

    // Add new selection
    selectedFields.value.set(field.id, {
        field,
        timeSlots: [],
        bookingData: bookingItem
    });

    showBookingModal.value = true;
    closeFieldDetail(); // Close the field detail modal
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
    if (!props.auth?.user) {
        toast.warning('Silakan login terlebih dahulu untuk melakukan booking');
        router.visit('/login');
        return;
    }

    if (selectedFields.value.size === 0) {
        toast.warning('Pilih lapangan terlebih dahulu');
        return;
    }

    const bookings = Array.from(selectedFields.value.values()).map(({ field, timeSlots }) => ({
        fieldId: field.id,
        name: field.name,
        type: field.type,
        timeSlots: timeSlots,
        price: field.price,
        totalPrice: timeSlots.length * field.price,
        image: field.image,
        date: selectedDate.value,
        venueName: props.venue.name
    }));

    bookingData.value = bookings;
    showConfirmModal.value = true;
};

// Add smooth scrolling
const scrollToBooking = () => {
  const bookingSection = document.querySelector('#booking-section');
  bookingSection?.scrollIntoView({ behavior: 'smooth' });
};

const filteredFields = computed(() => {
    if (!filterSport.value) return props.venue.fields;
    return props.venue.fields.filter(field => field.type === filterSport.value);
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
    const selectedField = props.venue.fields.find(f => f.id === field.id);
    if (!selectedField) return;

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
    if (currentImageIndex.value < props.venue.images.length - 1) {
        currentImageIndex.value++;
    }
};

const prevImage = () => {
    if (currentImageIndex.value > 0) {
        currentImageIndex.value--;
    }
};

// Add to cart function
const addToCart = () => {
    try {
        bookingData.value.forEach(booking => {
            cartStore.addItem(booking);
        });

        toast.success('Berhasil ditambahkan ke keranjang');
        showConfirmModal.value = false;
        router.visit('/cart');
    } catch (error) {
        toast.error(error.message || 'Gagal menambahkan ke keranjang');
    }
};

// Direct checkout function
const directCheckout = () => {
    try {
        console.log('Starting checkout process');

        // First, ensure bookingData exists and has items
        if (!bookingData.value || bookingData.value.length === 0) {
            throw new Error('Tidak ada item untuk checkout');
        }

        // Clear existing cart first to avoid duplicates
        cartStore.clearCart();

        bookingData.value.forEach(booking => {
            const cartItem = {
                fieldId: booking.fieldId,
                name: booking.fieldName || booking.name, // Handle both possible property names
                type: booking.type,
                timeSlots: booking.timeSlots,
                price: booking.price,
                totalPrice: booking.totalPrice || (booking.price * booking.timeSlots.length),
                image: booking.image,
                date: booking.date,
                venueName: props.venue.name
            };

            console.log('Adding item to cart:', cartItem);
            cartStore.addItem(cartItem);
        });

        showConfirmModal.value = false;

        // Use router.get instead of visit for more reliable navigation
        router.get('/checkout', {}, {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                console.log('Navigation to checkout successful');
            },
            onError: (errors) => {
                console.error('Navigation failed:', errors);
                toast.error('Gagal menuju halaman checkout');
            }
        });
    } catch (error) {
        console.error('Checkout error:', error);
        toast.error(error.message || 'Gagal memproses checkout');
    }
};

// Add this computed property
const selectedField = computed(() => {
    return props.venue.fields.find(f => f.id === selectedFieldId.value);
});
</script>

<template>
    <Layout>
        <Head :title="venue.name" />

        <div class="min-h-screen bg-gray-50 py-8 dark:bg-gray-900">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Venue Hero Section -->
                <div class="relative mb-8">
                    <!-- Main Image Gallery -->
                    <div class="relative overflow-hidden rounded-3xl bg-gray-100 dark:bg-gray-800">
                        <!-- Main Image -->
                        <div class="relative aspect-[21/9] w-full overflow-hidden lg:aspect-[21/8]">
                            <!-- Use transition group for smooth image changes -->
                            <TransitionGroup
                                name="fade-slide"
                                tag="div"
                                class="relative h-full w-full"
                            >
                                <img
                                    :key="currentImageIndex"
                                    :src="venue.images[currentImageIndex]"
                                    :alt="`${venue.name} - Image ${currentImageIndex + 1}`"
                                    class="absolute h-full w-full object-cover"
                                />
                            </TransitionGroup>

                            <!-- Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>

                            <!-- Hero Content Overlay -->
                            <div class="absolute inset-x-0 bottom-0 p-4 text-white sm:p-6 lg:p-8">
                                <div class="mx-auto max-w-7xl">
                                    <div class="flex flex-col gap-2">
                                        <!-- Venue Type Badge -->
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-appGreenLight/90 px-3 py-1 text-sm font-medium backdrop-blur-sm">
                                                <Dumbbell class="h-4 w-4" />
                                                {{ venue.sports[0] }}
                                            </span>
                                            <span class="flex items-center gap-1.5 rounded-full bg-white/20 px-3 py-1 text-sm backdrop-blur-sm">
                                                <Star class="h-4 w-4 fill-yellow-400 text-yellow-400" />
                                                {{ venue.rating }}
                                            </span>
                                        </div>

                                        <!-- Venue Name -->
                                        <h1 class="text-2xl font-bold sm:text-3xl lg:text-4xl">{{ venue.name }}</h1>

                                        <!-- Location -->
                                        <div class="flex items-center gap-2 text-white/90">
                                            <MapPin class="h-5 w-5" />
                                            <span class="text-sm sm:text-base">{{ venue.address }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Enhanced Navigation Controls -->
                            <div class="absolute inset-x-0 top-1/2 flex -translate-y-1/2 justify-between px-4">
                                <button
                                    @click="prevImage"
                                    class="group relative rounded-full bg-black/20 p-3 text-white backdrop-blur-sm transition-all duration-300 hover:bg-black/40 disabled:opacity-50 disabled:hover:bg-black/20"
                                    :disabled="currentImageIndex === 0"
                                >
                                    <ChevronLeft class="h-6 w-6 transition-transform duration-300 group-hover:-translate-x-0.5" />
                                    <span class="absolute -right-2 top-1/2 h-px w-8 -translate-y-1/2 bg-white/0 transition-all duration-300 group-hover:w-12 group-hover:bg-white/20"></span>
                                </button>
                                <button
                                    @click="nextImage"
                                    class="group relative rounded-full bg-black/20 p-3 text-white backdrop-blur-sm transition-all duration-300 hover:bg-black/40 disabled:opacity-50 disabled:hover:bg-black/20"
                                    :disabled="currentImageIndex === venue.images.length - 1"
                                >
                                    <ChevronRight class="h-6 w-6 transition-transform duration-300 group-hover:translate-x-0.5" />
                                    <span class="absolute -left-2 top-1/2 h-px w-8 -translate-y-1/2 bg-white/0 transition-all duration-300 group-hover:w-12 group-hover:bg-white/20"></span>
                                </button>
                            </div>
                        </div>

                        <!-- Enhanced Thumbnail Strip -->
                        <div class="relative border-t border-gray-200 bg-white/80 p-4 backdrop-blur-sm dark:border-gray-700 dark:bg-gray-800/80">
                            <div class="mx-auto max-w-7xl">
                                <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-hide">
                                    <button
                                        v-for="(image, index) in venue.images"
                                        :key="index"
                                        @click="currentImageIndex = index"
                                        class="group relative aspect-[4/3] h-16 flex-shrink-0 overflow-hidden rounded-lg transition-all duration-300 hover:scale-105 sm:h-20"
                                        :class="[
                                            currentImageIndex === index
                                                ? 'ring-2 ring-appGreenLight ring-offset-2 dark:ring-offset-gray-900'
                                                : 'hover:ring-2 hover:ring-appGreenLight/50 hover:ring-offset-2 dark:hover:ring-offset-gray-900'
                                        ]"
                                    >
                                        <img
                                            :src="image"
                                            :alt="`${venue.name} - ${index + 1}`"
                                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                                        />
                                        <div
                                            class="absolute inset-0 bg-black/10 transition-opacity duration-300"
                                            :class="currentImageIndex === index ? 'opacity-0' : 'opacity-100 group-hover:opacity-0'"
                                        ></div>
                                        <!-- Active Indicator -->
                                        <div
                                            class="absolute bottom-0 left-0 h-0.5 w-full bg-appGreenLight transition-transform duration-300"
                                            :class="currentImageIndex === index ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100'"
                                        ></div>
                                    </button>
                                </div>

                                <!-- Enhanced Image Counter -->
                                <div class="absolute right-6 top-4 rounded-full bg-gray-900/10 px-3 py-1.5 text-sm font-medium backdrop-blur-sm transition-all duration-300 hover:bg-gray-900/20 dark:bg-white/10 dark:hover:bg-white/20">
                                    {{ currentImageIndex + 1 }}/{{ venue.images.length }}
                                </div>
                            </div>
                        </div>
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
        <AnimatedDialog
            :open="showingFieldDetail"
            @close="closeFieldDetail"
            size="lg"
            panel-class="sm:max-w-3xl"
        >
            <div class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                {{ selectedField?.type }} / {{ selectedField?.name }}
            </div>

            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ selectedField?.name }}
                    </h3>
                    <span :class="[
                        'mt-1 inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                        selectedField?.available
                            ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                            : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
                    ]">
                        {{ selectedField?.available ? 'Tersedia' : 'Tidak Tersedia' }}
                    </span>
                </div>
                <button @click="closeFieldDetail"
                    class="rounded-full p-2 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <X class="h-6 w-6 text-gray-500 dark:text-gray-400" />
                </button>
            </div>

            <div class="mb-6">
                <div class="relative aspect-[16/9] overflow-hidden rounded-2xl">
                    <img
                        :src="selectedField?.images[currentImageIndex]"
                        :alt="selectedField?.name"
                        class="h-full w-full object-cover transition-opacity duration-300"
                    />
                    <button @click.stop="prevImage"
                        :class="['absolute left-4 top-1/2 -translate-y-1/2 rounded-full bg-black/30 p-2 text-white backdrop-blur-sm transition-colors hover:bg-black/50',
                        { 'opacity-50 cursor-not-allowed': currentImageIndex === 0 }]">
                        <ChevronRight class="h-6 w-6 rotate-180" />
                    </button>
                    <button @click.stop="nextImage"
                        :class="['absolute right-4 top-1/2 -translate-y-1/2 rounded-full bg-black/30 p-2 text-white backdrop-blur-sm transition-colors hover:bg-black/50',
                        { 'opacity-50 cursor-not-allowed': currentImageIndex === (selectedField?.images.length - 1) }]">
                        <ChevronRight class="h-6 w-6" />
                    </button>
                    <div class="absolute bottom-4 right-4 rounded-full bg-black/30 px-3 py-1 text-sm text-white backdrop-blur-sm">
                        {{ currentImageIndex + 1 }}/{{ selectedField?.images.length }}
                    </div>
                </div>

                <div class="mt-4 flex gap-2 overflow-x-auto pb-2">
                    <button
                        v-for="(image, index) in selectedField?.images"
                        :key="index"
                        @click="currentImageIndex = index"
                        class="relative h-16 w-24 flex-shrink-0 overflow-hidden rounded-lg"
                        :class="currentImageIndex === index ? 'ring-2 ring-appGreenLight' : ''"
                    >
                        <img
                            :src="image"
                            :alt="`${selectedField?.name} - ${index + 1}`"
                            class="h-full w-full object-cover"
                        />
                    </button>
                </div>
            </div>

            <div class="mb-6 grid gap-6 sm:grid-cols-2">
                <div>
                    <h4 class="mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Deskripsi
                    </h4>
                    <p class="text-gray-600 dark:text-gray-400">
                        {{ selectedField?.description }}
                    </p>
                </div>

                <div>
                    <h4 class="mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Fasilitas
                    </h4>
                    <div class="flex flex-wrap gap-2">
                        <span
                            v-for="equipment in selectedField?.equipment"
                            :key="equipment"
                            class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-3 py-1 text-sm text-gray-700 dark:bg-gray-800 dark:text-gray-300"
                        >
                            <Shield class="h-4 w-4" />
                            {{ equipment }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="mb-6 rounded-lg bg-gray-50 p-4 dark:bg-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Harga Sewa
                        </h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Per jam penggunaan
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-appGreenDark dark:text-appGreenLight">
                            Rp {{ selectedField?.price.toLocaleString() }}
                        </div>
                        <span class="text-sm text-gray-500">/jam</span>
                    </div>
                </div>
            </div>

            <div class="flex gap-2">
                <button @click="closeFieldDetail"
                    class="flex-1 rounded-xl border border-gray-200 bg-white px-6 py-3 font-semibold text-gray-700 transition-all hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    Kembali
                </button>
                <button
                    @click="addToBooking(selectedField)"
                    :disabled="!selectedField?.available"
                    class="flex-1 items-center justify-center gap-2 rounded-xl bg-appGreenLight px-6 py-3 font-semibold text-white transition-all hover:bg-appGreenMedium disabled:cursor-not-allowed disabled:opacity-50"
                >
                    <span class="flex items-center gap-2">
                        <Calendar class="h-5 w-5" />
                        Pilih Lapangan
                    </span>
                </button>
            </div>
        </AnimatedDialog>

        <!-- Update the confirmation modal -->
        <AnimatedDialog
            :open="showConfirmModal"
            @close="showConfirmModal = false"
        >
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Konfirmasi Booking
                </h3>
                <button @click="showConfirmModal = false"
                    class="rounded-full p-2 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <X class="h-5 w-5 text-gray-500" />
                </button>
            </div>

            <!-- Add booking summary -->
            <div class="mb-6 space-y-4">
                <div v-for="(booking, index) in bookingData" :key="index"
                    class="rounded-lg border p-4 dark:border-gray-700">
                    <div class="flex items-center gap-4">
                        <img :src="booking.image" :alt="booking.fieldName"
                            class="h-16 w-16 rounded-lg object-cover" />
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white">
                                {{ booking.fieldName }}
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ new Date(booking.date).toLocaleDateString('id-ID', {
                                    weekday: 'long',
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric'
                                }) }}
                            </p>
                            <div class="mt-2 flex flex-wrap gap-2">
                                <span v-for="slot in booking.timeSlots" :key="slot"
                                    class="rounded-full bg-appGreenLight/10 px-2 py-1 text-xs font-medium text-appGreenDark dark:text-appGreenLight">
                                    {{ slot }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <button @click="addToCart"
                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-appGreenLight p-4 text-white transition-colors hover:bg-appGreenMedium">
                    <ShoppingCart class="h-5 w-5" />
                    Tambah ke Keranjang
                </button>

                <button @click="directCheckout"
                    class="flex w-full items-center justify-center gap-2 rounded-xl border border-appGreenLight p-4 text-appGreenDark transition-colors hover:bg-appGreenLight/10 dark:text-appGreenLight">
                    <CreditCard class="h-5 w-5" />
                    Langsung Checkout
                </button>
            </div>
        </AnimatedDialog>
    </Layout>
</template>

<style scoped>
.fade-slide-enter-active,
.fade-slide-leave-active {
    transition: all 0.5s ease;
}

.fade-slide-enter-from {
    opacity: 0;
    transform: translateX(5%);
}

.fade-slide-leave-to {
    opacity: 0;
    transform: translateX(-5%);
}

/* Hide scrollbar but keep functionality */
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
</style>
