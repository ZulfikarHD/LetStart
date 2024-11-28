<script setup>
import { computed, ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { MapPin, Mail, Phone, Instagram, Facebook, Twitter, CheckCircle, AlertCircle } from 'lucide-vue-next';

const props = defineProps({
    auth: {
        type: Object,
        default: () => ({ user: null })
    }
});

// Compute which layout to use based on auth status
const Layout = computed(() => {
    return props.auth?.user ? AuthenticatedLayout : GuestLayout;
});

const teamMembers = [
    {
        name: 'Karlina Ibrahim',
        role: 'Founder & CEO',
        image: '/images/team/karina.jpg',
        description: 'Penggemar olahraga yang berdedikasi untuk membuat olahraga lebih mudah diakses untuk semua orang.'
    },
    {
        name: 'Karlina Ibrahim',
        role: 'Head of Operations',
        image: '/images/team/karlina.jpg',
        description: 'Ahli manajemen operasional dengan pengalaman 10 tahun di industri olahraga.'
    },
    {
        name: 'Zulfikar Hidayatullah',
        role: 'Technical Head',
        image: '/images/team/zul.jpg',
        description: 'Insinyur perangkat lunak dengan passion untuk teknologi dan inovasi.'
    }
];

const features = [
    {
        title: 'Booking Mudah',
        description: 'Proses booking lapangan olahraga yang cepat dan mudah, hanya dalam beberapa klik.',
        icon: 'calendar'
    },
    {
        title: 'Pembayaran Aman',
        description: 'Sistem pembayaran yang aman dan terpercaya dengan berbagai metode pembayaran.',
        icon: 'credit-card'
    },
    {
        title: 'Venue Terpercaya',
        description: 'Ratusan venue olahraga terpercaya yang telah terverifikasi kualitasnya.',
        icon: 'shield'
    }
];

const form = useForm({
  name: '',
  email: '',
  message: ''
});

const isSubmitted = ref(false);
const isLoading = ref(false);

const submitForm = async () => {
  isLoading.value = true;
  // Simulate API call
  await new Promise(resolve => setTimeout(resolve, 1000));
  isLoading.value = false;
  isSubmitted.value = true;

  // Reset after 3 seconds
  setTimeout(() => {
    isSubmitted.value = false;
    form.reset();
  }, 3000);
};
</script>

<template>
    <Layout>
        <Head title="Tentang Kami" />

        <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
            <!-- Hero Section -->
            <section class="relative overflow-hidden bg-appGreenLight py-20 dark:bg-appGreenDark">
                <div class="absolute inset-0 bg-[url('/images/pattern.svg')] opacity-10"></div>
                <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <h1 class="mb-4 text-4xl font-bold text-white sm:text-5xl lg:text-6xl">
                            Memudahkan Akses Olahraga untuk Semua
                        </h1>
                        <p class="mx-auto max-w-2xl text-lg text-white/90">
                            Kami berkomitmen untuk membuat olahraga lebih mudah diakses bagi semua orang melalui platform booking lapangan olahraga yang modern dan terpercaya.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Mission Section -->
            <section class="py-16">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="grid gap-12 lg:grid-cols-2">
                        <div class="flex flex-col justify-center">
                            <h2 class="mb-6 text-3xl font-bold text-gray-900 dark:text-white">
                                Misi Kami
                            </h2>
                            <p class="mb-8 text-lg text-gray-600 dark:text-gray-400">
                                Menjadi platform terdepan dalam memudahkan akses ke fasilitas olahraga di Indonesia. Kami percaya bahwa setiap orang berhak mendapatkan akses mudah ke fasilitas olahraga berkualitas untuk mendukung gaya hidup sehat.
                            </p>
                            <div class="space-y-4">
                                <div v-for="feature in features" :key="feature.title"
                                    class="flex items-start gap-4 rounded-xl bg-white p-4 shadow-sm transition-all hover:shadow-md dark:bg-gray-800">
                                    <div class="rounded-full bg-appGreenLight/10 p-3 text-appGreenDark dark:text-appGreenLight">
                                        <component :is="feature.icon" class="h-6 w-6" />
                                    </div>
                                    <div>
                                        <h3 class="mb-2 font-semibold text-gray-900 dark:text-white">
                                            {{ feature.title }}
                                        </h3>
                                        <p class="text-gray-600 dark:text-gray-400">
                                            {{ feature.description }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="relative">
                            <img src="/images/about-illustration.svg" alt="About Illustration"
                                class="w-full rounded-2xl object-cover shadow-xl" />
                        </div>
                    </div>
                </div>
            </section>

            <!-- Team Section -->
            <section class="bg-white py-16 dark:bg-gray-800">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <h2 class="mb-4 text-3xl font-bold text-gray-900 dark:text-white">
                            Tim Kami
                        </h2>
                        <p class="mx-auto mb-12 max-w-2xl text-lg text-gray-600 dark:text-gray-400">
                            Bertemu dengan tim berdedikasi yang bekerja keras untuk memberikan pengalaman terbaik bagi pengguna kami.
                        </p>
                    </div>

                    <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                        <div v-for="member in teamMembers" :key="member.name"
                            class="group overflow-hidden rounded-2xl bg-gray-50 transition-all hover:-translate-y-1 hover:shadow-xl dark:bg-gray-900">
                            <div class="aspect-[4/3] overflow-hidden">
                                <img :src="member.image" :alt="member.name"
                                    class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-110" />
                            </div>
                            <div class="p-6">
                                <h3 class="mb-1 text-xl font-semibold text-gray-900 dark:text-white">
                                    {{ member.name }}
                                </h3>
                                <p class="mb-4 text-sm font-medium text-appGreenDark dark:text-appGreenLight">
                                    {{ member.role }}
                                </p>
                                <p class="text-gray-600 dark:text-gray-400">
                                    {{ member.description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Contact Section -->
            <section class="py-16">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="grid gap-12 lg:grid-cols-2">
                        <div class="order-2 lg:order-1">
                            <h2 class="mb-6 text-3xl font-bold text-gray-900 dark:text-white">
                                Hubungi Kami
                            </h2>
                            <p class="mb-8 text-lg text-gray-600 dark:text-gray-400">
                                Punya pertanyaan atau masukan? Jangan ragu untuk menghubungi kami.
                            </p>
                            <div class="space-y-6">
                                <div class="flex items-center gap-4">
                                    <MapPin class="h-6 w-6 text-appGreenDark dark:text-appGreenLight" />
                                    <div>
                                        <h3 class="font-medium text-gray-900 dark:text-white">Alamat</h3>
                                        <p class="text-gray-600 dark:text-gray-400">
                                            Jl. Sudirman No. 123, Jakarta Pusat
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <Mail class="h-6 w-6 text-appGreenDark dark:text-appGreenLight" />
                                    <div>
                                        <h3 class="font-medium text-gray-900 dark:text-white">Email</h3>
                                        <p class="text-gray-600 dark:text-gray-400">
                                            info@mainnow.com
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <Phone class="h-6 w-6 text-appGreenDark dark:text-appGreenLight" />
                                    <div>
                                        <h3 class="font-medium text-gray-900 dark:text-white">Telepon</h3>
                                        <p class="text-gray-600 dark:text-gray-400">
                                            +62 21 1234 5678
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 flex gap-4">
                                <a href="#" class="rounded-full bg-gray-100 p-3 text-gray-600 transition-colors hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
                                    <Instagram class="h-6 w-6" />
                                </a>
                                <a href="#" class="rounded-full bg-gray-100 p-3 text-gray-600 transition-colors hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
                                    <Facebook class="h-6 w-6" />
                                </a>
                                <a href="#" class="rounded-full bg-gray-100 p-3 text-gray-600 transition-colors hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
                                    <Twitter class="h-6 w-6" />
                                </a>
                            </div>
                        </div>

                        <div class="order-1 lg:order-2">
                            <form @submit.prevent="submitForm" class="space-y-6 rounded-2xl bg-white p-8 shadow-lg dark:bg-gray-800">
                                <div class="mb-8 text-center">
                                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                                        Kirim Pesan Kepada Kami
                                    </h3>
                                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                                        Kami akan merespons dalam waktu 24 jam
                                    </p>
                                </div>
                                <div>
                                    <label for="name" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Nama <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.name"
                                        type="text"
                                        id="name"
                                        required
                                        :disabled="isLoading"
                                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-700
                                               transition-all duration-200 ease-in-out
                                               focus:border-appGreenLight focus:outline-none focus:ring-2 focus:ring-appGreenLight/20
                                               disabled:opacity-50
                                               dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                                        aria-label="Nama"
                                        aria-required="true"
                                        autocomplete="name"
                                        :aria-invalid="form.errors.name ? 'true' : 'false'"
                                    />
                                </div>
                                <div>
                                    <label for="email" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Email <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.email"
                                        type="email"
                                        id="email"
                                        required
                                        :disabled="isLoading"
                                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-700
                                               transition-all duration-200 ease-in-out
                                               focus:border-appGreenLight focus:outline-none focus:ring-2 focus:ring-appGreenLight/20
                                               disabled:opacity-50
                                               dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                                        aria-label="Email"
                                        aria-required="true"
                                        autocomplete="email"
                                        :aria-invalid="form.errors.email ? 'true' : 'false'"
                                    />
                                </div>
                                <div>
                                    <label for="message" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Pesan <span class="text-red-500">*</span>
                                    </label>
                                    <textarea
                                        v-model="form.message"
                                        id="message"
                                        name="message"
                                        rows="4"
                                        required
                                        :disabled="isLoading"
                                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-700
                                               transition-all duration-200 ease-in-out
                                               focus:border-appGreenLight focus:outline-none focus:ring-2 focus:ring-appGreenLight/20
                                               disabled:opacity-50
                                               dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                                        aria-label="Pesan"
                                        aria-required="true"
                                        :aria-invalid="form.errors.message ? 'true' : 'false'"
                                    ></textarea>
                                </div>
                                <button
                                    type="submit"
                                    :disabled="isLoading || isSubmitted"
                                    class="group relative w-full rounded-xl bg-appGreenLight px-6 py-3 text-center
                                           font-semibold text-white transition-all duration-200
                                           hover:bg-appGreenMedium disabled:opacity-50"
                                >
                                    <span :class="{ 'opacity-0': isLoading || isSubmitted }">
                                        Kirim Pesan
                                    </span>

                                    <!-- Loading Spinner -->
                                    <div v-if="isLoading" class="absolute inset-0 flex items-center justify-center">
                                        <svg class="h-5 w-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>

                                    <!-- Success Check -->
                                    <div v-if="isSubmitted" class="absolute inset-0 flex items-center justify-center">
                                        <CheckCircle class="h-5 w-5 text-white" />
                                    </div>
                                </button>

                                <!-- Success Message -->
                                <div v-if="isSubmitted"
                                     class="animate-fade-in rounded-lg bg-green-50 p-4 text-green-700 dark:bg-green-900/30 dark:text-green-300">
                                    <p class="flex items-center gap-2">
                                        <CheckCircle class="h-5 w-5" />
                                        Pesan Anda telah terkirim! Kami akan segera menghubungi Anda.
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </Layout>
</template>
