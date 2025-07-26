<template>
  <section class="mx-auto px-4 py-3" style="max-width: 90vw;">
    <!-- Nagłówek -->
    <div class="mb-4 border-bottom pb-2">
      <h1 class="h3 fw-bold">Lista produktów</h1>
      <p class="text-muted">
        <b>
          {{ category ? `Kategoria: ${category}` : 'Wszystkie kategorie' }}
        </b>
      </p>
    </div>

    <div class="d-flex flex-row gap-4">
      <!-- Panel filtrów -->
      <aside
          :class="['', showFilter ? 'expanded-filter' : 'collapsed-filter']"
          @click="!showFilter && toggleFilter()"
          style="transition: width 0.3s ease; overflow: hidden;"
      >
        <div @click="!showFilter && toggleFilter()">
          <template v-show="!showFilter">
            <div
                class="vertical-text d-flex justify-content-center align-items-center h-20"
                style="width: 20px;"
                title="Pokaż filtry"
            >
              Pokaż Filtry
            </div>
          </template>
        </div>

        <div v-show="showFilter">
          <ProductFilter
              :category="category"
              :small-loading="smallLoading"
              @apply-filters="updateFilters"
          />
          <button
              class="btn btn-sm btn-outline-secondary mt-3 w-100"
              @click.stop="toggleFilter"
              title="Ukryj filtry"
          >
            Ukryj filtry
          </button>
        </div>
      </aside>

      <!-- Główna lista produktów -->
      <main
          :style="{ flex: showFilter ? '1 1 calc(100% - 300px)' : '1 1 100%' }"
          class="pe-3"
      >
        <Loader v-if="loading"/>

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
                    :src="product.dataImage"
                    class="card-img-top"
                    :alt="product.name"
                />
                <div class="card-body">
                  <h5 class="card-title">{{ product.name }}</h5>
                  <p class="card-text"><strong>{{ product.price }} zł</strong></p>
                </div>

                <!-- Serduszko -->
                <button
                    class="btn position-absolute"
                    :class="hoveredWishlist === product.id ? ' btn-outline-success ' : '  btn-success '"
                    style="top: 2px; right: 2px;"
                    @mouseenter="hoveredWishlist = product.id"
                    @mouseleave="hoveredWishlist = null"
                >
                  <SmallLoader :active="smallLoading"/>
                  <i
                      class="fa-heart"
                      :class="hoveredWishlist === product.id ? 'fa-regular' : 'fa-solid'"
                      style="color: black;"
                  ></i>
                </button>

                <!-- Koszyk -->
                <button
                    class="btn  position-absolute"
                    :class="hoveredCart === product.id ? 'btn-outline-primary' : 'btn-primary'"
                    style="bottom: 2px; right: 2px;"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    title="Dodaj do koszyka"
                    @mouseenter="hoveredCart = product.id"
                    @mouseleave="hoveredCart = null"
                >
                  <i
                      class="fa-solid fa-cart-shopping"
                      :class="{ 'text-light': hoveredCart === product.id, 'text-dark': hoveredCart !== product.id }"
                      style="color: black;"
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
import SmallLoader from '@/component/SmallLoader';
import {ref} from "vue";

export default {
  name: 'ProductList',
  components: {
    Loader,
    SmallLoader,
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
      loading: false,
      activeFilters: {},
      smallLoading: false,
    };
  },
  created() {
    // this.activeFilters.append('category', this.category);
  },
  mounted() {
    // this.loadProducts();
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
          // params.append('category', this.category);
        }

        for (const key in this.activeFilters) {
          const value = this.activeFilters[key];
          if (key === 'category' && typeof value === 'object' && value !== null) {
            // Wyślij kategorię jako dwa osobne parametry
            params.append('category', value.name || '');
            params.append('category_id', parseInt(value.id) || null);
          } else if (value !== null && value !== undefined) {
            params.append(key, value);
          }
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
    async updateFilters(filters) {
      try {
        this.activeFilters = filters;
        this.page = 1;
        this.smallLoading = true;
        await this.loadProducts();
      } catch (e) {
        console.error(e);
      } finally {
        this.smallLoading = false;
      }
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
