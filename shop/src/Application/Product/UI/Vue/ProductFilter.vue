<template>
  <div class="p-4 mb-6 border border-gray-200 rounded-lg bg-gray-50">
    <h3 class="text-lg font-semibold mb-3">Filtry</h3>
    <form @submit.prevent="applyFilters">
      <div class="mb-4">
        <label for="filter-name" class="block text-sm font-medium text-gray-700">Nazwa produktu</label>
        <input
            type="text"
            id="filter-name"
            v-model="filters.name"
            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            placeholder="np. Ławka parkowa"
        />
      </div>

      <div class="mb-4">
        <label for="filter-type" class="block text-sm font-medium text-gray-700">Typ</label>
        <input
            type="text"
            id="filter-type"
            v-model="filters.type"
            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            placeholder="np. żeliwna"
        />
      </div>

      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">Zakres cenowy</label>
        <div v-if="loadingRange" class="text-sm text-gray-500">Ładowanie zakresu cen...</div>
        <div v-else>
          <div class="flex justify-between text-sm text-gray-600">
            <span>{{ filters.price_min }} zł</span>
            <span>{{ filters.price_max }} zł</span>
          </div>
          <div class="mt-2 grid grid-cols-2 gap-4">
            <div>
              <label for="price_min" class="text-xs text-gray-500">Cena min.</label>
              <input
                  type="range"
                  id="price_min"
                  :min="priceRange.min"
                  :max="priceRange.max"
                  v-model.number="filters.price_min"
                  class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
              />
            </div>
            <div>
              <label for="price_max" class="text-xs text-gray-500">Cena max.</label>
              <input
                  type="range"
                  id="price_max"
                  :min="priceRange.min"
                  :max="priceRange.max"
                  v-model.number="filters.price_max"
                  class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
              />
            </div>
          </div>
        </div>
      </div>

      <div class="mb-4">
        <div class="flex items-center">
          <input
              id="filter-is-active"
              type="checkbox"
              v-model="filters.is_active"
              class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
          />
          <label for="filter-is-active" class="ml-2 block text-sm text-gray-900">Tylko aktywne</label>
        </div>
      </div>

      <div class="flex items-center justify-end space-x-3">
        <button
            type="button"
            @click="resetFilters"
            class="px-4 py-2 bg-gray-200 text-sm font-medium text-gray-800 rounded-md hover:bg-gray-300"
        >
          Resetuj
        </button>
        <button
            type="submit"
            class="px-4 py-2 bg-blue-600 text-sm font-medium text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
        >
          Filtruj
        </button>
      </div>
    </form>
  </div>
</template>

<script>
import Loader from '@/component/Loader.vue';
export default {
  name: 'ProductFilter',
  components: {
    Loader,
  },
  props: {
    category: {
      type: String,
      default: null,
    },
  },
  data() {
    return {
      loadingRange: false,
      // Domyślny, szeroki zakres, który zostanie nadpisany przez API
      priceRange: {
        min: 0,
        max: 10000,
      },
      filters: {
        name: '',
        type: '',
        price_min: 0,
        price_max: 10000,
        is_active: true,
      },
    };
  },
  watch: {
    // Obserwuj zmianę kategorii, aby przeładować zakres cen
    category() {
      this.fetchPriceRange();
    },
    // Zapewnij, że minimalna cena nie jest wyższa od maksymalnej
    'filters.price_min'(newVal) {
      if (newVal > this.filters.price_max) {
        this.filters.price_max = newVal;
      }
    },
    'filters.price_max'(newVal) {
      if (newVal < this.filters.price_min) {
        this.filters.price_min = newVal;
      }
    }
  },
  methods: {
    async fetchPriceRange() {
      this.loadingRange = true;
      try {
        // Używamy `encodeURIComponent` dla bezpieczeństwa
        const categoryParam = this.category ? `?category=${encodeURIComponent(this.category)}` : '';
        const response = await fetch(`/api/products/range-price/${categoryParam}`);
        if (!response.ok) throw new Error('Błąd pobierania zakresu cenowego');

        const data = await response.json(); // Oczekujemy obiektu np. { min_price: 100, max_price: 5000 }

        this.priceRange.min = data.min_price || 0;
        this.priceRange.max = data.max_price || 10000;

        // Zresetuj wartości suwaków do nowego zakresu
        this.resetPriceSliders();

      } catch (error) {
        console.error(error);
        // W przypadku błędu, zachowaj domyślne wartości
      } finally {
        this.loadingRange = false;
      }
    },
    applyFilters() {
      // Wyślij sklonowany obiekt filtrów do rodzica, aby uniknąć bezpośredniej mutacji
      this.$emit('apply-filters', { ...this.filters });
    },
    resetPriceSliders() {
      this.filters.price_min = this.priceRange.min;
      this.filters.price_max = this.priceRange.max;
    },
    resetFilters() {
      this.filters.name = '';
      this.filters.type = '';
      this.filters.is_active = true;
      this.resetPriceSliders();
      // Po zresetowaniu formularza, od razu zastosuj filtry (czyli pokaż wszystko)
      this.applyFilters();
    },
  },
  mounted() {
    this.fetchPriceRange();
  },
};
</script>

<style scoped>
/* Styl dla suwaków dla lepszej widoczności */
input[type='range']::-webkit-slider-thumb {
  -webkit-appearance: none;
  appearance: none;
  width: 16px;
  height: 16px;
  background: #3b82f6; /* Kolor niebieski */
  cursor: pointer;
  border-radius: 50%;
}

input[type='range']::-moz-range-thumb {
  width: 16px;
  height: 16px;
  background: #3b82f6;
  cursor: pointer;
  border-radius: 50%;
}
</style>
