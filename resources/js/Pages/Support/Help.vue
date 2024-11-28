<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { ChevronRight, ThumbsUp, ThumbsDown, MessageCircle, Search } from 'lucide-vue-next';

// Props for authentication status
const props = defineProps({
  isAuthenticated: Boolean,
  user: Object,
  faqs: Array,
  popularArticles: Array
});

const searchQuery = ref('');
const showLiveChat = ref(false);

// Computed search results
const searchResults = computed(() => {
  if (!searchQuery.value) return [];
  return [...props.popularArticles, ...props.faqs].filter(item =>
    item.title?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    item.question?.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

// Feedback submission
const submitFeedback = async (articleId, isHelpful) => {
  try {
    await axios.post('/help/feedback', {
      articleId,
      isHelpful
    });
    // Show success notification
  } catch (error) {
    // Handle error
  }
};

// Live chat toggle with auth check
const toggleLiveChat = () => {
  if (!props.isAuthenticated) {
    window.location.href = '/login?redirect=/help';
    return;
  }
  showLiveChat.value = !showLiveChat.value;
};
</script>

<template>
  <Head title="Pusat Bantuan" />

  <component :is="isAuthenticated ? AuthenticatedLayout : GuestLayout">
    <div class="min-h-screen bg-gray-50 py-12 dark:bg-gray-900">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Hero Section -->
        <div class="text-center">
          <h1 class="mb-4 text-4xl font-bold tracking-tight text-gray-900 dark:text-white">
            Pusat Bantuan
          </h1>
          <p class="mx-auto mb-8 max-w-2xl text-lg text-gray-600 dark:text-gray-400">
            Temukan jawaban untuk semua pertanyaan Anda seputar MainNow
          </p>

          <!-- Search Component -->
          <div class="relative mx-auto mb-12 max-w-2xl">
            <Search class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" />
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Cari bantuan..."
              class="w-full rounded-full border-gray-200 py-3 pl-12 pr-4 shadow-sm transition-all focus:border-appGreenLight focus:ring-appGreenLight dark:border-gray-700 dark:bg-gray-800 dark:text-white"
            />
            <!-- Search Results -->
            <div v-if="searchResults.length > 0"
                 class="absolute mt-2 w-full rounded-lg border bg-white p-4 shadow-lg dark:border-gray-700 dark:bg-gray-800">
              <div v-for="result in searchResults" :key="result.id"
                   class="mb-3 last:mb-0">
                <Link :href="result.url"
                      class="block rounded-lg p-3 transition-colors hover:bg-gray-50 dark:hover:bg-gray-700">
                  <h3 class="font-medium text-gray-900 dark:text-white">
                    {{ result.title || result.question }}
                  </h3>
                  <p v-if="result.category" class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ result.category }}
                  </p>
                </Link>
              </div>
            </div>
          </div>

          <!-- Popular Articles Grid -->
          <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            <div v-for="article in popularArticles" :key="article.id"
                 class="rounded-xl bg-white p-6 shadow-sm transition-all hover:shadow-md dark:bg-gray-800">
              <h3 class="mb-2 text-lg font-medium text-gray-900 dark:text-white">
                {{ article.title }}
              </h3>
              <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                {{ article.category }}
              </p>
              <Link :href="article.url"
                    class="inline-flex items-center text-appGreenLight hover:text-appGreenMedium">
                Baca selengkapnya
                <ChevronRight class="ml-1 h-4 w-4" />
              </Link>
            </div>
          </div>

          <!-- Guest CTA Section -->
          <div v-if="!isAuthenticated" class="mt-16 rounded-xl bg-gradient-to-r from-appGreenLight/10 to-appBlueLight/10 p-8">
            <h2 class="mb-4 text-2xl font-semibold text-gray-900 dark:text-white">
              Dapatkan Bantuan Lebih Personal
            </h2>
            <p class="mb-6 text-gray-600 dark:text-gray-400">
              Daftar atau masuk untuk mendapatkan bantuan yang lebih personal dan akses ke fitur live chat
            </p>
            <div class="flex justify-center space-x-4">
              <Link href="/login"
                    class="rounded-full bg-appGreenLight px-6 py-2 text-white transition-all hover:bg-appGreenMedium">
                Masuk
              </Link>
              <Link href="/register"
                    class="rounded-full border border-appGreenLight px-6 py-2 text-appGreenLight transition-all hover:bg-appGreenLight hover:text-white">
                Daftar
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Floating Action Button -->
    <button
      @click="toggleLiveChat"
      class="fixed bottom-6 right-6 flex items-center rounded-full bg-appGreenLight px-6 py-3 text-white shadow-lg transition-all hover:bg-appGreenMedium"
    >
      <MessageCircle class="mr-2 h-5 w-5" />
      <span class="text-sm font-medium">
        {{ isAuthenticated ? 'Live Chat' : 'Login untuk Chat' }}
      </span>
    </button>
  </component>
</template>
