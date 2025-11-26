<template>
  <div class="card">
    <div class="card-header">
      <h3 class="h6 mb-0">Filtry</h3>
    </div>
    <div class="card-body">
      <form @submit.prevent="applyFilters">
        <div style="margin-left: 20px; margin-right: 20px;">
          <!-- Nazwa produktu -->
          <div class="mb-3">
            <label for="filter-name" class="form-label">Nazwa produktu</label>
            <input
                type="text"
                id="filter-name"
                v-model="filters.name"
                class="form-control"
                placeholder="np. Ławka parkowa"
            />
          </div>

          <!-- Kategoria produktu -->
          <div class="mb-3">
            <label for="filter-type" class="form-label">Kategoria</label>
            <Multiselect
                v-model="filters.category"
                :options="categories"
                :multiple="false"
                :select-label="''"
                :selected-label="''"
                :deselect-label="''"
                placeholder="Wybierz"
                label="name"
                track-by="name"
            />
          </div>

          <!-- Zakres cenowy -->
          <div class="mb-4">
            <label class="form-label">Zakres cenowy</label>
            <div v-if="loadingRange" class="form-text">Ładowanie zakresu cen...</div>
            <div v-else>
              <Slider
                  v-model="priceRangeModel"
                  :min="priceRange.min"
                  :max="priceRange.max"
                  :step="20"
                  :tooltip="true"
                  :tooltipPosition="'bottom'"
                  :lazy="true"
                  class="mb-2"
              />
            </div>
          </div>

          <div class="mt-3">&nbsp;</div>

          <!-- Przycisk akcji -->
          <div class="d-flex justify-content-end gap-2">
            <button
                type="button"
                @click="resetFilters"
                class="btn btn-outline-secondary btn-sm"
            >
              Resetuj
            </button>
            <button
                type="submit"
                class="btn btn-primary btn-sm d-flex align-items-center gap-2"
                :disabled="smallLoading"
            >
              <SmallLoader :active="smallLoading" />
              Filtruj
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import '@vueform/slider/themes/default.css';
import Slider from '@vueform/slider';
import Loader from '@/component/Loader.vue';
import Multiselect from 'vue-multiselect';
import 'vue-multiselect/dist/vue-multiselect.css';
import SmallLoader from '@/component/SmallLoader.vue';

import { ref, reactive, computed, watch, onMounted } from 'vue';

// Props
const props = defineProps({
  category: {
    type: String,
    default: null,
  },
  smallLoading: {
    type: Boolean,
    default: false,
  },
});

// Emit
const emit = defineEmits(['apply-filters']);

// State
const debounceTimeout = ref(null);
const categories = ref([]);
const loadingRange = ref(false);

const priceRange = reactive({
  min: 0,
  max: 10000,
});

const filters = reactive({
  name: '',
  category: '',
  price_min: 0,
  price_max: 0,
  is_active: true,
});

// Computed
const priceRangeModel = computed({
  get: () => [filters.price_min, filters.price_max],
  set: ([min, max]) => {
    filters.price_min = min;
    filters.price_max = max;
  },
});

// Watchers
watch(
    () => [filters.price_min, filters.price_max],
    () => {
      debouncedApplyFilters();
    }
);

watch(
    () => props.category,
    (newVal) => {
      filters.category = newVal ? ucfirst(newVal) : '';
      // fetchPriceRange(); // jeśli chcesz odświeżać zakres cen przy zmianie kategorii
    }
);

// Methods
const debouncedApplyFilters = () => {
  clearTimeout(debounceTimeout.value);
  debounceTimeout.value = setTimeout(() => {
    applyFilters();
  }, 1000);
};

const fetchPriceRange = async (newCategory = null) => {
  loadingRange.value = true;
  try {
    if (newCategory === null) {
      filters.category = '';
    }
    const categoryParam = filters.category
        ? `/${encodeURIComponent(filters.category)}`
        : '';
    const response = await fetch(`/api/product/range-price${categoryParam}`);
    if (!response.ok) throw new Error('Błąd pobierania zakresu cenowego');

    const data = await response.json();
    priceRange.min = data.minPrice || 0;
    priceRange.max = data.maxPrice || 10000;

    filters.price_min = priceRange.min;
    filters.price_max = priceRange.max;
  } catch (error) {
    console.error(error);
  } finally {
    loadingRange.value = false;
  }
};

const fetchProductType = async () => {
  loadingRange.value = true;
  try {
    const response = await fetch(`/api/product-type/list`);
    if (!response.ok) throw new Error('Błąd pobierania kategorii produktów');

    const data = await response.json();
    categories.value = data.data;

    if (filters.category) {
      const match = categories.value.find(
          (c) => c.link.toLowerCase() === filters.category.toLowerCase()
      );
      filters.category = match || null;
    }
  } catch (error) {
    categories.value = [];
    console.error(error);
  } finally {
    loadingRange.value = false;
  }
};

const ucfirst = (str) => {
  if (!str) return '';
  return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
};

const applyFilters = () => {
  emit('apply-filters', { ...filters });
};

const resetPriceSliders = () => {
  filters.price_min = priceRange.min;
  filters.price_max = priceRange.max;
};

const resetFilters = () => {
  filters.name = '';
  filters.type = '';
  filters.is_active = true;
  filters.category = '';
  fetchPriceRange(null);
  applyFilters();
};

// Lifecycle
onMounted(() => {
  if (props.category) {
    filters.category = ucfirst(props.category);
  }
  fetchProductType();
  fetchPriceRange();
});
</script>

<style scoped>
</style>
