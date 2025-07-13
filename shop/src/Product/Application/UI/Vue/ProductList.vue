<template>
  <section class="mx-auto px-4 py-3" style="max-width: 90vw;">
    <!-- Nagłówek -->
    <div class="mb-4 border-bottom pb-2">
      <h1 class="h3 fw-bold">Lista produktów</h1>
      <p class="text-muted">
        {{ category ? `Kategoria: ${category}` : 'Wszystkie kategorie' }}
      </p>
    </div>

    <div class="d-flex flex-row gap-4">
      <!-- Panel filtrów -->
      <aside
          :class="['', showFilter ? 'expanded-filter' : 'collapsed-filter']"
          @click="!showFilter && toggleFilter()"
          style="transition: width 0.3s ease; overflow: hidden;"
      >
        <template v-if="showFilter">
            <ProductFilter :category="category" @apply-filters="updateFilters" />
            <button
                class="btn btn-sm btn-outline-secondary mt-3 w-100"
                @click.stop="toggleFilter"
                title="Ukryj filtry"
            >
              Ukryj filtry
            </button>

        </template>
        <template v-else>
          <div
              class="vertical-text d-flex justify-content-center align-items-center h-20"
              style="width: 20px;"
              title="Pokaż filtry"
          >
            Filtry
          </div>
        </template>
      </aside>

      <!-- Główna lista produktów -->
      <main
          :style="{ flex: showFilter ? '1 1 calc(100% - 300px)' : '1 1 100%' }"
          class="pe-3"
      >
        <Loader v-if="loading" />

        <div v-else>
          <div v-if="products.length === 0" class="alert alert-info text-center">
            Brak produktów spełniających kryteria.
          </div>

          <div class="row row-cols-1 row-cols-md-3 g-4">
            <div
                v-for="product in products"
                :key="product.id"
                class="col"
            >
              <div class="card h-100 position-relative">
                <img
                    :src="product.image"
                    class="card-img-top"
                    :alt="product.name"
                />
                <div class="card-body">
                  <h5 class="card-title">{{ product.name }}</h5>
                  <p class="card-text"><strong>{{ product.price }} zł</strong></p>
                </div>

                <!-- Serduszko -->
                <button
                    class="btn btn-success position-absolute"
                    style="top: 10px; right: 10px;"
                    @mouseenter="hoveredWishlist = product.id"
                    @mouseleave="hoveredWishlist = null"
                >
                  <i
                      class="bi"
                      :class="hoveredWishlist === product.id ? 'bi-heart-fill text-danger' : 'bi-heart'"
                  ></i>
                </button>

                <!-- Koszyk -->
                <button
                    class="btn btn-primary position-absolute"
                    style="bottom: 10px; right: 10px;"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    title="Dodaj do koszyka"
                    @mouseenter="hoveredCart = product.id"
                    @mouseleave="hoveredCart = null"
                >
                  <i
                      class="bi bi-cart"
                      :class="{ 'text-black': hoveredCart === product.id, 'text-white': hoveredCart !== product.id }"
                  ></i>
                </button>
              </div>
            </div>
          </div>


          <section id="pagination">
            <div
                v-if="totalPages > 1"
                class="d-flex justify-content-right align-items-right mt-4 gap-3"
            >
              <button
                  class="btn btn-secondary"
                  :disabled="page === 1"
                  @click="changePage(page - 1)"
              >
                Poprzednia
              </button>

              <span class="fw-medium">
              Strona {{ page }} z {{ totalPages }}
            </span>

              <button
                  class="btn btn-secondary"
                  :disabled="page === totalPages"
                  @click="changePage(page + 1)"
              >
                Następna
              </button>
            </div>
          </section>
        </div>
      </main>
    </div>
  </section>
</template>

<script>
import Loader from '@/component/Loader.vue';

import ProductFilter from './ProductFilter.vue';
import {ref} from "vue";

export default {
  name: 'ProductList',
  components: {
    Loader,
    ProductFilter,
  },
  props: {
    category: {
      type: String,
      default: '',
    },
  },
  data() {
    return {
      showFilter: true,
      products: [],
      page: 1,
      totalPages: 1,
      // category: null,
      loading: false,
      activeFilters: {},
    };
  },
  created() {
    // this.activeFilters.append('category', this.category);
  },
  mounted() {
    this.loadProducts();
  },
  watch: {
    // categorySelected(newCategory) {
    //   this.category = newCategory;
    //   this.page = 1;
    //
    //   this.loadProducts();
    // },
  },
  methods: {
    toggleFilter() {
      this.showFilter = !this.showFilter
    },
    async loadProducts() {
      this.products = [];
      this.totalPages = 0;

      this.loading = true;
      try {
        const params = new URLSearchParams({
          page: this.page,
        });

        if (this.category) {
          params.append('category', this.category);
        }

        // Dodajemy aktywne filtry do parametrów zapytania
        for (const key in this.activeFilters) {
          let value = this.activeFilters[key];
          // Dodajemy tylko niepuste wartości, aby nie zaśmiecać URL
          if (value === null) {
            value = '';
          }
          params.append(key, value);
        }

        const response = await fetch(`/api/product/list?${params.toString()}`);
        if (!response.ok) throw new Error('Błąd serwera');

        const data = await response.json();
        this.products = data.items;
        this.totalPages = data.pages;

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
    updateFilters(filters) {
      this.activeFilters = filters;
      this.page = 1;
      this.loadProducts();
    },
  },
};
</script>

<style scoped>
  .expanded-filter {
    width: 25%;
    min-width: 250px;
    height: auto;
    min-height: 400px;
  }

  .collapsed-filter {
    width: 20px;
    min-width: 20px;
    height: auto;
    min-height: 400px;
    background-color: #f8f9fa;
    border-left: 1px solid #dee2e6;
  }
  .vertical-text {
    writing-mode: vertical-rl; /* Tekst pionowo od dołu do góry */
    transform: rotate(180deg); /* Obróć, żeby czytać od góry do dołu */
    font-size: 12px;
    font-weight: 600;
    color: #6c757d; /* szary bootstrapowy */
    user-select: none; /* Nie zaznaczaj tekstu */
  }
</style>
