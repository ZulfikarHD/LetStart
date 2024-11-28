<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  title: {
    type: String,
    required: true
  },
  links: {
    type: Array,
    required: true
  }
});
</script>

<template>
  <div>
    <h3 class="mb-4 text-sm font-semibold uppercase text-gray-900 dark:text-white">
      {{ title }}
    </h3>
    <ul class="space-y-2">
      <li v-for="link in links" :key="link.href">
        <Link :href="link.href"
              class="text-gray-600 transition-colors hover:text-appGreenLight dark:text-gray-400">
          {{ link.name }}
        </Link>
      </li>
    </ul>
  </div>
</template>
