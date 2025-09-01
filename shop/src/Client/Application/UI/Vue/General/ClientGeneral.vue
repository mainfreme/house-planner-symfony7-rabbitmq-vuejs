<template>
  <div class="client-form-container">
    <h2 v-if="props.parentName !== null" v-once>Formularz Klienta</h2>

    <form @submit.prevent="handleUpdate">
      <Loader v-if="loading"/>

      <div class="row" v-memo="[formData.name]">
        <label for="name" v-once>Nazwa</label>
        <input id="name" type="text" v-model="formData.name">
      </div>

      <div class="row" v-memo="[formData.isCompany]">
        <div class="col-md-4 d-flex align-items-center justify-content-between">
          <label for="company" v-once>Firma</label>
          <input id="company" type="checkbox" v-model="formData.isCompany">
        </div>
      </div>

      <div class="row" v-memo="[formData.nip, formData.regon, formData.pesel, formData.isCompany]">
        <section class="d-flex align-items-center justify-content-between" id="company">
          <div class="col-md-4">
            <label for="nip" v-once>NIP</label>
            <input id="nip" type="text" v-model="formData.nip">
          </div>
          <div class="col-md-4">
            <label for="regon" v-once>REGON</label>
            <input id="regon" type="text" v-model="formData.regon">
          </div>
          <div class="col-md-4">
            <div v-if="formData.isCompany === false" v-memo="[formData.pesel]">
              <label for="pesel" v-once>PESEL</label>
              <input id="pesel" type="text" v-model="formData.pesel">
            </div>
          </div>
        </section>
      </div>

      <div class="form-group" v-memo="[formData.email]">
        <label for="email" v-once>Email</label>
        <input id="email" type="email" v-model="formData.email">
      </div>

      <div class="form-group phone-group" v-memo="[formData.phonePrefix, formData.phoneNumber]">
        <div>
          <label for="phonePrefix" v-once>Prefix</label>
          <input id="phonePrefix" type="text" v-model="formData.phonePrefix" class="prefix-input">
        </div>
        <div class="phone-number-wrapper">
          <label for="phoneNumber" v-once>Numer telefonu</label>
          <input id="phoneNumber" type="tel" v-model.number="formData.phoneNumber">
        </div>
      </div>

      <div class="form-group" v-memo="[formData.country]">
        <label for="country" v-once>Kraj</label>
        <input id="country" type="text" v-model="formData.country">
      </div>

      <button type="submit" class="submit-button" :disabled="smallLoading" v-memo="[smallLoading]">
        Aktualizuj dane
        <SmallLoader :active="smallLoading"/>
      </button>
    </form>
  </div>
</template>

<script setup>
import { onMounted, onUnmounted, ref, watch, reactive } from 'vue'
import axios from 'axios'
import Loader from '@/component/Loader.vue'
import SmallLoader from "@/component/SmallLoader";

const props = defineProps({
  clientId: { type: Number },
  parentName: { type: String, default: null }
})

const formData = reactive({
  name: ref(''),
  nip: ref(''),
  regon: ref(''),
  pesel: ref(''),
  email: ref(''),
  phonePrefix: ref(''),
  phoneNumber: ref(''),
  country: ref(''),
  isCompany: ref(true)
})

const loading = ref(false)
const smallLoading = ref(false)

const originalData = ref({})
const blockAutoUpdate = ref(false)

const changedFields = reactive({})
let debounceTimer = null

function clearDebounce() {
  if (debounceTimer) {
    clearTimeout(debounceTimer)
    debounceTimer = null
  }
}
function resetChanged() {
  Object.keys(changedFields).forEach(k => delete changedFields[k])
}

onMounted(async () => {
  await loadClient()
})

onUnmounted(() => {
  clearDebounce()
})

async function loadClient() {
  if (!props.clientId) return
  loading.value = true

  blockAutoUpdate.value = true
  clearDebounce()
  resetChanged()

  try {
    const { data } = await axios.get(`/api/client/get-${props.clientId}`)

    originalData.value = { ...data }
    Object.assign(formData, data)
  } catch (error) {
    console.error('Błąd ładowania danych klienta:', error)
  } finally {
    blockAutoUpdate.value = false
    loading.value = false
  }
}

async function handleUpdate() {
  blockAutoUpdate.value = true
  clearDebounce()
  resetChanged()

  try {
    smallLoading.value = true
    await axios.put(`/api/client/update/${props.clientId}`, formData)

    Object.assign(originalData.value, JSON.parse(JSON.stringify(formData)))
    alert('Dane zaktualizowane pomyślnie!')
  } catch (error) {
    console.error('Błąd aktualizacji danych klienta:', error)
    alert('Wystąpił błąd przy aktualizacji.')
  } finally {
    smallLoading.value = false
    blockAutoUpdate.value = false
  }
}

Object.keys(formData).forEach((key) => {
  watch(
      () => formData[key],
      (newVal) => {
        if (blockAutoUpdate.value) return

        if (originalData.value[key] !== newVal) {
          changedFields[key] = newVal
          scheduleUpdate()
        }
      }
  )
})

function scheduleUpdate() {
  if (blockAutoUpdate.value) return
  clearDebounce()
  debounceTimer = setTimeout(updateChangedFields, 4000)
}

async function updateChangedFields() {
  if (blockAutoUpdate.value) return
  const keys = Object.keys(changedFields)
  if (keys.length === 0) return

  smallLoading.value = true
  try {
    const changeFields = { ...changedFields }
    await axios.patch(`/api/client/update/${props.clientId}`, changeFields)

    for (const k of keys) originalData.value[k] = changedFields[k]
    resetChanged()

    console.log('Zaktualizowano pola:', changeFields)
  } catch (error) {
    console.error('Błąd przy aktualizacji pól:', error)
  } finally {
    smallLoading.value = false
  }
}
</script>


<style scoped>
.client-form-container {
  max-width: 100%;
  margin: 2rem auto;
  padding: 2rem;
  font-family: sans-serif;
}

.form-group {
  margin-bottom: 1.25rem;
}

label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: bold;
}

input {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

.phone-group {
  display: flex;
  gap: 1rem;
  align-items: flex-end;
}

.prefix-input {
  width: 80px;
}

.phone-number-wrapper {
  flex-grow: 1;
}

.submit-button {
  width: 225px;
  padding: 0.85rem;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
  font-size: 1rem;
  cursor: pointer;
}
</style>
