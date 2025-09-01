<template>

  <div class="page-container">
    <div class="actions d-flex justify-content-between mb-2 align-items-center">
      <div>
        <button class="btn btn-outline-secondary btn-sm" @click="filtersOpen = !filtersOpen">
          Filtry
        </button>
      </div>

      <div>
        <button class="btn btn-outline-success btn-sm me-2" @click="addNew">Dodaj +</button>
        <button class="btn btn-outline-primary btn-sm" @click="loadClientAddress">
          Odśwież
        </button>
      </div>
    </div>

    <div class="main-content d-flex">
      <div :class="['filter-sidebar', { open: filtersOpen }]">
        <div v-show="filtersOpen" class="filter-content">
          <address-filter v-model:filters="filters" />
        </div>
      </div>
      <Loader v-if="loading"/>
      <div v-else class="content flex-grow-1">
        <table class="table table-striped" style="max-width: 100%; display: table;">
          <thead class="table-light position-sticky top-0">
          <tr>
            <th>ID</th>
            <th>Kraj</th>
            <th>Województwo</th>
            <th>Miasto</th>
            <th>Kod pocztowy</th>
            <th>Ulica</th>
            <th>Numer domu</th>
            <th>Numer mieszkania</th>
            <th>Dodatkowe informacje</th>
            <th>Główny adres</th>
            <th>Data dodania</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="address in items" :key="address.id">
            <template v-if="address.edit">
              <td>-</td>
              <td><input v-model="address.country" maxlength="50" size="3" placeholder="Kraj"/></td>
              <td><input v-model="address.state_province" size="15" placeholder="Województwo"/></td>
              <td><input v-model="address.city" size="20" placeholder="Miasto"/></td>
              <td><input v-model="address.postal_code" min="1" max="99-999" size="6" placeholder="Kod pocztowy"/></td>
              <td><input v-model="address.street" maxlength="50" size="15" placeholder="Ulica"/></td>
              <td><input v-model="address.house_number" min="1" max="9999" size="3" placeholder="Numer domu"/></td>
              <td><input v-model="address.apartment_number" min="1" max="9999" size="4" placeholder="Numer mieszkania"/>
              </td>
              <td><textarea rows="4" cols="5" v-model="address.additional_info" placeholder="Dodatkowe informacje"/>
              </td>
              <td>
                <input type="checkbox" v-model="address.is_primary" size="1"/>
              </td>
              <td>-</td>
              <td>
                <button
                    @click="updateEdit(address)"
                    class="btn btn-sm btn-outline-success"
                >
                  Save
                </button>
                <button
                    @click="cancelEdit(address)"
                    class="btn btn-sm btn-outline-danger"
                >
                  X
                </button>
              </td>
            </template>

            <template v-else-if="address.edit === false">
              <td>{{ address.id }}</td>
              <td>{{ address.country }}</td>
              <td>{{ address.state_province }}</td>
              <td>{{ address.city }}</td>
              <td>{{ address.postal_code }}</td>
              <td>{{ address.street }}</td>
              <td>{{ address.house_number }}</td>
              <td>{{ address.apartment_number }}</td>
              <td>{{ address.additional_info }}</td>
              <td>
                <input
                    type="checkbox"
                    v-model="address.is_primary"
                    @change="updatePrimary(address.id, address.is_primary)"
                />
              </td>
              <td>{{ address.added_at }}</td>
              <td>
                <button
                    @click="addressEdit(address)"
                    class="btn btn-sm btn-outline-primary"
                >
                  Edytuj
                </button>
                <button
                    @click="openDeletePopup(address)"
                    class="btn btn-sm btn-outline-danger">
                  Usuń
                </button>
              </td>
            </template>
          </tr>

          <tr v-if="showNewRow">
            <td>-</td>
            <td><input v-model="newAddress.country" maxlength="50" size="3" placeholder="Kraj"/></td>
            <td><input v-model="newAddress.state_province" size="15" placeholder="Województwo"/></td>
            <td><input v-model="newAddress.city" size="20" placeholder="Miasto"/></td>
            <td><input v-model="newAddress.postal_code" min="1" max="99-999" size="6" placeholder="Kod pocztowy"/></td>
            <td><input v-model="newAddress.street" maxlength="50" size="15" placeholder="Ulica"/></td>
            <td><input v-model="newAddress.house_number" min="1" max="9999" size="3" placeholder="Numer domu"/></td>
            <td><input v-model="newAddress.apartment_number" min="1" max="9999" size="4"
                       placeholder="Numer mieszkania"/>
            </td>
            <td><textarea rows="4" cols="5" v-model="newAddress.additional_info" placeholder="Dodatkowe informacje"/>
            </td>
            <td>
              <input type="checkbox" v-model="newAddress.is_primary" size="1"/>
            </td>
            <td>-</td>
            <td>
              <button class="btn btn-sm btn-success" @click="saveNewAddress">
                Zapisz
              </button>
              <button
                  @click="cancelAdd()"
                  class="btn btn-sm btn-outline-danger"
              >
                X
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
    </div>
  </div>

  <delete-popup
      :showModal="showModal"
      :toDelete="toDelete"
      :error="deleteError"
      @close="closeDeletePopUp"
      @confirm-delete="deleteClient"
      :message="'Czy napewno chcesz usunąć adress'"
  />
</template>


<script setup>
import {onMounted, reactive, ref, watch} from 'vue';
import Loader from '@/component/Loader.vue';
import axios from 'axios'
import SmallLoader from "@/component/SmallLoader";
import DeletePopup from "@/component/deletePopup";
import AddressFilter from "./AddressFilter.vue";

const props = defineProps({
  clientId: Number,
  items: {
    type: Array,
    required: true
  }
});
const loading = ref(false)
const smallLoading = ref(false)
const totalPages = ref(1)
const page = ref(1)
const showNewRow = ref(false)
const filtersOpen = ref(false)


// delete popup
const showModal = ref(false)
const deleteError = ref('')
const toDelete = reactive({
  id: '',
  additional_info: '',
  apartment_number: '',
  city: '',
  country: '',
  house_number: '',
  is_primary: false,
  postal_code: '',
  state_province: '',
  street: '',
})

const filters = ref({
  street: "",
  house_number: "",
  apartment_number: "",
  postal_code: "",
  city: "",
  state_province: "",
  country: "",
  added_at: "",
})

const items = ref([])

const newAddress = reactive({
  additional_info: ref(''),
  apartment_number: ref(''),
  city: ref(''),
  country: ref(''),
  house_number: ref(''),
  is_primary: ref(false),
  postal_code: ref(''),
  state_province: ref(''),
  street: ref(''),
})

const toggleEdit = ref(false)
const resendStatus = reactive({})
const emit = defineEmits(['add-address'])

watch(filters, (newFilters) => {
  console.log("Filtry zmieniły się:", newFilters);
  loadClientAddress();
}, {
  deep: true
})

onMounted(async () => {
  await loadClientAddress();
});


function toggleAddressEdit(address) {
  toggleEdit.value = !toggleEdit.value

  address.edit = true;
}


function formatUtcDate(dateObj) {
  if (!dateObj) return ''

  // Wyciągamy datę jako string
  let str = typeof dateObj === 'string'
      ? dateObj
      : (dateObj.date || '')

  if (!str) return ''

  // Zamiana na ISO
  // const iso = str.split('.')[0].replace(' ', 'T') + 'Z'
  const date = new Date(str)

  // Format YYYY-MM-DD
  const year = date.getFullYear()
  const month = String(date.getMonth() + 1).padStart(2, '0')
  const day = String(date.getDate()).padStart(2, '0')
  const hours = String(date.getUTCHours()).padStart(2, '0')
  const minutes = String(date.getUTCMinutes()).padStart(2, '0')
  const seconds = String(date.getUTCSeconds()).padStart(2, '0')

  return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`
}

function addNew() {
  filtersOpen.value = !filtersOpen.value
  showNewRow.value = true
}

async function changePage(newPage) {
  if (newPage >= 1 && newPage <= totalPages.value) {
    page.value = newPage;
    await loadClientAddress();
  }
}

async function updatePrimary(addressId, value) {
  try {
    loading.value = true;
    await axios.put(`/api/client/address/${props.clientId}/primary`, {id: addressId, is_primary: value})

    items.value = items.value.map(item => ({
      ...item,
      is_primary: item.id === addressId
    }))

  } catch (error) {
    console.error('Błąd aktualizacji danych klienta:', error);
  } finally {
    loading.value = false;
  }
}


async function loadClientAddress() {
  if (props.clientId) {
    try {
      loading.value = true;
      const params = new URLSearchParams({
        page: page.value,
        ...filters.value,
      })

      const res = await axios.get(`/api/client/address/${props.clientId}?${params.toString()}`);
      const data = await res.data;

      items.value = data.items.map(item => ({
        ...item,
        added_at: formatUtcDate(item.added_at),
        edit: false
      }))

      let meta = data.meta;
      totalPages.value = meta.pages;
      page.value = meta.page;

    } catch (error) {
      console.error('Błąd ładowania danych klienta:', error);
    } finally {
      loading.value = false;
    }
  }
}

async function saveNewAddress() {
  const tempAddress = {...newAddress, id: null, resend: false}
  items.value.push(tempAddress)
  resetForm();

  // Próbujemy wysłać na backend
  await trySend(tempAddress, false)
  showNewRow.value = false;
}

async function trySend(address, resend) {
  if (resend) {
    smallLoading.value = true
  }
  resendStatus[address.id] = false
  try {
    const res = await axios.post(`/api/client/address/${props.clientId}/add`, address)
    const created = res.data

    // Aktualizujemy rekord w items o prawdziwe ID
    const idx = items.value.findIndex(a => a.id === address.id)
    if (idx !== -1) {
      items.value[idx] = {...created}
    }
    resendStatus[address.id] = false
  } catch (err) {
    // Jeśli błąd, ustawiamy flagę resend
    const idx = items.value.findIndex(a => a.id === address.id)
    if (idx !== -1) {
      items.value[idx].resend = true
      resendStatus[address.id] = true
    }
  } finally {
    smallLoading.value = false
  }
}

async function resendAddress(address) {
  await trySend(address, true)
}

function cancelAdd() {
  filtersOpen.value = !filtersOpen.value
  showNewRow.value = false
  resetForm();
}

function resetForm() {
  Object.keys(newAddress).forEach((key) => {
    newAddress[key] = key === 'is_primary' ? false : ''
  })
}

function cancelEdit(address) {
  filtersOpen.value = !filtersOpen.value
  address.edit = false;
}

async function updateEdit(address) {
  smallLoading.value = false;
  try {
    const res = await axios.put(`/api/client/address/${props.clientId}/update`, address);


  } catch (e) {

  } finally {
    smallLoading.value = false
    cancelEdit(address)
  }
}

function openDeletePopup(address) {
  deleteError.value = '';
  Object.assign(toDelete, address)
  showModal.value = true;
}

function addressEdit(address) {
  address.edit = true
  filtersOpen.value = !filtersOpen.value
}

async function deleteClient() {
  deleteError.value = '';

  try {
    await axios.delete(`/api/client/address/${toDelete.id}`);
    items.value = items.value.filter(c => c.id !== toDelete.id);
    showModal.value = false;
    toDelete.value = null;
  } catch (error) {
    deleteError.value = 'Nie udało się usunąć adresu klienta. Spróbuj ponownie później.';

    if (error.response && error.response.data && error.response.data.message) {
      deleteError.value = error.response.data.message;
    }
  }
}

function closeDeletePopUp() {
  showModal.value = false;
  deleteError.value = '';
}





</script>

<style scoped>

.page-container {
  display: flex;
  flex-direction: column;
  position: relative;
  height: 100%;
}

.actions {
  position: sticky;
  top: 0;
  background: white;
  z-index: 100;
  padding: 10px 0;
}

.main-content {
  display: flex;
  flex: 1;
  min-height: 0;
}

.filter-sidebar {
  transition: width 0.3s ease;
  overflow: hidden;
  width: 0;
  background: #f8f9fa;
}

.filter-sidebar.open {
  width: 30%;
  max-width: 400px;
}

.filter-content {
  padding: 10px;
  height: 100%;
  overflow-y: auto;
}

</style>

