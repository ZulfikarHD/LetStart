<script setup>
import { computed } from 'vue';
import FooterBrand from './FooterBrand.vue';
import FooterLinks from './FooterLinks.vue';
import FooterSocial from './FooterSocial.vue';
import FooterContact from './FooterContact.vue';

const quickLinks = [
  { name: 'Tentang Kami', href: '/about' },
  { name: 'List Venue', href: '/venue-listing' },
  { name: 'Kategori Olahraga', href: '/sport-category' },
  { name: 'Bantuan', href: '/help' },
];

const supportLinks = [
  { name: 'FAQ', href: '/faq' },
  { name: 'Kebijakan Privasi', href: '/privacy-policy' },
  { name: 'Syarat & Ketentuan', href: '/terms' },
];
</script>

<template>
  <footer class="bg-white border-t border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 gap-8 md:grid-cols-4">
        <!-- Brand Section with Social -->
        <div class="space-y-6">
          <FooterBrand />
          <FooterSocial />
        </div>

        <!-- Quick Links -->
        <FooterLinks title="Quick Links" :links="quickLinks" />

        <!-- Support -->
        <FooterLinks title="Support" :links="supportLinks" />

        <!-- Contact -->
        <FooterContact />
      </div>

      <div class="mt-8 border-t border-gray-200 pt-8 dark:border-gray-700">
        <p class="text-center text-sm text-gray-600 dark:text-gray-400">
          © {{ new Date().getFullYear() }} MainNow. All rights reserved.
        </p>
      </div>
    </div>
  </footer>
</template>
