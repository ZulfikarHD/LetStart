<script setup>
import { Dialog, DialogPanel, DialogOverlay, TransitionRoot, TransitionChild } from '@headlessui/vue';

const props = defineProps({
  show: Boolean,
  paymentMethod: Object
});

const emit = defineEmits(['close', 'confirm']);
</script>

<template>
  <TransitionRoot appear :show="show" as="template">
    <Dialog as="div" class="relative z-50" @close="emit('close')">
      <TransitionChild
        as="template"
        enter="duration-300 ease-out"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="duration-200 ease-in"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <DialogOverlay class="fixed inset-0 bg-black/25 backdrop-blur-sm" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-xl bg-white p-6 text-left shadow-xl transition-all dark:bg-gray-800">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                Hapus Metode Pembayaran?
              </h3>

              <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                Apakah Anda yakin ingin menghapus {{ paymentMethod?.name }}? Tindakan ini tidak dapat dibatalkan.
              </p>

              <div class="mt-6 flex justify-end gap-3">
                <button
                  @click="emit('close')"
                  class="rounded-xl px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 transition-all duration-200"
                >
                  Batal
                </button>
                <button
                  @click="emit('confirm')"
                  class="rounded-xl bg-gradient-to-r from-red-500 to-red-600 px-4 py-2 text-sm font-medium text-white hover:shadow-lg hover:shadow-red-500/20 transition-all duration-200 transform hover:scale-105"
                >
                  Hapus
                </button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>
