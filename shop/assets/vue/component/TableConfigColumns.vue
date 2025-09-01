<template>
  <slot name="button">
    <button
        class="btn btn-outline-secondary btn-sm"
        @click="toggleColumnConfig('show')"
    >
      ⚙️ Konfiguruj kolumny
    </button>
  </slot>

  <teleport to="body">
    <div
        class="modal fade"
        id="columnModal"
        tabindex="-1"
        aria-labelledby="columnModalLabel"
        aria-hidden="true"
        ref="columnModal"
    >
      <div class="modal-dialog">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title" id="columnModalLabel">Konfiguruj kolumny</h5>
            <button type="button" class="btn-close" @click="toggleColumnConfig('hide')"></button>
          </div>

          <div class="modal-body">
            <div class="loader-wrapper" v-if="smallLoader">
              <SmallLoader :active="smallLoader.value" :size="30"/>
            </div>
            <div v-if="!smallLoader.value" v-for="col in allColumns" :key="col.key" class="form-check">
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
            <button class="btn btn-outline-success" @click="toggleColumnConfig('hide')">
              Zapisz
            </button>
          </div>

        </div>
      </div>
    </div>
  </teleport>
</template>

<script setup>
import {ref, onMounted} from 'vue'
import SmallLoader from '@/component/SmallLoader.vue'
import {Modal} from 'bootstrap'
import axios from 'axios'

const props = defineProps({
  smallLoading: {
    type: Boolean,
    default: false,
  },
  storeName: {
    type: String,
    default: 'client'
  }
})

const emit = defineEmits(['update:columns'])

const columnModal = ref(null)
const columnModalInstance = ref(null)

const localStorageName = ref('')
const smallLoader = ref(false)
const allColumns = ref([])
const defVisibleColumn = ['id', 'name', 'nip', 'email']
const visibleColumnKeys = ref([])

onMounted(() => {
  smallLoader.value = true

  if (columnModal.value) {
    columnModalInstance.value = new Modal(columnModal.value)
  }

  localStorageName.value = props.storeName + '_local_storage'

  const saved = localStorage.getItem(localStorageName.value)
  if (saved) {
    try {
      const objSave = JSON.parse(saved)
      visibleColumnKeys.value = objSave.visibleKeys
      setTimeout(() => (smallLoader.value = false), 3000)
    } catch (e) {
      console.warn('Nie udało się sparsować localStorage:', e)
      resetColumns()
    } finally {
      smallLoader.value = false
    }
  } else {
    resetColumns()
  }
  smallLoader.value = false
})

async function toggleColumnConfig(type = 'toggle') {
  const storage = {
    columns: allColumns.value,
    visibleKeys: visibleColumnKeys.value
  }

  if (type === 'hide') {
    localStorage.setItem(localStorageName.value, JSON.stringify(storage))
    emit('update:columns', storage)
    columnModalInstance.value?.hide()
  } else if (type === 'show') {
    columnModalInstance.value?.show()

    if (allColumns.value.length === 0) {
      await loadColumnsClients()
    }

    emit('update:columns', storage)
  } else {
    columnModalInstance.value?.toggle()
  }
}

function resetColumns() {
  visibleColumnKeys.value = [...defVisibleColumn]
  const storage = {
    columns: allColumns.value,
    visibleKeys: visibleColumnKeys.value
  }
  emit('update:columns', storage)
}

async function loadColumnsClients() {
  smallLoader.value = true
  try {
    const response = await axios.get(`/api/${props.storeName}/list-columns`)
    allColumns.value = response.data
  } catch (error) {
    console.error('Błąd ładowania kolumn:', error)

  } finally {
    smallLoader.value = false
  }
}
</script>
<style>
.loader-wrapper {
  display: flex;
  justify-content: center; /* wyśrodkuj poziomo */
  padding: 30px 0; /* 30px od góry i dołu */
}
</style>
