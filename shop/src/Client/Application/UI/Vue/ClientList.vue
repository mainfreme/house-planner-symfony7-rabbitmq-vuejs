<template>
  <div class="container my-4">
    <div class="row">
      <div class="col-md-12">
        <h2 class="h4 fw-bold mb-3">Lista klientów</h2>

        <div class="d-flex justify-content-end mb-2">
          <button class="btn btn-outline-success btn-sm" @click="addClient">
            Dodaj +
          </button>
          <button class="btn btn-outline-primary btn-sm" @click="refreshList">
            Odśwież
          </button>
          <TableConfigColumns
              :smallLoading="false"
              @update:columns="handleColumnChange"
          />
        </div>

      </div>

      <div class="col-md-2">
        <ClientFilter :filters="filters" @update-filters="applyFilters"/>
      </div>

      <div class="col-md-10">
        <div class="flex-grow-1 overflow-auto" ref="clientScrollContainer" @scroll="handleScroll">
          <Loader v-if="loading"/>
          <table class="table table-striped" style="max-width: 100%; display: table;">
            <thead class="table-light position-sticky top-0">
            <tr>
              <th
                  v-for="col in visibleColumns"
                  :key="col.key"
                  @click="sortBy(col.key)"
                  class="cursor-pointer"
              >
                {{ col.label }}
                <span v-if="sort.field === col.key">
                    {{ sort.order === 'asc' ? '▲' : '▼' }}
                  </span>
              </th>
              <th>Akcje</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="client in clients" :key="client.id">
              <td v-for="col in visibleColumns" :key="col.key">
                {{ client[col.key] }}
              </td>
              <td>
                <ClientDetailModal :clientId="client.id">
                  <template #button="{ toggle }">
                    <button
                        class="btn btn-outline-secondary btn-sm"
                        @click="toggle('show')"
                    >
                      Szczegóły
                    </button>
                  </template>
                </ClientDetailModal>

                <div v-if="!showForm">
                  <button class="btn btn-sm btn-outline-primary me-1" @click="edit(client)">Edytuj</button>
                  <button class="btn btn-sm btn-outline-danger" @click="openDeletePopup(client)">Usuń</button>
                </div>
                <div v-else>
                  <button class="btn btn-sm btn-outline-success me-1" @click="updateChanges">Zapisz</button>
                  <button class="btn btn-sm btn-outline-danger" @click="cancelEdit">Anuluj</button>
                </div>
              </td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <delete-popup
        :showModal="showModal"
        :clientToDelete="clientToDelete"
        :error="deleteError"
        @close="closeDeletePopUp"
        @confirm-delete="deleteClient"
    />
  </div>
</template>

<script>
import Loader from '@/component/Loader.vue';
import DeletePopup from '@/component/deletePopup.vue';
import TableConfigColumns from '@/component/TableConfigColumns.vue';
import ClientDetailModal from './ClientDetailModal.vue';
import ClientFilter from './ClientFilter.vue';
import axios from 'axios';

export default {
  name: 'ClientList',
  components: {
    ClientDetailModal,
    Loader,
    DeletePopup,
    ClientFilter,
    TableConfigColumns
  },
  data() {
    return {
      showForm: false,
      clients: [],
      showModal: false,
      clientToDelete: null,
      deleteError: '',
      page: 1,
      totalPages: 1,
      loading: false,
      sort: {
        field: null,
        order: 'asc'
      },
      filters: {
        name: '',
        nip: '',
        regon: '',
        pesel: '',
        email: '',
        number_phone: '',
        phone_prefix: '',
        country: ''
      },
      allColumns: [
        {key: 'id', label: 'ID'},
        {key: 'name', label: 'Nazwa'},
        {key: 'email', label: 'Email'},
        {key: 'nip', label: 'NIP'},
      ],
      visibleColumnKeys: ['id', 'name', 'email', 'nip'],
    };
  },
  computed: {
    visibleColumns() {
      return this.allColumns.filter(col => this.visibleColumnKeys.includes(col.key));
    }
  },
  mounted() {
    this.loadClients();
  },
  methods: {
    async loadClients() {
      this.loading = true;
      try {
        const params = new URLSearchParams({
          page: this.page,
          sort: this.sort.field,
          order: this.sort.order,
          ...this.filters
        });
        const response = await axios.get(`/api/client/list?${params.toString()}`);
        const data = response.data;
        this.clients = this.page === 1 ? data.items : [...this.clients, ...data.items];
        this.totalPages = data.pages;
      } catch (error) {
        console.error('Błąd ładowania klientów:', error);
        if (this.page === 1) {
          this.clients = [];
          this.totalPages = 1;
        }
      } finally {
        this.loading = false;
      }
    },

    refreshList() {
      this.loadClients();
    },
    handleScroll() {
      const container = this.$refs.clientScrollContainer;
      if (!container || this.loading || this.page >= this.totalPages) return;
      const threshold = 50;
      if (container.scrollTop + container.clientHeight >= container.scrollHeight - threshold) {
        this.page++;
        this.loadClients();
      }
    },

    addClient() {

    },

    /* usun */
    edit(client) {
      this.showForm = true;
    },

    cancelEdit() {
      this.showForm = false;
    },

    updateChanges() {
      // implementacja zapisu
    },

    openDeletePopup(client) {
      this.deleteError = '';
      this.clientToDelete = client;
      this.showModal = true;
    },
    /* end - usun */
    async deleteClient() {
      this.deleteError = '';

      try {
        await axios.delete(`/api/client/${this.clientToDelete.id}`);
        this.clients = this.clients.filter(c => c.id !== this.clientToDelete.id);
        this.showModal = false;
        this.clientToDelete = null;
      } catch (error) {
        this.deleteError = 'Nie udało się usunąć klienta. Spróbuj ponownie później.';

        if (error.response && error.response.data && error.response.data.message) {
          this.deleteError = error.response.data.message;
        }
      }
    },

    applyFilters(updatedFilters) {
      this.filters = updatedFilters;
      this.page = 1;
      this.clients = [];
      this.loadClients();
    },

    sortBy(field) {
      if (this.sort.field === field) {
        this.sort.order = this.sort.order === 'asc' ? 'desc' : 'asc';
      } else {
        this.sort.field = field;
        this.sort.order = 'asc';
      }
      this.page = 1;
      this.clients = [];
      this.loadClients();
    },

    handleColumnChange({columns, visibleKeys}) {
      this.allColumns = columns;
      this.visibleColumnKeys = visibleKeys?.length ? visibleKeys : ['id', 'name', 'email', 'nip'];
    },
    closeDeletePopUp() {
      this.showModal = false;
      this.deleteError = '';
    }
  }
};
</script>

<style scoped>
.cursor-pointer {
  cursor: pointer;
}
</style>
