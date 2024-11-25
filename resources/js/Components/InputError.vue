<script setup>
import { AlertCircle } from 'lucide-vue-next';

defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div v-show="message" class="mt-1 flex items-center gap-1.5 transition-all duration-200">
        <AlertCircle class="h-4 w-4 text-red-500 dark:text-red-400" />
        <p class="text-sm font-medium text-red-500 dark:text-red-400">
            {{ message }}
        </p>
    </div>
</template>
