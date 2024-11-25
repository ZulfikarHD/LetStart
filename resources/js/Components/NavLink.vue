<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'inline-flex items-center px-4 py-2 rounded-xl font-medium text-sm text-white bg-gradient-to-r from-appGreenLight to-appGreenMedium hover:from-appGreenMedium hover:to-appGreenDark transition-all duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-appGreenLight focus:ring-offset-2 dark:focus:ring-offset-gray-800'
        : 'inline-flex items-center px-4 py-2 rounded-xl font-medium text-sm text-gray-600 hover:text-appGreenLight dark:text-gray-400 dark:hover:text-appGreenLight transition-all duration-300 ease-in-out focus:outline-none'
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
