<template>
  <div class="container my-4">
    <div class="row">
      <div class="col-md-12">
        <h2 class="h4 fw-bold mb-3">Lista klientów</h2>

        <div class="d-flex justify-content-end mb-2">
          <button class="btn btn-outline-success btn-sm" @click="addClient">dodaj +</button>
          <button class="btn btn-outline-primary btn-sm" @click="refreshList">
            Odśwież
          </button>
          <TableConfigColumns
              :smallLoading="false"
              store-name="client"
              @update:columns="handleColumnChange"
          />
        </div>
      </div>

      <div class="col-md-2">
        <ClientFilter :filters="filters" @update-filters="applyFilters" />
      </div>

      <div class="col-md-10">
        <div
            class="flex-grow-1 overflow-auto"
            ref="clientScrollContainer"
            @scroll="handleScroll"
        >
          <Loader v-if="loading" />
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
                <button
                    class="btn btn-sm btn-outline-danger"
                    @click="openDeletePopup(client)"
                >
                  Usuń
                </button>
              </td>
            </tr>
            </tbody>
          </table>

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

              <span class="fw-medium">Strona {{ page }} z {{ totalPages }}</span>

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
      </div>
    </div>

    <DeletePopup
        :showModal="showModal"
        :toDelete="toDelete"
        :error="deleteError"
        @close="closeDeletePopUp"
        @confirm-delete="deleteClient"
        message="Czy na pewno chcesz usunąć klienta"
    />
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, defineAsyncComponent} from "vue";
import axios from "axios";

import Loader from "@/component/Loader.vue";
import DeletePopup from "@/component/deletePopup.vue";
import TableConfigColumns from "@/component/TableConfigColumns.vue";
import ClientDetailModal from "./ClientDetailModal.vue";
import ClientFilter from "./ClientFilter.vue";

// lazy load
const ClientGeneral = defineAsyncComponent(() =>
    import("./General/ClientGeneral.vue")
);

const clients = ref([]);
const clientMetaData = ref([]);
const clientLinkData = ref([]);
const showModal = ref(false);
const toDelete = ref(null);
const deleteError = ref("");
const page = ref(1);
const totalPages = ref(1);
const loading = ref(false);

const sort = reactive({
  field: null,
  order: "asc",
});

const filters = reactive({
  name: "",
  nip: "",
  regon: "",
  pesel: "",
  email: "",
  phoneNumber: "",
  phonePrefix: "",
  country: "",
});

const allColumns = ref([
  { key: "id", label: "ID" },
  { key: "name", label: "Nazwa" },
  { key: "email", label: "Email" },
  { key: "nip", label: "NIP" },
]);
const visibleColumnKeys = ref(["id", "name", "email", "nip"]);

const clientScrollContainer = ref(null);

const visibleColumns = computed(() =>
    allColumns.value.filter((col) => visibleColumnKeys.value.includes(col.key))
);

const clearData = () => {
  clients.value = [];
  clientMetaData.value = [];
  clientLinkData.value = [];
  totalPages.value = 1;
};

const loadClients = async () => {
  loading.value = true;
  try {
    const params = new URLSearchParams({
      page: page.value,
      sort: sort.field,
      order: sort.order,
      ...filters,
    });
    const response = await axios.get(`/api/client/list?${params.toString()}`);
    const data = response.data;

    clientMetaData.value = data.meta;
    clientLinkData.value = data.links;

    clients.value =
        page.value === 1 ? data.data : [...clients.value, ...data.data];
    totalPages.value = clientMetaData.value.total_item;
  } catch (error) {
    console.error("Błąd ładowania klientów:", error);
    if (page.value === 1) {
      clearData();
    }
  } finally {
    loading.value = false;
  }
};

const refreshList = () => {
  page.value = 1;
  loadClients();
};

const handleScroll = () => {
  const container = clientScrollContainer.value;
  if (!container || loading.value || page.value >= totalPages.value) return;
  const threshold = 50;
  if (
      container.scrollTop + container.clientHeight >=
      container.scrollHeight - threshold
  ) {
    page.value++;
    loadClients();
  }
};

const applyFilters = (updatedFilters) => {
  Object.assign(filters, updatedFilters);
  page.value = 1;
  clients.value = [];
  loadClients();
};

const sortBy = (field) => {
  if (sort.field === field) {
    sort.order = sort.order === "asc" ? "desc" : "asc";
  } else {
    sort.field = field;
    sort.order = "asc";
  }
  page.value = 1;
  clients.value = [];
  loadClients();
};

const handleColumnChange = ({ columns, visibleKeys }) => {
  allColumns.value = columns;
  visibleColumnKeys.value =
      visibleKeys?.length > 0 ? visibleKeys : ["id", "name", "email", "nip"];
};

const openDeletePopup = (client) => {
  deleteError.value = "";
  toDelete.value = client;
  showModal.value = true;
};

const deleteClient = async () => {
  deleteError.value = "";
  try {
    await axios.delete(`/api/client/${toDelete.value.id}`);
    clients.value = clients.value.filter((c) => c.id !== toDelete.value.id);
    showModal.value = false;
    toDelete.value = null;
  } catch (error) {
    deleteError.value =
        error.response?.data?.message ||
        "Nie udało się usunąć klienta. Spróbuj ponownie później.";
  }
};

const closeDeletePopUp = () => {
  showModal.value = false;
  deleteError.value = "";
};

const changePage = (newPage) => {
  page.value = newPage;
  loadClients();
};

onMounted(() => {
  const savedColumns = JSON.parse(
      localStorage.getItem("client_local_storage")
  );

  if (savedColumns !== null) {
    if (savedColumns.columns.length > 0) {
      allColumns.value = savedColumns.columns;
    }
    if (savedColumns.visibleKeys.length > 0) {
      visibleColumnKeys.value = savedColumns.visibleKeys;
    }
  }
  loadClients();
});

function addClient() {

}

async function saveClient() {

}
</script>

<style scoped>
.cursor-pointer {
  cursor: pointer;
}
</style>
