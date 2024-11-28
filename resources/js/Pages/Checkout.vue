<template>
  <AuthenticatedLayout>
    <div class="container mx-auto px-8 py-8 max-w-7xl">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white md:text-3xl">Checkout</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Selesaikan pemesanan lapangan Anda</p>
      </div>

      <div class="mb-8">
        <div class="flex justify-center">
          <ol class="flex w-full max-w-3xl items-center">
            <li class="flex w-full items-center" :class="{'text-appGreenLight': currentStep >= 1}">
              <span class="flex h-8 w-8 items-center justify-center rounded-full transition-all duration-300"
                    :class="[currentStep >= 1 ? 'bg-appGreenLight scale-110' : 'bg-gray-200']">
                <CheckIcon v-if="currentStep > 1" class="h-5 w-5 text-white" />
                <span v-else>1</span>
              </span>
              <span class="ml-2 text-sm font-medium">Keranjang</span>
              <div class="ml-2 h-0.5 w-full bg-appGreenLight"></div>
            </li>
            <li class="flex w-full items-center">
              <span class="flex h-8 w-8 items-center justify-center rounded-full bg-appGreenLight text-white">2</span>
              <span class="ml-2 text-sm font-medium">Pembayaran</span>
              <div class="ml-2 h-0.5 w-full bg-gray-200 dark:bg-gray-700"></div>
            </li>
            <li class="flex items-center">
              <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400">3</span>
              <span class="ml-2 text-sm font-medium">Selesai</span>
            </li>
          </ol>
        </div>
      </div>

      <div class="grid gap-8 lg:grid-cols-3">
        <!-- Order Details -->
        <div class="lg:col-span-2">
          <div class="rounded-2xl bg-white p-6 shadow-sm dark:bg-gray-800">
            <h2 class="mb-6 text-xl font-semibold text-gray-900 dark:text-white">Detail Pesanan</h2>

            <div class="space-y-6">
              <div v-for="(item, index) in cartStore.items" :key="index"
                class="flex gap-4 rounded-xl border p-4 dark:border-gray-700">
                <img :src="item.image" :alt="item.name"
                  class="h-24 w-24 rounded-lg object-cover"/>

                <div class="flex-1">
                  <h3 class="font-medium text-gray-900 dark:text-white">{{ item.name }}</h3>
                  <p class="text-sm text-gray-600 dark:text-gray-400">{{ item.type }}</p>

                  <div class="mt-2 flex flex-wrap gap-2">
                    <span v-for="slot in item.timeSlots" :key="slot"
                      class="rounded-full bg-appGreenLight/10 px-3 py-1 text-xs font-medium text-appGreenDark dark:text-appGreenLight">
                      {{ slot }}
                    </span>
                  </div>

                  <div class="mt-3 flex items-center justify-between">
                    <p class="font-medium text-appGreenDark dark:text-appGreenLight">
                      Rp {{ item.totalPrice.toLocaleString() }}
                    </p>
                    <button @click="cartStore.removeItem(index)"
                      class="rounded-full p-1 text-gray-400 transition-colors hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-900/20">
                      <Trash2 class="h-5 w-5" />
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Payment Method -->
          <div class="mt-8 rounded-2xl bg-white p-6 shadow-sm dark:bg-gray-800">
            <h2 class="mb-6 text-xl font-semibold text-gray-900 dark:text-white">Metode Pembayaran</h2>

            <div class="space-y-4">
              <label v-for="method in paymentMethods"
                :class="[
                  'flex cursor-pointer items-center gap-4 rounded-xl border p-4 transition-all duration-300',
                  selectedPayment === method.id ? 'border-appGreenLight bg-appGreenLight/5 ring-2 ring-appGreenLight' : 'border-gray-200'
                ]">
                <div class="flex-1">
                  <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                      <input type="radio" :value="method.id" v-model="selectedPayment"
                        class="h-4 w-4 text-appGreenLight focus:ring-appGreenLight"/>
                      <img :src="method.icon" :alt="method.name" class="h-8 w-8 object-contain"/>
                      <span class="font-medium text-gray-900 dark:text-white">{{ method.name }}</span>
                    </div>
                    <span class="text-sm text-gray-500">{{ method.processingTime }}</span>
                  </div>
                </div>
              </label>
            </div>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
          <div class="sticky top-8 rounded-2xl bg-white p-6 shadow-sm dark:bg-gray-800">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Ringkasan</h2>
              <button class="lg:hidden" @click="toggleSummary">
                <ChevronDown :class="{'rotate-180': isSummaryExpanded}" class="h-5 w-5 transition-transform" />
              </button>
            </div>

            <div class="text-sm text-gray-600 dark:text-gray-400 mb-4">
              {{ cartStore.items.length }} item{{ cartStore.items.length > 1 ? 's' : '' }}
            </div>

            <div class="space-y-4">
              <div class="flex justify-between text-gray-600 dark:text-gray-400">
                <span>Subtotal</span>
                <span>Rp {{ cartStore.total.toLocaleString() }}</span>
              </div>

              <div class="flex justify-between text-gray-600 dark:text-gray-400">
                <span>Biaya Admin</span>
                <span>Rp {{ adminFee.toLocaleString() }}</span>
              </div>

              <div class="border-t pt-4 dark:border-gray-700">
                <div class="flex justify-between text-lg font-semibold">
                  <span class="text-gray-900 dark:text-white">Total</span>
                  <span class="text-appGreenDark dark:text-appGreenLight">
                    Rp {{ (cartStore.total + adminFee).toLocaleString() }}
                  </span>
                </div>
              </div>

              <button @click="processCheckout"
                :disabled="isProcessing || !selectedPayment"
                class="mt-6 flex w-full items-center justify-center gap-2 rounded-xl bg-appGreenLight px-6 py-3 font-semibold text-white transition-all hover:bg-appGreenMedium disabled:cursor-not-allowed disabled:opacity-50">
                <Loader2 v-if="isProcessing" class="h-5 w-5 animate-spin" />
                <CreditCard v-else class="h-5 w-5" />
                {{ isProcessing ? 'Memproses...' : 'Bayar Sekarang' }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Success Dialog -->
    <AnimatedDialog :open="showSuccessDialog" @close="closeSuccessDialog">
      <div class="text-center">
        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-green-100 dark:bg-green-900">
          <CheckCircle class="h-8 w-8 text-green-600 dark:text-green-400" />
        </div>
        <h3 class="mb-2 text-xl font-semibold text-gray-900 dark:text-white">Pembayaran Berhasil!</h3>
        <p class="mb-6 text-gray-600 dark:text-gray-400">Terima kasih telah melakukan pemesanan</p>
        <button @click="goToBookings"
          class="w-full rounded-xl bg-appGreenLight px-6 py-3 font-semibold text-white transition-colors hover:bg-appGreenMedium">
          Lihat Pesanan Saya
        </button>
      </div>
    </AnimatedDialog>

    <!-- Add estimated delivery time -->
    <div class="mt-4 rounded-lg bg-blue-50 p-4 dark:bg-blue-900/20">
      <div class="flex items-center gap-2">
        <Clock class="h-5 w-5 text-appBlueLight" />
        <p class="text-sm text-gray-600 dark:text-gray-400">
          Estimasi konfirmasi pembayaran: <span class="font-medium text-gray-900 dark:text-white">5-10 menit</span>
        </p>
      </div>
    </div>

    <!-- Add payment method benefits -->
    <div class="mt-4 space-y-2">
      <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
        <Shield class="h-4 w-4" />
        <span>Pembayaran Aman & Terpercaya</span>
      </div>
      <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
        <Clock class="h-4 w-4" />
        <span>Konfirmasi Instan</span>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useCartStore } from '@/Stores/cart';
import { useToast } from '@/Composables/useToast';
import { router } from '@inertiajs/vue3';
import {
    Trash2,
    CreditCard,
    Loader2,
    ChevronDown
} from 'lucide-vue-next';

const props = defineProps({
    auth: {
        type: Object,
        required: true
    },
    paymentMethods: {
        type: Array,
        required: true
    },
    adminFee: {
        type: Number,
        required: true
    }
});

const cartStore = useCartStore();
const { addToast } = useToast();

const selectedPayment = ref('');
const isProcessing = ref(false);

// Ensure cart is loaded
onMounted(() => {
    cartStore.loadFromStorage();

    // Only redirect if cart is actually empty
    if (cartStore.items.length === 0) {
        addToast({
            title: 'Warning',
            message: 'Keranjang belanja Anda kosong',
            type: 'warning',
            duration: 3000
        });
        router.visit('/venues');
    }
});

const processCheckout = async () => {
    if (!selectedPayment.value) {
        addToast({
            title: 'Warning',
            message: 'Pilih metode pembayaran terlebih dahulu',
            type: 'warning'
        });
        return;
    }

    try {
        isProcessing.value = true;
        // Process payment logic here

        // Clear cart after successful payment
        cartStore.clearCart();

        router.visit('/payment-success');
    } catch (error) {
        addToast({
            title: 'Error',
            message: 'Gagal memproses pembayaran',
            type: 'error'
        });
    } finally {
        isProcessing.value = false;
    }
};
</script>
