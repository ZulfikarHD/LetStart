<script setup>
import { Facebook, Instagram, Twitter } from 'lucide-vue-next';

const socialLinks = [
  { name: 'Facebook', href: '#', icon: Facebook },
  { name: 'Instagram', href: '#', icon: Instagram },
  { name: 'Twitter', href: '#', icon: Twitter }
];
</script>

<template>
  <div class="flex space-x-4">
    <a v-for="item in socialLinks"
       :key="item.name"
       :href="item.href"
       class="text-gray-500 transition-colors hover:text-appGreenLight dark:text-gray-400 dark:hover:text-appGreenLight">
      <component :is="item.icon" class="h-5 w-5" />
    </a>
  </div>
</template>
