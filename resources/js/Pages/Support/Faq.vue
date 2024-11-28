<script setup>
import { ref, computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { ChevronDown, ChevronUp, Search, MessageCircleQuestion } from 'lucide-vue-next';

const page = usePage();
const isAuthenticated = computed(() => page.props.auth.user !== null);

// Add search functionality
const searchQuery = ref('');
const faqs = ref([
  {
    id: 1,
    question: 'Bagaimana cara melakukan booking lapangan?',
    answer: 'Untuk melakukan booking lapangan, ikuti langkah berikut:\n1. Pilih venue yang diinginkan\n2. Pilih lapangan dan jadwal yang tersedia\n3. Tambahkan ke keranjang atau langsung checkout\n4. Pilih metode pembayaran\n5. Selesaikan pembayaran',
    isOpen: false
  },
  {
    id: 2,
    question: 'Metode pembayaran apa saja yang tersedia?',
    answer: 'Kami menerima pembayaran melalui:\n- GoPay\n- OVO\n- DANA\n- Transfer Bank BCA',
    isOpen: false
  },
  {
    id: 3,
    question: 'Bagaimana jika saya ingin membatalkan booking?',
    answer: 'Pembatalan booking dapat dilakukan minimal 24 jam sebelum jadwal bermain. Biaya yang sudah dibayarkan akan dikembalikan sesuai dengan kebijakan pembatalan yang berlaku.',
    isOpen: false
  },
  {
    id: 4,
    question: 'Apakah saya bisa mengubah jadwal booking?',
    answer: 'Ya, perubahan jadwal dapat dilakukan minimal 24 jam sebelum jadwal bermain, dengan syarat jadwal baru yang diinginkan masih tersedia.',
    isOpen: false
  },
  {
    id: 5,
    question: 'Berapa lama batas waktu pembayaran?',
    answer: 'Batas waktu pembayaran adalah 1 jam setelah proses booking. Jika melewati batas waktu, booking akan otomatis dibatalkan.',
    isOpen: false
  }
]);

// Add categories
const categories = ref([
  { id: 1, name: 'Booking', icon: 'Calendar' },
  { id: 2, name: 'Pembayaran', icon: 'Wallet' },
  { id: 3, name: 'Pembatalan', icon: 'XCircle' },
  { id: 4, name: 'Lainnya', icon: 'MoreHorizontal' },
]);

// Filter FAQs based on search
const filteredFaqs = computed(() => {
  return faqs.value.filter(faq =>
    faq.question.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    faq.answer.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

const toggleFaq = (id) => {
  faqs.value = faqs.value.map(faq => ({
    ...faq,
    isOpen: faq.id === id ? !faq.isOpen : false
  }));
};

// Add feedback functionality
const submitFeedback = (faqId, helpful) => {
  // Implementation for feedback
};
</script>

<template>
  <component :is="isAuthenticated ? AuthenticatedLayout : GuestLayout">
    <Head title="FAQ" />

    <div class="min-h-screen bg-gray-50 py-12 dark:bg-gray-900">
      <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="text-center">
          <h1 class="mb-2 text-3xl font-bold text-gray-900 dark:text-white">
            Pertanyaan yang Sering Diajukan
          </h1>
          <p class="mb-8 text-lg text-gray-600 dark:text-gray-400">
            Temukan jawaban untuk pertanyaan umum seputar layanan kami
          </p>
        </div>

        <!-- Search Bar -->
        <div class="relative mx-auto mb-8 max-w-lg">
          <Search class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" />
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Cari pertanyaan..."
            class="w-full rounded-full border-gray-200 py-3 pl-10 pr-4 focus:border-appGreenLight focus:ring-appGreenLight dark:border-gray-700 dark:bg-gray-800 dark:text-white"
          />
        </div>

        <!-- Categories -->
        <div class="mb-8 grid grid-cols-2 gap-4 sm:grid-cols-4">
          <button
            v-for="category in categories"
            :key="category.id"
            class="flex items-center justify-center rounded-xl border border-gray-200 p-4 transition-all hover:border-appGreenLight hover:bg-appGreenLight/10 dark:border-gray-700 dark:text-white"
          >
            <span class="text-sm font-medium">{{ category.name }}</span>
          </button>
        </div>

        <!-- FAQ List -->
        <div class="space-y-4">
          <div v-for="faq in filteredFaqs" :key="faq.id"
            class="overflow-hidden rounded-xl border border-gray-200 bg-white transition-all duration-200 hover:shadow-md dark:border-gray-700 dark:bg-gray-800">
            <button @click="toggleFaq(faq.id)"
              class="flex w-full items-center justify-between p-6 text-left">
              <span class="text-lg font-medium text-gray-900 dark:text-white">
                {{ faq.question }}
              </span>
              <component
                :is="faq.isOpen ? ChevronUp : ChevronDown"
                class="h-5 w-5 text-gray-500 transition-transform duration-200 dark:text-gray-400"
              />
            </button>

            <div v-show="faq.isOpen"
              class="border-t border-gray-200 px-6 py-4 dark:border-gray-700">
              <p class="whitespace-pre-line text-gray-600 dark:text-gray-400">
                {{ faq.answer }}
              </p>
              <div class="mt-4 flex items-center justify-end space-x-4">
                <span class="text-sm text-gray-500 dark:text-gray-400">Apakah jawaban ini membantu?</span>
                <button
                  @click="submitFeedback(faq.id, true)"
                  class="rounded-full px-4 py-1 text-sm text-appGreenLight hover:bg-appGreenLight/10 transition-colors duration-200"
                >
                  Ya
                </button>
                <button
                  @click="submitFeedback(faq.id, false)"
                  class="rounded-full px-4 py-1 text-sm text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200"
                >
                  Tidak
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Contact Section -->
        <div class="mt-12 rounded-xl bg-gradient-to-br from-appGreenLight/20 to-appBlueLight/20 p-8 text-center backdrop-blur-sm">
          <MessageCircleQuestion class="mx-auto mb-4 h-12 w-12 text-appGreenMedium" />
          <h2 class="mb-2 text-xl font-semibold text-gray-900 dark:text-white">
            Masih Butuh Bantuan?
          </h2>
          <p class="mb-4 text-gray-600 dark:text-gray-400">
            Tim support kami siap membantu Anda 24/7
          </p>
          <div class="flex flex-col space-y-3 sm:flex-row sm:justify-center sm:space-x-4 sm:space-y-0">
            <Link
              href="/contact"
              class="rounded-full bg-appGreenLight px-6 py-2 text-white transition-colors hover:bg-appGreenMedium focus:outline-none focus:ring-2 focus:ring-appGreenLight focus:ring-offset-2"
            >
              Hubungi Kami
            </Link>
            <a
              href="mailto:support@sportsvenue.com"
              class="rounded-full border border-appGreenLight px-6 py-2 text-appGreenLight transition-colors hover:bg-appGreenLight hover:text-white focus:outline-none focus:ring-2 focus:ring-appGreenLight focus:ring-offset-2"
            >
              Email Support
            </a>
          </div>
        </div>
      </div>
    </div>
  </component>
</template>
