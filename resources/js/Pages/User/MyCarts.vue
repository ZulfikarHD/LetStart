<template>
    <AuthenticatedLayout>
        <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <!-- Header with Cart Summary -->
                <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-appBlack dark:text-white">
                            Keranjang Booking
                        </h1>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">
                            {{ cartItems.length }} venue dipilih
                        </p>
                    </div>

                    <!-- Quick Actions -->
                    <div class="mt-4 sm:mt-0 flex gap-3">
                        <button @click="clearCart"
                            class="flex items-center gap-2 px-4 py-2 text-sm text-gray-600 hover:text-red-500 transition-colors">
                            <LucideTrash2 class="h-4 w-4" />
                            Kosongkan Keranjang
                        </button>
                        <Link href="/venues"
                            class="flex items-center gap-2 px-4 py-2 text-sm text-appGreenDark hover:text-appGreenLight transition-colors">
                            <LucidePlus class="h-4 w-4" />
                            Tambah Venue
                        </Link>
                    </div>
                </div>

                <!-- Cart Content and Summary Section -->
                <div class="lg:grid lg:grid-cols-12 lg:gap-8">
                    <!-- Cart Items List -->
                    <div class="lg:col-span-8">
                        <TransitionGroup
                            name="cart-list"
                            tag="div"
                            class="space-y-4"
                        >
                            <div v-for="item in cartItems" :key="item.id"
                                class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 relative group">
                                <!-- Quick Remove Button -->
                                <button @click="confirmRemove(item.id)"
                                    class="absolute top-4 right-4 p-2 text-gray-400 hover:text-red-500 transition-colors opacity-0 group-hover:opacity-100">
                                    <LucideX class="h-5 w-5" />
                                </button>

                                <div class="flex items-start gap-4">
                                    <!-- Venue Image -->
                                    <div class="w-24 h-24 rounded-lg overflow-hidden">
                                        <img :src="item.venue_image" :alt="item.venue_name"
                                            class="w-full h-full object-cover" />
                                    </div>

                                    <!-- Booking Details -->
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-appBlack dark:text-white">
                                            {{ item.venue_name }}
                                        </h3>

                                        <!-- Date and Time -->
                                        <div class="mt-2 space-y-1">
                                            <div class="flex items-center text-gray-600 dark:text-gray-300">
                                                <LucideCalendar class="h-4 w-4 mr-2" />
                                                <span>{{ formatDate(item.date) }}</span>
                                            </div>
                                            <div class="flex items-center text-gray-600 dark:text-gray-300">
                                                <LucideClock class="h-4 w-4 mr-2" />
                                                <span>{{ item.start_time }} - {{ item.end_time }}</span>
                                            </div>
                                        </div>

                                        <!-- Time Slot Editor -->
                                        <div class="mt-4">
                                            <button @click="editTimeSlots(item)"
                                                class="text-sm text-appBlueMedium hover:text-appBlueLight transition-colors flex items-center gap-1">
                                                <LucideEdit class="h-4 w-4" />
                                                Ubah Jadwal
                                            </button>
                                        </div>

                                        <!-- Price -->
                                        <div class="mt-3 text-lg font-medium text-appGreenDark dark:text-appGreenLight">
                                            Rp {{ formatPrice(item.price) }}
                                        </div>

                                        <!-- Availability Status -->
                                        <div class="mt-2">
                                            <span class="inline-flex items-center gap-1 text-sm"
                                                :class="item.isAvailable ? 'text-green-600' : 'text-red-500'">
                                                <div :class="item.isAvailable ? 'bg-green-500' : 'bg-red-500'"
                                                    class="w-2 h-2 rounded-full"></div>
                                                {{ item.isAvailable ? 'Tersedia' : 'Jadwal Sudah Terisi' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </TransitionGroup>

                        <!-- Empty State -->
                        <div v-if="!cartItems.length"
                            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-8 text-center">
                            <div class="max-w-sm mx-auto">
                                <LucideShoppingCart class="h-12 w-12 mx-auto text-gray-400" />
                                <h3 class="mt-4 text-lg font-medium text-appBlack dark:text-white">
                                    Keranjang Kosong
                                </h3>
                                <p class="mt-2 text-gray-600 dark:text-gray-400">
                                    Mulai booking venue olahraga favoritmu sekarang
                                </p>
                                <Link href="/venues"
                                    class="mt-6 inline-flex items-center px-4 py-2 rounded-lg bg-appGreenLight text-white hover:bg-appGreenMedium transition-colors">
                                    <LucideSearch class="h-4 w-4 mr-2" />
                                    Cari Venue
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:col-span-4 mt-8 lg:mt-0">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 sticky top-4">
                            <h3 class="text-lg font-semibold text-appBlack dark:text-white mb-4">
                                Ringkasan Pesanan
                            </h3>

                            <!-- Summary Details -->
                            <div class="space-y-3">
                                <div class="flex justify-between text-gray-600 dark:text-gray-400">
                                    <span>Subtotal ({{ cartItems.length }} item)</span>
                                    <span>Rp {{ formatPrice(subtotal) }}</span>
                                </div>
                                <div class="flex justify-between text-gray-600 dark:text-gray-400">
                                    <span>Biaya Layanan</span>
                                    <span>Rp {{ formatPrice(serviceFee) }}</span>
                                </div>

                                <!-- Applied Promo -->
                                <div v-if="appliedPromo"
                                    class="flex justify-between text-green-600 dark:text-green-400">
                                    <span class="flex items-center gap-2">
                                        <LucideTag class="h-4 w-4" />
                                        {{ appliedPromo.code }}
                                        <button @click="removePromo" class="text-red-500 hover:text-red-600">
                                            <LucideX class="h-3 w-3" />
                                        </button>
                                    </span>
                                    <span>-Rp {{ formatPrice(promoDiscount) }}</span>
                                </div>

                                <div class="border-t border-gray-200 dark:border-gray-700 pt-3">
                                    <div class="flex justify-between text-lg font-semibold text-appBlack dark:text-white">
                                        <span>Total</span>
                                        <span>Rp {{ formatPrice(total) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Promo Code Input -->
                            <div v-if="!appliedPromo" class="mt-6">
                                <div class="flex gap-2">
                                    <input type="text" v-model="promoCode"
                                        placeholder="Masukkan kode promo"
                                        class="flex-1 px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-2 focus:ring-appGreenLight" />
                                    <button @click="applyPromo"
                                        class="px-4 py-2 rounded-lg bg-appGreenLight text-white hover:bg-appGreenMedium transition-colors">
                                        Pakai
                                    </button>
                                </div>
                            </div>

                            <!-- Checkout Button -->
                            <button @click="proceedToCheckout"
                                :disabled="!cartItems.length || hasUnavailableItems"
                                class="mt-6 w-full px-6 py-3 rounded-lg bg-appGreenLight text-white hover:bg-appGreenMedium disabled:opacity-50 disabled:cursor-not-allowed transition-all flex items-center justify-center gap-2">
                                <LucideCreditCard class="h-5 w-5" />
                                Lanjut ke Pembayaran
                            </button>

                            <!-- Unavailable Items Warning -->
                            <div v-if="hasUnavailableItems" class="mt-4 text-sm text-red-500 text-center">
                                Beberapa jadwal sudah tidak tersedia. Silakan ubah jadwal atau hapus item yang tidak tersedia.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Time Slot Editor Modal -->
        <TransitionRoot appear :show="showTimeSlotEditor" as="template">
            <Dialog as="div" @close="closeTimeSlotEditor" class="relative z-50">
                <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" />

                <div class="fixed inset-0 overflow-y-auto">
                    <div class="flex min-h-full items-center justify-center p-4">
                        <DialogPanel class="w-full max-w-md rounded-2xl bg-white dark:bg-gray-800 p-6">
                            <DialogTitle class="text-lg font-semibold text-gray-900 dark:text-white">
                                Ubah Jadwal
                            </DialogTitle>

                            <!-- Date Picker -->
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Tanggal
                                </label>
                                <input type="date" v-model="selectedDate"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700" />
                            </div>

                            <!-- Time Slots Grid -->
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                    Pilih Jam
                                </label>
                                <div class="grid grid-cols-3 gap-2">
                                    <button v-for="slot in availableTimeSlots" :key="slot.id"
                                        @click="toggleTimeSlot(slot.id)"
                                        :disabled="!slot.available"
                                        :class="[
                                            'p-3 rounded-lg text-sm font-medium transition-colors',
                                            selectedTimeSlots.has(slot.id)
                                                ? 'bg-appGreenLight text-white'
                                                : slot.available
                                                    ? 'bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 hover:border-appGreenLight'
                                                    : 'bg-gray-100 dark:bg-gray-800 text-gray-400 cursor-not-allowed'
                                        ]">
                                        {{ slot.start }}
                                        <span class="block text-xs mt-1">
                                            Rp {{ formatPrice(slot.price) }}
                                        </span>
                                    </button>
                                </div>
                            </div>

                            <!-- Total Price -->
                            <div class="mt-6 flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Total</span>
                                <span class="font-medium text-appGreenDark dark:text-appGreenLight">
                                    Rp {{ formatPrice(Array.from(selectedTimeSlots).reduce((sum, id) =>
                                        sum + (availableTimeSlots.find(slot => slot.id === id)?.price || 0), 0)) }}
                                </span>
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-6 flex justify-end gap-3">
                                <button @click="closeTimeSlotEditor"
                                    class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
                                    Batal
                                </button>
                                <button @click="saveTimeSlots"
                                    :disabled="!selectedTimeSlots.size"
                                    class="px-4 py-2 rounded-lg bg-appGreenLight text-white hover:bg-appGreenMedium disabled:opacity-50">
                                    Simpan
                                </button>
                            </div>
                        </DialogPanel>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- Confirmation Modal -->
        <TransitionRoot appear :show="showConfirmation" as="template">
            <Dialog as="div" @close="closeConfirmation" class="relative z-50">
                <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" />

                <div class="fixed inset-0 overflow-y-auto">
                    <div class="flex min-h-full items-center justify-center p-4">
                        <DialogPanel class="w-full max-w-md rounded-2xl bg-white dark:bg-gray-800 p-6">
                            <DialogTitle class="text-lg font-semibold text-gray-900 dark:text-white">
                                Hapus Item
                            </DialogTitle>
                            <div class="mt-4">
                                <p class="text-gray-600 dark:text-gray-400">
                                    Apakah Anda yakin ingin menghapus item ini dari keranjang?
                                </p>
                            </div>
                            <div class="mt-6 flex justify-end gap-3">
                                <button @click="closeConfirmation"
                                    class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
                                    Batal
                                </button>
                                <button @click="removeFromCart"
                                    class="px-4 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600">
                                    Hapus
                                </button>
                            </div>
                        </DialogPanel>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Dialog, DialogPanel, DialogTitle, TransitionRoot } from '@headlessui/vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {
    Calendar as LucideCalendar,
    Clock as LucideClock,
    X as LucideX,
    ShoppingCart as LucideShoppingCart,
    Search as LucideSearch,
    CreditCard as LucideCreditCard,
    Tag as LucideTag,
    Edit as LucideEdit,
    Trash2 as LucideTrash2,
    Plus as LucidePlus,
} from 'lucide-vue-next';

// Define props to receive data from web.php
const props = defineProps({
    cartItems: {
        type: Array,
        required: true,
        default: () => []
    },
    auth: {
        type: Object,
        required: true
    }
});

// Change the ref to use props data
const cartItems = ref(props.cartItems);
const promoCode = ref('');
const appliedPromo = ref(null);
const showTimeSlotEditor = ref(false);
const showConfirmation = ref(false);
const selectedItemId = ref(null);
const serviceFee = 5000;

// Computed
const subtotal = computed(() => {
    return cartItems.value.reduce((sum, item) => sum + item.price, 0);
});

const promoDiscount = computed(() => {
    if (!appliedPromo.value) return 0;
    return Math.floor(subtotal.value * (appliedPromo.value.discount / 100));
});

const total = computed(() => {
    return subtotal.value + serviceFee - promoDiscount.value;
});

const hasUnavailableItems = computed(() => {
    return cartItems.value.some(item => !item.isAvailable);
});

// Methods
const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID').format(price);
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};

const confirmRemove = (itemId) => {
    selectedItemId.value = itemId;
    showConfirmation.value = true;
};

const removeFromCart = () => {
    cartItems.value = cartItems.value.filter(item => item.id !== selectedItemId.value);
    closeConfirmation();
};

const editTimeSlots = (item) => {
    selectedItemId.value = item.id;
    showTimeSlotEditor.value = true;
};

const applyPromo = () => {
    // Implement promo logic
};

const removePromo = () => {
    appliedPromo.value = null;
};

const proceedToCheckout = () => {
    // Implement checkout logic
};

const clearCart = () => {
    // Show confirmation before clearing
    if (confirm('Apakah Anda yakin ingin mengosongkan keranjang?')) {
        cartItems.value = [];
    }
};

// Modal handling
const closeTimeSlotEditor = () => {
    showTimeSlotEditor.value = false;
    selectedItemId.value = null;
};

const closeConfirmation = () => {
    showConfirmation.value = false;
    selectedItemId.value = null;
};

// Add new state
const selectedItems = ref(new Set());

// Add new computed
const selectedTotal = computed(() => {
    return cartItems.value
        .filter(item => selectedItems.value.has(item.id))
        .reduce((sum, item) => sum + item.price, 0);
});

// Add new methods
const toggleItemSelection = (itemId) => {
    if (selectedItems.value.has(itemId)) {
        selectedItems.value.delete(itemId);
    } else {
        selectedItems.value.add(itemId);
    }
};

const toggleAllItems = () => {
    if (selectedItems.value.size === cartItems.value.length) {
        selectedItems.value.clear();
    } else {
        cartItems.value.forEach(item => selectedItems.value.add(item.id));
    }
};

// Add new state for time slots
const availableTimeSlots = ref([
    { id: 1, start: '08:00', end: '09:00', price: 100000, available: true },
    { id: 2, start: '09:00', end: '10:00', price: 100000, available: true },
    { id: 3, start: '10:00', end: '11:00', price: 100000, available: false },
    { id: 4, start: '11:00', end: '12:00', price: 100000, available: true },
    { id: 5, start: '13:00', end: '14:00', price: 120000, available: true },
    { id: 6, start: '14:00', end: '15:00', price: 120000, available: true },
    { id: 7, start: '15:00', end: '16:00', price: 120000, available: false },
    { id: 8, start: '16:00', end: '17:00', price: 150000, available: true },
    { id: 9, start: '17:00', end: '18:00', price: 150000, available: true },
    { id: 10, start: '18:00', end: '19:00', price: 150000, available: true },
]);

const selectedDate = ref(new Date());
const selectedTimeSlots = ref(new Set());

// Add methods for time slot handling
const toggleTimeSlot = (slotId) => {
    if (selectedTimeSlots.value.has(slotId)) {
        selectedTimeSlots.value.delete(slotId);
    } else {
        selectedTimeSlots.value.add(slotId);
    }
};

const saveTimeSlots = () => {
    // Update cart item with new time slots
    const item = cartItems.value.find(item => item.id === selectedItemId.value);
    if (item) {
        const slots = Array.from(selectedTimeSlots.value)
            .map(id => availableTimeSlots.value.find(slot => slot.id === id))
            .filter(Boolean);

        if (slots.length) {
            item.start_time = slots[0].start;
            item.end_time = slots[slots.length - 1].end;
            item.price = slots.reduce((sum, slot) => sum + slot.price, 0);
        }
    }
    closeTimeSlotEditor();
};
</script>

<style scoped>
.cart-list-move,
.cart-list-enter-active,
.cart-list-leave-active {
    transition: all 0.5s ease;
}

.cart-list-enter-from,
.cart-list-leave-to {
    opacity: 0;
    transform: translateX(-30px);
}

.cart-list-leave-active {
    position: absolute;
}
</style>
