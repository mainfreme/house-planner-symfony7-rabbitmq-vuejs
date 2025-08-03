<template>
  <div class="d-flex justify-content-end mb-2">
    <button class="btn btn-outline-secondary btn-sm" @click="toggleColumnConfig('show')">
      ⚙️ Konfiguruj kolumny
    </button>
  </div>

  <teleport to="body">
    <div class="modal fade" id="columnModal" tabindex="-1" aria-labelledby="columnModalLabel" aria-hidden="true" ref="columnModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <SmallLoader :active="smallLoading" />

          <div class="modal-header">
            <h5 class="modal-title" id="columnModalLabel">Konfiguruj kolumny</h5>
            <button type="button" class="btn-close" @click="toggleColumnConfig('hide')"></button>
          </div>

          <div class="modal-body">
            <div v-for="col in allColumns" :key="col.key" class="form-check">
              <input
                  class="form-check-input"
                  type="checkbox"
                  :value="col.key"
                  v-model="visibleColumnKeys"
                  :id="`check-${col.key}`"
              />
              <label class="form-check-label" :for="`check-${col.key}`">
                {{ col.label }}
              </label>
            </div>
          </div>

          <div class="modal-footer">
            <button class="btn btn-outline-danger" @click="resetColumns">Przywróć domyślne</button>
            <button class="btn btn-outline-success" @click="toggleColumnConfig('hide')">Zapisz</button>
          </div>
        </div>
      </div>
    </div>
  </teleport>
</template>

<script>
import SmallLoader from '@/component/SmallLoader';
import { Modal } from 'bootstrap';
import axios from 'axios';

export default {
  name: 'TableConfigColumns',
  components: {
    SmallLoader,
  },
  props: {
    smallLoading: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      allColumns: [],
      defVisibleColumn: ['id', 'name', 'nip', 'email'],
      visibleColumnKeys: [],
      columnModalInstance: null,
    };
  },
  mounted() {
    // Inicjalizacja modala
    const modalEl = this.$refs.columnModal;
    if (modalEl) {
      this.columnModalInstance = new Modal(modalEl);
    }

    // Wczytanie zapisanych kolumn
    const saved = localStorage.getItem('client_visible_columns');
    if (saved) {
      try {
        this.visibleColumnKeys = JSON.parse(saved);
      } catch (e) {
        console.warn('Nie udało się sparsować localStorage:', e);
        this.resetColumns();
      }
    } else {
      this.resetColumns();
    }
  },
  methods: {
    async toggleColumnConfig(type = 'toggle') {
      if (type === 'hide') {
        localStorage.setItem('client_visible_columns', JSON.stringify(this.visibleColumnKeys));
        this.$emit('update:columns', {
          columns: this.allColumns,
          visibleKeys: this.visibleColumnKeys
        });
        this.columnModalInstance?.hide();
      } else if (type === 'show') {
        this.columnModalInstance?.show();

        // Jeśli kolumny nie są jeszcze pobrane, pobierz je
        if (this.allColumns.length === 0) {
          await this.loadColumnsClients();
        }

        // Emituj dane do rodzica (przydatne, gdyby chciał się zaktualizować od razu)
        this.$emit('update:columns', {
          columns: this.allColumns,
          visibleKeys: this.visibleColumnKeys
        });
      } else {
        this.columnModalInstance?.toggle();
      }
    },

    resetColumns() {
      this.visibleColumnKeys = [...this.defVisibleColumn];
      this.$emit('update:columns', {
        columns: this.allColumns,
        visibleKeys: this.visibleColumnKeys
      });
    },

    async loadColumnsClients() {
      try {
        const response = await axios.get('/api/client/list-columns');
        this.allColumns = response.data;
      } catch (error) {
        console.error('Błąd ładowania kolumn:', error);
      }
    },
  },
};
</script>
