<script setup>
import { ref, computed } from 'vue';
import {
  CreditCard, Wallet, Plus, Trash2, Clock,
  CheckCircle2, AlertCircle, Building, Receipt,
  Search, Calendar, Filter
} from 'lucide-vue-next';
import DeleteConfirmationModal from '@/Components/Settings/DeleteConfirmationModal.vue';
import FeedbackToast from '@/Components/Settings/FeedbackToast.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// Dummy data
const paymentMethods = ref([
  {
    id: 1,
    type: 'credit_card',
    name: 'Visa',
    last4: '4242',
    expiry: '12/25',
    isDefault: true
  },
  {
    id: 2,
    type: 'bank',
    name: 'Bank BCA',
    last4: '9876',
    isDefault: false
  }
]);

const recentTransactions = ref([
  {
    id: 1,
    date: '2024-03-15',
    venue: 'Lapangan Futsal Bintang',
    amount: 250000,
    status: 'success',
    paymentMethod: 'Visa •••• 4242'
  },
  {
    id: 2,
    date: '2024-03-10',
    venue: 'GOR Badminton Sejahtera',
    amount: 180000,
    status: 'success',
    paymentMethod: 'Bank BCA'
  },
  {
    id: 3,
    date: '2024-03-05',
    venue: 'Lapangan Basket Champion',
    amount: 300000,
    status: 'failed',
    paymentMethod: 'Visa •••• 4242'
  }
]);

const showAddPaymentModal = ref(false);
const isAddingPayment = ref(false);

const toggleAddPaymentModal = () => {
  showAddPaymentModal.value = !showAddPaymentModal.value;
};

const setDefaultPaymentMethod = (id) => {
  paymentMethods.value = paymentMethods.value.map(method => ({
    ...method,
    isDefault: method.id === id
  }));
};

const removePaymentMethod = (id) => {
  paymentMethods.value = paymentMethods.value.filter(method => method.id !== id);
};

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR'
  }).format(amount);
};

// Add payment method form state
const newPaymentForm = ref({
  type: 'credit_card',
  number: '',
  expiry: '',
  cvv: '',
  name: ''
});

// Add loading states
const isLoading = ref(false);
const activeSection = ref('payment_methods'); // or 'transactions'

// Add filtering for transactions
const transactionFilters = ref({
  status: 'all',
  dateRange: 'last_30_days',
  search: ''
});

// Add pagination for transactions
const currentPage = ref(1);
const itemsPerPage = 5;

// Add success/error feedback
const showFeedback = ref(false);
const feedbackMessage = ref('');
const feedbackType = ref('success');

// Add confirmation before delete
const showDeleteConfirm = ref(false);
const paymentMethodToDelete = ref(null);

// Filters
const filteredTransactions = computed(() => {
  let filtered = [...recentTransactions.value];

  // Apply search
  if (transactionFilters.value.search) {
    filtered = filtered.filter(t =>
      t.venue.toLowerCase().includes(transactionFilters.value.search.toLowerCase())
    );
  }

  // Apply status filter
  if (transactionFilters.value.status !== 'all') {
    filtered = filtered.filter(t => t.status === transactionFilters.value.status);
  }

  // Apply date filter (simplified for demo)
  // In real app, use proper date filtering

  return filtered;
});

const paginatedTransactions = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  return filteredTransactions.value.slice(start, start + itemsPerPage);
});

const totalPages = computed(() =>
  Math.ceil(filteredTransactions.value.length / itemsPerPage)
);

// Add computed properties for totals
const totalTransactions = computed(() => {
  return recentTransactions.value.reduce((total, transaction) => {
    return transaction.status === 'success' ? total + transaction.amount : total;
  }, 0);
});

const monthlyTotal = computed(() => {
  const currentMonth = new Date().getMonth();
  return recentTransactions.value
    .filter(transaction => {
      const transactionMonth = new Date(transaction.date).getMonth();
      return transactionMonth === currentMonth && transaction.status === 'success';
    })
    .reduce((total, transaction) => total + transaction.amount, 0);
});

// Methods
const showToast = (message, type = 'success') => {
  feedbackMessage.value = message;
  feedbackType.value = type;
  showFeedback.value = true;
  setTimeout(() => {
    showFeedback.value = false;
  }, 3000);
};

const confirmDelete = (method) => {
  paymentMethodToDelete.value = method;
  showDeleteConfirm.value = true;
};

const handleDelete = () => {
  if (paymentMethodToDelete.value) {
    removePaymentMethod(paymentMethodToDelete.value.id);
    showDeleteConfirm.value = false;
    paymentMethodToDelete.value = null;
    showToast('Metode pembayaran berhasil dihapus');
  }
};

const setAsDefault = (method) => {
  setDefaultPaymentMethod(method.id);
  showToast(`${method.name} telah diatur sebagai metode pembayaran utama`);
};
</script>

<template>
  <AuthenticatedLayout>
    <div class="space-y-8 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Header -->
      <div class="text-center sm:text-left">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
          Pengaturan Pembayaran
        </h2>
        <p class="mt-2 text-base text-gray-600 dark:text-gray-400">
          Kelola metode pembayaran dan lihat riwayat transaksi Anda
        </p>
      </div>

      <!-- Add Quick Actions Bar -->
      <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
        <div class="flex gap-3 w-full sm:w-auto">
          <button
            v-for="section in ['payment_methods', 'transactions']"
            :key="section"
            @click="activeSection = section"
            :class="[
              'px-6 py-3 rounded-xl text-sm font-semibold transition-all duration-200 flex-1 sm:flex-none',
              activeSection === section
                ? 'bg-gradient-to-r from-appGreenLight to-appGreenMedium text-white shadow-lg shadow-appGreenLight/20 transform hover:scale-105'
                : 'text-gray-600 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700'
            ]"
          >
            {{ section === 'payment_methods' ? 'Metode Pembayaran' : 'Transaksi' }}
          </button>
        </div>

        <!-- Quick Stats -->
        <div class="flex gap-6 w-full sm:w-auto">
          <div class="flex-1 sm:flex-none bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Transaksi</div>
            <div class="mt-1 text-xl font-bold text-gray-900 dark:text-white">{{ formatCurrency(totalTransactions) }}</div>
          </div>
          <div class="flex-1 sm:flex-none bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Bulan Ini</div>
            <div class="mt-1 text-xl font-bold text-gray-900 dark:text-white">{{ formatCurrency(monthlyTotal) }}</div>
          </div>
        </div>
      </div>

      <!-- Feedback Toast -->
      <div
        v-if="showFeedback"
        :class="[
          'fixed bottom-4 right-4 p-4 rounded-xl shadow-xl transition-all transform duration-300',
          feedbackType === 'success' ? 'bg-gradient-to-r from-green-500 to-green-600' : 'bg-gradient-to-r from-red-500 to-red-600',
          'text-white backdrop-blur-sm'
        ]"
      >
        {{ feedbackMessage }}
      </div>

      <!-- Transaction Filters -->
      <div v-if="activeSection === 'transactions'" class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="flex flex-col sm:flex-row gap-4">
          <div class="relative flex-1">
            <Search class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" />
            <input
              v-model="transactionFilters.search"
              type="text"
              placeholder="Cari transaksi..."
              class="w-full rounded-xl border-2 pl-12 pr-4 py-3 focus:border-appGreenLight focus:ring-appGreenLight dark:border-gray-700 dark:bg-gray-800 transition-all duration-200"
            />
          </div>

          <select
            v-model="transactionFilters.status"
            class="rounded-xl border-2 px-4 py-3 focus:border-appGreenLight focus:ring-appGreenLight dark:border-gray-700 dark:bg-gray-800 transition-all duration-200"
          >
            <option value="all">Semua Status</option>
            <option value="success">Berhasil</option>
            <option value="failed">Gagal</option>
          </select>

          <select
            v-model="transactionFilters.dateRange"
            class="rounded-xl border-2 px-4 py-3 focus:border-appGreenLight focus:ring-appGreenLight dark:border-gray-700 dark:bg-gray-800 transition-all duration-200"
          >
            <option value="last_30_days">30 Hari Terakhir</option>
            <option value="last_3_months">3 Bulan Terakhir</option>
            <option value="last_year">1 Tahun Terakhir</option>
          </select>
        </div>
      </div>

      <!-- Empty States -->
      <div
        v-if="!paymentMethods.length && activeSection === 'payment_methods'"
        class="flex flex-col items-center justify-center rounded-xl border-2 border-dashed p-12 bg-gray-50 dark:bg-gray-800/50"
      >
        <CreditCard class="h-16 w-16 text-gray-400 animate-pulse" />
        <h3 class="mt-4 text-xl font-semibold">Belum ada metode pembayaran</h3>
        <p class="mt-2 text-base text-gray-500 text-center max-w-md">
          Tambahkan metode pembayaran untuk memudahkan transaksi Anda
        </p>
        <button
          @click="toggleAddPaymentModal"
          class="mt-6 inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-appGreenLight to-appGreenMedium px-6 py-3 text-white font-semibold shadow-lg shadow-appGreenLight/20 hover:shadow-xl transition-all duration-200 transform hover:scale-105"
        >
          <Plus class="h-5 w-5" />
          Tambah Sekarang
        </button>
      </div>

      <!-- Payment Methods Section -->
      <div class="rounded-xl border border-gray-200 bg-white p-8 dark:border-gray-700 dark:bg-gray-800 shadow-sm">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            Metode Pembayaran
          </h3>
          <button
            @click="toggleAddPaymentModal"
            class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-appGreenLight to-appGreenMedium px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-appGreenLight/20 transition-all duration-200 hover:shadow-xl transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-appGreenLight focus:ring-offset-2 dark:focus:ring-offset-gray-900"
          >
            <Plus class="h-5 w-5" />
            Tambah Metode Pembayaran
          </button>
        </div>

        <!-- Payment Methods List -->
        <div class="mt-8 space-y-4">
          <div
            v-for="method in paymentMethods"
            :key="method.id"
            class="flex items-center justify-between rounded-xl border border-gray-100 p-6 hover:border-appGreenLight transition-all duration-200 dark:border-gray-700"
          >
            <div class="flex items-center gap-6">
              <div class="rounded-xl bg-gray-100 p-3 dark:bg-gray-700">
                <component
                  :is="method.type === 'credit_card' ? CreditCard : Building"
                  class="h-6 w-6 text-gray-500 dark:text-gray-400"
                />
              </div>
              <div>
                <div class="flex items-center gap-3">
                  <span class="font-semibold text-gray-900 dark:text-white text-lg">
                    {{ method.name }}
                  </span>
                  <span class="text-base text-gray-500 dark:text-gray-400">
                    •••• {{ method.last4 }}
                  </span>
                  <span
                    v-if="method.expiry"
                    class="text-base text-gray-500 dark:text-gray-400"
                  >
                    ({{ method.expiry }})
                  </span>
                </div>
                <div
                  v-if="method.isDefault"
                  class="mt-1 text-sm font-medium text-appGreenMedium dark:text-appGreenLight"
                >
                  Metode Pembayaran Utama
                </div>
              </div>
            </div>
            <div class="flex items-center gap-3">
              <button
                v-if="!method.isDefault"
                @click="setDefaultPaymentMethod(method.id)"
                class="rounded-xl p-3 text-gray-500 transition-all duration-200 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300"
              >
                <Wallet class="h-5 w-5" />
              </button>
              <button
                @click="confirmDelete(method)"
                class="rounded-xl p-3 text-red-500 transition-all duration-200 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-900/20"
              >
                <Trash2 class="h-5 w-5" />
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Transactions -->
      <div class="rounded-xl border border-gray-200 bg-white p-8 dark:border-gray-700 dark:bg-gray-800 shadow-sm">
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            Riwayat Transaksi
          </h3>
          <button class="text-sm font-medium text-appBlueMedium hover:text-appBlueDark dark:text-appBlueLight transition-colors duration-200">
            Lihat Semua
          </button>
        </div>

        <div class="mt-8 space-y-4">
          <div
            v-for="transaction in paginatedTransactions"
            :key="transaction.id"
            class="flex items-center justify-between rounded-xl border border-gray-100 p-6 hover:border-appGreenLight transition-all duration-200 dark:border-gray-700"
          >
            <div class="flex items-center gap-6">
              <div
                :class="[
                  'rounded-xl p-3',
                  transaction.status === 'success'
                    ? 'bg-green-100 dark:bg-green-900/20'
                    : 'bg-red-100 dark:bg-red-900/20'
                ]"
              >
                <component
                  :is="transaction.status === 'success' ? CheckCircle2 : AlertCircle"
                  :class="[
                    'h-6 w-6',
                    transaction.status === 'success'
                      ? 'text-green-600 dark:text-green-400'
                      : 'text-red-600 dark:text-red-400'
                  ]"
                />
              </div>
              <div>
                <div class="font-semibold text-gray-900 dark:text-white text-lg">
                  {{ transaction.venue }}
                </div>
                <div class="mt-2 flex items-center gap-3 text-base text-gray-500 dark:text-gray-400">
                  <Clock class="h-5 w-5" />
                  {{ transaction.date }}
                  <span class="text-gray-300 dark:text-gray-600">•</span>
                  <Receipt class="h-5 w-5" />
                  {{ transaction.paymentMethod }}
                </div>
              </div>
            </div>
            <div>
              <div
                :class="[
                  'text-right font-bold text-lg',
                  transaction.status === 'success'
                    ? 'text-gray-900 dark:text-white'
                    : 'text-red-600 dark:text-red-400'
                ]"
              >
                {{ formatCurrency(transaction.amount) }}
              </div>
              <div
                :class="[
                  'mt-2 text-base font-medium',
                  transaction.status === 'success'
                    ? 'text-green-600 dark:text-green-400'
                    : 'text-red-600 dark:text-red-400'
                ]"
              >
                {{ transaction.status === 'success' ? 'Berhasil' : 'Gagal' }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="totalPages > 1" class="flex justify-center gap-3 mt-8">
        <button
          v-for="page in totalPages"
          :key="page"
          @click="currentPage = page"
          :class="[
            'px-4 py-2 rounded-xl text-base font-medium transition-all duration-200',
            currentPage === page
              ? 'bg-gradient-to-r from-appGreenLight to-appGreenMedium text-white shadow-lg shadow-appGreenLight/20'
              : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
          ]"
        >
          {{ page }}
        </button>
      </div>
    </div>
  </AuthenticatedLayout>

  <!-- Modals and Toasts -->
  <DeleteConfirmationModal
    v-if="showDeleteConfirm"
    :show="showDeleteConfirm"
    :payment-method="paymentMethodToDelete"
    @close="showDeleteConfirm = false"
    @confirm="handleDelete"
  />

  <FeedbackToast
    v-if="showFeedback"
    :show="showFeedback"
    :message="feedbackMessage"
    :type="feedbackType"
    @close="showFeedback = false"
  />
</template>
