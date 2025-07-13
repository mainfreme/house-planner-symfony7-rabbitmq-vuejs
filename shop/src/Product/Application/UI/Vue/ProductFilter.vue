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
            <multiselect
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
          <div class="mt-3">
            &nbsp;
          </div>

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
                class="btn btn-primary btn-sm"
            >
              Filtruj
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import('@vueform/slider/themes/default.css');
import Slider from '@vueform/slider'
import Loader from '@/component/Loader.vue'
import Multiselect from 'vue-multiselect'
import 'vue-multiselect/dist/vue-multiselect.css'

export default {
  name: 'ProductFilter',
  components: {
    Loader,
    Slider,
    Multiselect,
  },
  props: {
    category: {
      type: String,
      default: null,
    },
  },
  data() {
    return {
      debounceTimeout: null,
      categories: [],
      loadingRange: false,
      priceRange: {
        min: 0,
        max: 10000,
      },
      filters: {
        name: '',
        category: '',
        price_min: 0,
        price_max: 0,
        is_active: true,
      },
    }
  },
  created() {
    this.filters.category = this.ucfirst(this.category)
  },
  computed: {
    priceRangeModel: {
      get() {
        return [this.filters.price_min, this.filters.price_max]
      },
      set([min, max]) {
        this.filters.price_min = min
        this.filters.price_max = max
      },
    },
  },
  watch: {
    'filters.price_min'(val) {
      this.debouncedApplyFilters()
    },
    'filters.price_max'(val) {
      this.debouncedApplyFilters()
    },
    category(newVal) {
      // this.fetchPriceRange()
      this.filters.category = newVal ? this.ucfirst(newVal) : ''
    },
  },
  methods: {
    debouncedApplyFilters() {
      clearTimeout(this.debounceTimeout)
      this.debounceTimeout = setTimeout(() => {
        this.applyFilters()
      }, 1000)
    },
    async fetchPriceRange() {
      this.loadingRange = true
      try {
        const categoryParam = this.category ? `/${encodeURIComponent(this.category)}` : ''
        const response = await fetch(`/api/product/range-price${categoryParam}`)
        if (!response.ok) throw new Error('Błąd pobierania zakresu cenowego')

        const data = await response.json()
        this.priceRange.min = data.minPrice || 0
        this.priceRange.max = data.maxPrice || 10000
        this.resetPriceSliders()
      } catch (error) {
        console.error(error)
      } finally {
        this.loadingRange = false
      }
    },
    async fetchProductType() {
      this.loadingRange = true
      try {
        const response = await fetch(`/api/product-type/list`)
        if (!response.ok) throw new Error('Błąd pobierania kategorii produktów')

        const data = await response.json()
        this.categories = data.items

        if (this.category) {
            const match = this.categories.find(
                (c) => c.name.toLowerCase() === this.category.toLowerCase()
            )
            this.filters.category = match || null
        }
      } catch (error) {
        this.categories = []
        console.error(error)
      } finally {
        this.loadingRange = false
      }
    },
    ucfirst(str) {
      if (!str) return ''
      return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase()
    },

    applyFilters() {
      this.$emit('apply-filters', {...this.filters})
    },
    resetPriceSliders() {
      this.filters.price_min = this.priceRange.min
      this.filters.price_max = this.priceRange.max
    },
    resetFilters() {
      this.filters.name = ''
      this.filters.type = ''
      this.filters.is_active = true
      this.filters.category = []
      this.resetPriceSliders()
      this.applyFilters()
    },
  },
  mounted() {
    this.fetchProductType()
    this.fetchPriceRange()
  },
}
</script>

<style scoped>
.vue-slider {
  width: 90%;
}
</style>
