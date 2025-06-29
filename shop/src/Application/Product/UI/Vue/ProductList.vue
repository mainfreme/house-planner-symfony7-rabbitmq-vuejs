<script setup>
defineProps({
  category: {
    type: String,
    default: null
  }
});
</script>

<template>
  <div class="max-w-3xl mx-auto p-4">
    <h1>Lista produktów</h1>
    <p v-if="category">Kategoria: {{ category }}</p>
    <p v-else>Wszystkie kategorie</p>

    <!-- Loader -->
    <Loader v-if="loading" />

    <!-- Zawartość po załadowaniu -->
    <div v-else>
      <div v-if="products.length === 0" class="text-gray-600 text-center p-4">
        Brak produktów.
      </div>

      <ul>
        <li
            v-for="product in products"
            :key="product.id"
            class="p-4 border-b border-gray-200 flex justify-between"
        >
          <span>{{ product.name }}</span>
          <span class="font-semibold">{{ product.price }} zł</span>
        </li>
      </ul>

      <!-- Paginacja -->
      <div class="flex justify-center items-center mt-6 space-x-4">
        <button
            :disabled="page === 1"
            @click="changePage(page - 1)"
            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50"
        >
          Poprzednia
        </button>

        <span class="text-gray-700 font-medium">
          Strona {{ page }} z {{ totalPages }}
        </span>

        <button
            :disabled="page === totalPages"
            @click="changePage(page + 1)"
            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50"
        >
          Następna
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import Loader from '@/component/Loader.vue';

export default {
  name: 'ProductList',
  components: {
    Loader,
  },
  data() {
    return {
      products: [],
      page: 1,
      totalPages: 1,
      loading: false,
    };
  },
  mounted() {
    this.loadProducts();
  },
  methods: {
    async loadProducts() {
      this.loading = true;
      try {
        const response = await fetch(`/api/product/list?page=${this.page}`);
        if (!response.ok) throw new Error('Błąd serwera');
        const data = await response.json();

        this.products = data.items;
        this.totalPages = data.total_pages;
      } catch (error) {
        console.error('Błąd ładowania produktów:', error);
        this.products = [];
        this.totalPages = 1;
      } finally {
        this.loading = false;
      }
    },
    changePage(newPage) {
      if (newPage >= 1 && newPage <= this.totalPages) {
        this.page = newPage;
        this.loadProducts();
      }
    },
  },
};
</script>

<style scoped>
/* Możesz dodać własne style lub korzystać z Tailwind */
</style>
