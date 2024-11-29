<script setup>
import { Link } from '@inertiajs/vue3';
import Logo from '@/Components/Logo.vue';
</script>

<template>
  <div class="space-y-4">
    <Link href="/"
          class="flex items-center">
      <Logo />
    </Link>
    <p class="text-sm text-gray-600 dark:text-gray-400">
      Platform booking lapangan olahraga terpercaya dengan ribuan venue di seluruh Indonesia
    </p>
  </div>
</template>
