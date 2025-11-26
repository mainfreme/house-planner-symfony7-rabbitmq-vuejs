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
        <button class="btn btn-outline-primary btn-sm" @click="loadClientContacts">
          Odśwież
        </button>
      </div>
    </div>

    <div class="main-content d-flex">
      <div :class="['filter-sidebar', { open: filtersOpen }]">
        <div v-show="filtersOpen" class="filter-content">
          <contact-filter v-model:filters="filters"/>
        </div>
      </div>
      <Loader v-if="loading"/>
      <div v-else class="content flex-grow-1">
        <table class="table table-striped" style="max-width: 100%; display: table;">
          <thead class="table-light position-sticky top-0">
          <tr>
            <th>ID</th>
            <th>Imię</th>
            <th>Nazwisko</th>
            <th>Email</th>
            <th>Telefon</th>
            <th>Kod pocztowy</th>
            <th>Kraj</th>
            <th>Język</th>
            <th>Data dodania</th>
            <th>Akcje</th>
          </tr>
          </thead>
          <tbody>
          <!-- Wiersze w trybie edycji -->
          <tr v-for="contact in contacts" :key="contact.id">
            <template v-if="contact.edit">
              <td>-</td>
              <td><input v-model="contact.name" maxlength="100" size="15" placeholder="Imię" required/></td>
              <td><input v-model="contact.surname" maxlength="100" size="15" placeholder="Nazwisko" required/></td>
              <td><input v-model="contact.email" type="email" size="20" placeholder="Email"/></td>
              <td><input v-model="contact.phoneNumber" size="15" placeholder="Telefon"/></td>
              <td><input v-model="contact.postal_code" size="7" placeholder="00-000"/></td>
              <td><input v-model="contact.country" size="5" placeholder="PL"/></td>
              <td><input v-model="contact.language" size="5" placeholder="pl"/></td>
              <td>-</td>
              <td>
                <button @click="updateEdit(contact)" class="btn btn-sm btn-outline-success">
                  Zapisz
                </button>
                <button @click="cancelEdit(contact)" class="btn btn-sm btn-outline-danger">
                  X
                </button>
              </td>
            </template>

            <!-- Wiersze w trybie podglądu -->
            <template v-else>
              <td>{{ contact.id }}</td>
              <td>{{ contact.name }}</td>
              <td>{{ contact.surname }}</td>
              <td>{{ contact.email }}</td>
              <td>{{ contact.phoneNumber }}</td>
              <td>{{ contact.postal_code }}</td>
              <td>{{ contact.country }}</td>
              <td>{{ contact.language }}</td>
              <td>{{ contact.added_to }}</td>
              <td>
                <button @click="contact.edit = true" class="btn btn-sm btn-outline-primary">
                  Edytuj
                </button>
                <button @click="openDeletePopup(contact)" class="btn btn-sm btn-outline-danger">
                  Usuń
                </button>
              </td>
            </template>
          </tr>

          <!-- Wiersz dodawania nowego klienta -->
          <tr v-if="showNewRow">
            <td>-</td>
            <td><input v-model="newContact.name" maxlength="100" size="15" placeholder="Imię" required/></td>
            <td><input v-model="newContact.surname" maxlength="100" size="15" placeholder="Nazwisko" required/></td>
            <td><input v-model="newContact.email" type="email" size="20" placeholder="Email"/></td>
            <td><input v-model="newContact.phoneNumber" size="15" placeholder="Telefon"/></td>
            <td><input v-model="newContact.postal_code" size="7" placeholder="00-000"/></td>
            <td><input v-model="newContact.country" size="5" placeholder="PL"/></td>
            <td><input v-model="newContact.language" size="5" placeholder="pl"/></td>
            <td>-</td>
            <td>
              <button class="btn btn-sm btn-success" @click="saveNewContact">
                Zapisz
              </button>
              <button @click="cancelAdd()" class="btn btn-sm btn-outline-danger">
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
      :message="'Czy na pewno chcesz usunąć kontakt ?'"
  />
</template>
<script setup>
import { onMounted, reactive, ref, watch } from 'vue';
import Loader from '@/component/Loader.vue';
import axios from 'axios';
import SmallLoader from "@/component/SmallLoader";
import DeletePopup from "@/component/deletePopup";
import ContactFilter from "./ContactFilter";

const props = defineProps({
  clientId: Number
})

const contacts = ref([])
const loading = ref(false)
const smallLoading = ref(false)
const totalPages = ref(1)
const page = ref(1)
const showNewRow = ref(false)
const filtersOpen = ref(false)

// delete popup
const showModal = ref(false)
const deleteError = ref('')
const toDelete = reactive({})

const filters = ref({
  street: '',
  name: '',
  surname: '',
  postal_code: '',
  country: '',
  language: '',
  added_from: '',
  added_to: ''
})

const newContact = reactive({
  clientId: null,
  name: '',
  surname: '',
  postal_code: '',
  email: '',
  phoneNumber: '',
  country: '',
  language: ''
})

const resendStatus = reactive({})

onMounted(async () => {
  await loadClientContacts();
})

watch(filters, () => {
  loadClientContacts();
}, { deep: true })

function addNew() {
  filtersOpen.value = false
  showNewRow.value = true
  resetForm()
}

async function saveNewContact() {
  try {
    // newContact.clientId = props.clientId

    const res = await axios.post(`/api/contact/${props.clientId}/add`, newContact)

    console.log(res)
    const created = res.data

    contacts.value.push({
      ...created,
      edit: false
    })

    showNewRow.value = false
    resetForm()
  } catch (err) {
    console.error("Błąd dodawania kontaktu:", err)
  }
}

function cancelAdd() {
  showNewRow.value = false
  resetForm()
}

function resetForm() {
  newContact.clientId = null
  newContact.name = ''
  newContact.surname = ''
  newContact.postal_code = ''
  newContact.email = ''
  newContact.phoneNumber = ''
  newContact.country = ''
  newContact.language = ''
}

async function loadClientContacts() {
  if (!props.clientId) return

  try {
    loading.value = true
    const params = new URLSearchParams({
      page: page.value,
      ...filters.value,
    })

    const res = await axios.get(`/api/contact/${props.clientId}/list?${params.toString()}`)
    const data = res.data

    contacts.value = data.data.map(item => ({
      ...item,
      edit: false
    }))

    totalPages.value = data.meta.pages
    page.value = data.meta.page
  } catch (error) {
    console.error('Błąd ładowania danych klienta:', error);
  } finally {
    loading.value = false;
  }
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
