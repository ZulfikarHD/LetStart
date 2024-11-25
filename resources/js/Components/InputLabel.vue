<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <label class="block text-sm font-medium leading-6 tracking-wide text-gray-700 transition-colors duration-200 dark:text-gray-300">
        <span v-if="value" class="inline-block">{{ value }}</span>
        <span v-else class="inline-block"><slot /></span>
    </label>
</template>
