<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Shield, ChevronDown, ChevronUp } from 'lucide-vue-next';

const props = defineProps({
    auth: {
        type: Object,
        default: () => ({ user: null })
    },
    lastUpdated: String,
    privacyContent: Object
});

// Compute which layout to use based on auth status
const Layout = computed(() => {
    return props.auth?.user ? AuthenticatedLayout : GuestLayout;
});

const expandedSections = ref(new Set());

const toggleSection = (index) => {
    if (expandedSections.value.has(index)) {
        expandedSections.value.delete(index);
    } else {
        expandedSections.value.add(index);
    }
};
</script>

<template>
    <Layout>
        <Head>
            <title>Kebijakan Privasi - MainNow</title>
            <meta name="description" content="Kebijakan privasi MainNow - Aplikasi booking venue olahraga" />
        </Head>

        <div class="min-h-screen py-12 sm:py-16 lg:py-20">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="text-center">
                    <div class="flex justify-center">
                        <Shield class="h-12 w-12 text-appGreenLight" />
                    </div>
                    <h1 class="mt-4 text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                        Kebijakan Privasi
                    </h1>
                    <p class="mt-4 text-base leading-7 text-gray-600 dark:text-gray-300">
                        Terakhir diperbarui: {{ lastUpdated }}
                    </p>
                </div>

                <!-- Introduction -->
                <div class="mt-10 prose prose-lg prose-green dark:prose-invert mx-auto">
                    <p>
                        Kami di MainNow berkomitmen untuk melindungi privasi Anda. Kebijakan privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi Anda saat menggunakan aplikasi kami.
                    </p>
                </div>

                <!-- Privacy Sections -->
                <div class="mt-10 space-y-6">
                    <div v-for="(section, index) in privacyContent.sections"
                        :key="index"
                        class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
                        <button @click="toggleSection(index)"
                                class="flex w-full items-center justify-between px-4 py-5 sm:p-6">
                            <span class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ section.title }}
                            </span>
                            <ChevronDown v-if="!expandedSections.has(index)"
                                        class="h-5 w-5 text-gray-500 dark:text-gray-400" />
                            <ChevronUp v-else
                                    class="h-5 w-5 text-gray-500 dark:text-gray-400" />
                        </button>

                        <div v-show="expandedSections.has(index)"
                            class="px-4 pb-5 sm:px-6 sm:pb-6">
                            <ul class="list-disc space-y-2 pl-5 text-gray-600 dark:text-gray-300">
                                <li v-for="(item, itemIndex) in section.content"
                                    :key="itemIndex">
                                    {{ item }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Contact Section -->
                <div class="mt-12 rounded-lg bg-appGreenLight/10 p-6 dark:bg-appGreenDark/10">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Hubungi Kami
                    </h2>
                    <p class="mt-2 text-gray-600 dark:text-gray-300">
                        Jika Anda memiliki pertanyaan tentang kebijakan privasi kami, silakan hubungi tim support kami di:
                    </p>
                    <div class="mt-4">
                        <a href="mailto:privacy@mainnow.com"
                           class="text-appGreenDark hover:text-appGreenMedium dark:text-appGreenLight dark:hover:text-appGreenLight/80">
                            privacy@mainnow.com
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
