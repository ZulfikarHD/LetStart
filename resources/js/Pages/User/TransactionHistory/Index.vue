<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {
  Receipt, Search, Filter, Calendar,
  CreditCard, Building2, Clock, Download,
  ChevronDown, CheckCircle2, XCircle, AlertCircle
} from 'lucide-vue-next';

// Dummy data for transactions
const transactions = ref([
  {
    id: 'TRX-001',
    venueName: 'GOR Sahabat Futsal',
    date: '2024-02-15',
    time: '18:00-20:00',
    amount: 250000,
    status: 'success',
    paymentMethod: 'QRIS',
    sport: 'Futsal'
  },
  {
    id: 'TRX-002',
    venueName: 'PB Duta Badminton',
    date: '2024-02-10',
    time: '16:00-18:00',
    amount: 180000,
    status: 'failed',
    paymentMethod: 'Bank Transfer',
    sport: 'Badminton'
  },
  // ... more transactions
]);

// Filter states
const searchQuery = ref('');
const dateRange = ref({ start: '', end: '' });
const selectedStatus = ref('all');
const selectedSport = ref('all');
const selectedPaymentMethod = ref('all');

// Filter options
const statusOptions = [
  { value: 'all', label: 'Semua Status' },
  { value: 'success', label: 'Berhasil' },
  { value: 'pending', label: 'Menunggu' },
  { value: 'failed', label: 'Gagal' }
];

const sportOptions = [
  { value: 'all', label: 'Semua Olahraga' },
  { value: 'Futsal', label: 'Futsal' },
  { value: 'Badminton', label: 'Badminton' },
  { value: 'Basket', label: 'Basket' },
  { value: 'Voli', label: 'Voli' }
];

const paymentOptions = [
  { value: 'all', label: 'Semua Metode' },
  { value: 'QRIS', label: 'QRIS' },
  { value: 'Bank Transfer', label: 'Transfer Bank' },
  { value: 'E-Wallet', label: 'E-Wallet' }
];

// Computed filtered transactions
const filteredTransactions = computed(() => {
  return transactions.value.filter(trx => {
    const matchesSearch = trx.venueName.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                         trx.id.toLowerCase().includes(searchQuery.value.toLowerCase());
    const matchesStatus = selectedStatus.value === 'all' || trx.status === selectedStatus.value;
    const matchesSport = selectedSport.value === 'all' || trx.sport === selectedSport.value;
    const matchesPayment = selectedPaymentMethod.value === 'all' || trx.paymentMethod === selectedPaymentMethod.value;

    // Add date range filter when implemented
    return matchesSearch && matchesStatus && matchesSport && matchesPayment;
  });
});

const getStatusColor = (status) => {
  const colors = {
    success: 'text-green-500 bg-green-50 dark:bg-green-500/10',
    pending: 'text-yellow-500 bg-yellow-50 dark:bg-yellow-500/10',
    failed: 'text-red-500 bg-red-50 dark:bg-red-500/10'
  };
  return colors[status] || 'text-gray-500 bg-gray-50 dark:bg-gray-500/10';
};

const getStatusIcon = (status) => {
  const icons = {
    success: CheckCircle2,
    pending: AlertCircle,
    failed: XCircle
  };
  return icons[status] || AlertCircle;
};

const exportTransactions = () => {
  // Implement export functionality
  console.log('Exporting transactions...');
};

const isLoading = ref(false);

const showTransactionDetail = (trx) => {
  // Implement modal or slide-over for transaction details
};

const hasActiveFilters = computed(() => {
  return searchQuery.value !== '' ||
         selectedStatus.value !== 'all' ||
         selectedSport.value !== 'all' ||
         selectedPaymentMethod.value !== 'all' ||
         dateRange.value.start !== '' ||
         dateRange.value.end !== '';
});

const clearFilters = () => {
  searchQuery.value = '';
  selectedStatus.value = 'all';
  selectedSport.value = 'all';
  selectedPaymentMethod.value = 'all';
  dateRange.value = { start: '', end: '' };
};
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Riwayat Transaksi" />

    <div class="max-w-7xl mx-auto p-6 space-y-8">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-semibold text-gray-900 dark:text-white flex items-center gap-2">
            <Receipt class="h-7 w-7 text-appGreenLight" />
            Riwayat Transaksi
          </h1>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Lihat dan kelola semua transaksi Anda
          </p>
        </div>

        <button
          @click="exportTransactions"
          class="flex items-center gap-2 rounded-lg bg-appGreenLight px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-appGreenMedium"
        >
          <Download class="h-4 w-4" />
          Export
        </button>
      </div>

      <!-- Filters -->
      <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
          <!-- Search -->
          <div class="relative flex-1">
            <Search class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" />
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Cari transaksi..."
              class="w-full rounded-lg border border-gray-300 pl-10 pr-4 py-2 focus:border-appGreenLight focus:ring-appGreenLight dark:border-gray-600 dark:bg-gray-700"
            />
          </div>

          <!-- Filter Dropdowns -->
          <div class="flex flex-wrap gap-3">
            <select
              v-model="selectedStatus"
              class="rounded-lg border border-gray-300 px-4 py-2 focus:border-appGreenLight focus:ring-appGreenLight dark:border-gray-600 dark:bg-gray-700"
            >
              <option v-for="option in statusOptions" :key="option.value" :value="option.value">
                {{ option.label }}
              </option>
            </select>

            <select
              v-model="selectedSport"
              class="rounded-lg border border-gray-300 px-4 py-2 focus:border-appGreenLight focus:ring-appGreenLight dark:border-gray-600 dark:bg-gray-700"
            >
              <option v-for="option in sportOptions" :key="option.value" :value="option.value">
                {{ option.label }}
              </option>
            </select>

            <select
              v-model="selectedPaymentMethod"
              class="rounded-lg border border-gray-300 px-4 py-2 focus:border-appGreenLight focus:ring-appGreenLight dark:border-gray-600 dark:bg-gray-700"
            >
              <option v-for="option in paymentOptions" :key="option.value" :value="option.value">
                {{ option.label }}
              </option>
            </select>
          </div>

          <!-- Add this in the filters section -->
          <div class="flex gap-3 items-center">
            <div class="flex items-center gap-2">
              <Calendar class="h-5 w-5 text-gray-400" />
              <input
                v-model="dateRange.start"
                type="date"
                class="rounded-lg border border-gray-300 px-4 py-2 focus:border-appGreenLight focus:ring-appGreenLight dark:border-gray-600 dark:bg-gray-700"
              />
              <span class="text-gray-500">sampai</span>
              <input
                v-model="dateRange.end"
                type="date"
                class="rounded-lg border border-gray-300 px-4 py-2 focus:border-appGreenLight focus:ring-appGreenLight dark:border-gray-600 dark:bg-gray-700"
              />
            </div>
          </div>

          <!-- Add clear filters button -->
          <button
            v-if="hasActiveFilters"
            @click="clearFilters"
            class="flex items-center gap-2 text-sm text-gray-500 hover:text-appGreenLight transition-colors"
          >
            <XCircle class="h-4 w-4" />
            Reset Filter
          </button>
        </div>
      </div>

      <!-- Transactions List -->
      <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="border-b border-gray-200 dark:border-gray-700">
                <th class="px-6 py-4 text-left text-sm font-medium text-gray-500 dark:text-gray-400">ID Transaksi</th>
                <th class="px-6 py-4 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Venue</th>
                <th class="px-6 py-4 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal & Waktu</th>
                <th class="px-6 py-4 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Total</th>
                <th class="px-6 py-4 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Metode</th>
                <th class="px-6 py-4 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
              <tr
                v-for="trx in filteredTransactions"
                :key="trx.id"
                @click="showTransactionDetail(trx)"
                class="group hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition-colors duration-200"
              >
                <td class="whitespace-nowrap px-6 py-4">
                  <span class="font-medium text-gray-900 dark:text-white">{{ trx.id }}</span>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <Building2 class="h-5 w-5 text-gray-400" />
                    <div>
                      <div class="font-medium text-gray-900 dark:text-white">{{ trx.venueName }}</div>
                      <div class="text-sm text-gray-500 dark:text-gray-400">{{ trx.sport }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <Clock class="h-5 w-5 text-gray-400" />
                    <div>
                      <div class="font-medium text-gray-900 dark:text-white">{{ trx.date }}</div>
                      <div class="text-sm text-gray-500 dark:text-gray-400">{{ trx.time }}</div>
                    </div>
                  </div>
                </td>
                <td class="whitespace-nowrap px-6 py-4">
                  <span class="font-medium text-gray-900 dark:text-white">
                    Rp {{ trx.amount.toLocaleString('id-ID') }}
                  </span>
                </td>
                <td class="whitespace-nowrap px-6 py-4">
                  <div class="flex items-center gap-2">
                    <CreditCard class="h-5 w-5 text-gray-400" />
                    <span class="text-gray-500 dark:text-gray-400">{{ trx.paymentMethod }}</span>
                  </div>
                </td>
                <td class="whitespace-nowrap px-6 py-4">
                  <div class="flex items-center gap-2">
                    <span
                      :class="[
                        'inline-flex items-center gap-1 rounded-full px-2 py-1 text-xs font-medium',
                        getStatusColor(trx.status)
                      ]"
                    >
                      <component :is="getStatusIcon(trx.status)" class="h-4 w-4" />
                      {{ trx.status === 'success' ? 'Berhasil' : trx.status === 'pending' ? 'Menunggu' : 'Gagal' }}
                    </span>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Empty State -->
        <div
          v-if="filteredTransactions.length === 0"
          class="flex flex-col items-center justify-center py-12"
        >
          <Receipt class="h-12 w-12 text-gray-400" />
          <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada transaksi</h3>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Belum ada transaksi yang sesuai dengan filter yang dipilih
          </p>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
