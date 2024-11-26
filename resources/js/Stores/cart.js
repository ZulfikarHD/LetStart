import { defineStore } from 'pinia';
import { ref, computed } from 'vue';

export const useCartStore = defineStore('cart', () => {
  const items = ref([]);

  // Computed properties
  const itemCount = computed(() => items.value.length);
  const hasItems = computed(() => items.value.length > 0);
  const total = computed(() =>
    items.value.reduce((sum, item) => sum + item.totalPrice, 0)
  );

  // Actions
  function addItem(item) {
    // Validate required fields
    if (!item.fieldId || !item.timeSlots || !item.price) {
      throw new Error('Invalid item data');
    }

    // Check for duplicate bookings
    const isDuplicate = items.value.some(existingItem =>
      existingItem.fieldId === item.fieldId &&
      existingItem.date === item.date &&
      existingItem.timeSlots.some(slot => item.timeSlots.includes(slot))
    );

    if (isDuplicate) {
      throw new Error('Jadwal sudah ada di keranjang');
    }

    items.value.push({
      ...item,
      cartId: `cart-${Date.now()}-${Math.random()}`, // Unique cart item ID
      addedAt: new Date().toISOString(),
    });

    // Persist to localStorage
    saveToStorage();
  }

  function removeItem(cartId) {
    const index = items.value.findIndex(item => item.cartId === cartId);
    if (index !== -1) {
      items.value.splice(index, 1);
      saveToStorage();
    }
  }

  function clearCart() {
    items.value = [];
    saveToStorage();
  }

  // Storage handling
  function saveToStorage() {
    localStorage.setItem('sports-venue-cart', JSON.stringify(items.value));
  }

  function loadFromStorage() {
    const stored = localStorage.getItem('sports-venue-cart');
    if (stored) {
      items.value = JSON.parse(stored);
    }
  }

  // Load cart data on store initialization
  loadFromStorage();

  return {
    items,
    itemCount,
    hasItems,
    total,
    addItem,
    removeItem,
    clearCart
  };
});
