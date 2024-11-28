<script setup>
import { ref, computed } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import { ChevronRight, FileText, Shield, Scale, BookOpen } from 'lucide-vue-next'

const props = defineProps({
  auth: {
    type: Object,
    default: () => ({ user: null })
  },
  lastUpdated: String
})

// Compute which layout to use based on auth status
const Layout = computed(() => {
  return props.auth?.user ? AuthenticatedLayout : GuestLayout;
});

const sections = ref([
  {
    id: 'umum',
    title: 'Ketentuan Umum',
    icon: BookOpen,
    content: [
      'Dengan menggunakan aplikasi ini, Anda menyetujui untuk terikat dengan syarat dan ketentuan yang berlaku.',
      'Kami berhak untuk mengubah syarat dan ketentuan ini sewaktu-waktu tanpa pemberitahuan terlebih dahulu.',
      'Pengguna wajib memberikan informasi yang akurat dan valid saat mendaftar.'
    ]
  },
  {
    id: 'pemesanan',
    title: 'Ketentuan Pemesanan',
    icon: Scale,
    content: [
      'Pemesanan venue hanya valid setelah pembayaran penuh diterima.',
      'Pembatalan pemesanan harus dilakukan minimal 24 jam sebelum jadwal.',
      'Pengembalian dana akan diproses sesuai dengan kebijakan pembatalan yang berlaku.',
      'Pengguna bertanggung jawab atas kebenaran data pemesanan.',
      'Venue berhak menolak pemesanan jika terjadi force majeure.'
    ]
  },
  {
    id: 'pembayaran',
    title: 'Pembayaran dan Pengembalian Dana',
    icon: Shield,
    content: [
      'Semua pembayaran diproses melalui gateway pembayaran yang aman.',
      'Harga yang tercantum sudah termasuk pajak dan biaya layanan.',
      'Pengembalian dana akan diproses dalam waktu 3-5 hari kerja.',
      'Biaya admin tidak dapat dikembalikan untuk pembatalan pemesanan.',
      'Pembayaran yang sudah diverifikasi tidak dapat dibatalkan.'
    ]
  }
])
</script>

<template>
  <Layout>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 px-4 sm:px-6 lg:px-8">
      <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
          <FileText class="h-12 w-12 text-appGreenMedium mx-auto mb-4" />
          <h1 class="text-3xl font-bold text-appBlack dark:text-white mb-2">
            Syarat dan Ketentuan
          </h1>
          <p class="text-gray-600 dark:text-gray-400">
            Terakhir diperbarui: {{ lastUpdated }}
          </p>
        </div>

        <!-- Quick Navigation -->
        <nav class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 mb-8">
          <h2 class="font-semibold text-appBlack dark:text-white mb-4">Daftar Isi:</h2>
          <ul class="space-y-2">
            <li v-for="section in sections" :key="section.id">
              <a
                :href="`#${section.id}`"
                class="flex items-center text-gray-600 dark:text-gray-400 hover:text-appGreenMedium dark:hover:text-appGreenLight transition-colors"
              >
                <ChevronRight class="h-4 w-4 mr-2" />
                {{ section.title }}
              </a>
            </li>
          </ul>
        </nav>

        <!-- Content Sections -->
        <div class="space-y-8">
          <section
            v-for="section in sections"
            :key="section.id"
            :id="section.id"
            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 scroll-mt-16 transform transition-all duration-300 hover:shadow-md"
          >
            <div class="flex items-center gap-3 mb-4">
              <component
                :is="section.icon"
                class="h-6 w-6 text-appGreenMedium dark:text-appGreenLight"
              />
              <h2 class="text-xl font-semibold text-appBlack dark:text-white">
                {{ section.title }}
              </h2>
            </div>
            <ul class="space-y-3">
              <li
                v-for="(item, index) in section.content"
                :key="index"
                class="text-gray-600 dark:text-gray-400 flex items-start animate-fadeIn"
              >
                <span class="inline-block w-2 h-2 rounded-full bg-appGreenLight mt-2 mr-3"></span>
                {{ item }}
              </li>
            </ul>
          </section>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center">
          <p class="text-gray-600 dark:text-gray-400 text-sm">
            Untuk pertanyaan lebih lanjut, silakan hubungi
            <a
              href="/contact"
              class="text-appBlueMedium hover:text-appBlueLight dark:text-appBlueLight dark:hover:text-appBlueLight transition-colors"
            >
              tim dukungan kami
            </a>
          </p>
        </div>
      </div>
    </div>
  </Layout>
</template>

<style scoped>
.scroll-mt-16 {
  scroll-margin-top: 4rem;
}

.animate-fadeIn {
  animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Add smooth scrolling behavior */
html {
  scroll-behavior: smooth;
}
</style>
