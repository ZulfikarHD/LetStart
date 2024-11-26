import { defineStore } from 'pinia';

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: [],
    appliedPromo: null,
    serviceFee: 5000,
  }),

  actions: {
    removeItem(itemId) {
      this.items = this.items.filter(item => item.id !== itemId);
    },

    clearCart() {
      this.items = [];
    },

    applyPromo(code) {
      // Contoh implementasi promo
      const promos = {
        'DISKON10': { code: 'DISKON10', discount: 10 },
        'DISKON20': { code: 'DISKON20', discount: 20 },
      };

      if (promos[code]) {
        this.appliedPromo = promos[code];
        return true;
      }
      return false;
    },

    removePromo() {
      this.appliedPromo = null;
    },
  },
});
