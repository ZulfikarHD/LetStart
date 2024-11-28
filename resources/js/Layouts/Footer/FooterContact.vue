<script setup>
import { Mail, Phone } from 'lucide-vue-next';
</script>

<template>
  <div>
    <h3 class="mb-4 text-sm font-semibold uppercase text-gray-400">Kontak</h3>
    <ul class="space-y-2">
      <li class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
        <Mail class="h-5 w-5" />
        <span>support@mainnow.id</span>
      </li>
      <li class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
        <Phone class="h-5 w-5" />
        <span>+62 821-2345-6789</span>
      </li>
    </ul>
  </div>
</template>
