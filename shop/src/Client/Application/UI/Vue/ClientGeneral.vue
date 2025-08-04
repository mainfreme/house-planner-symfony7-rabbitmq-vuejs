<template>
  <div class="client-form-container">
    <h2>Formularz Klienta</h2>

    <form @submit.prevent="handleUpdate">
      <Loader v-if="loading"/>

      <div class="form-group">
        <label for="name">Nazwa</label>
        <input id="name" type="text" v-model="formData.name">
      </div>


      <div class="row">
        <div class="col-md-4">
          <label for="nip">NIP</label>
          <input id="nip" type="text" v-model="formData.nip">
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="regon">REGON</label>
            <input id="regon" type="text" v-model="formData.regon">
          </div>
        </div>
      </div>


      <div class="form-group">
        <label for="pesel">PESEL</label>
        <input id="pesel" type="text" v-model="formData.pesel">
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input id="email" type="email" v-model="formData.email">
      </div>

      <div class="form-group phone-group">
        <div>
          <label for="phone_prefix">Prefix</label>
          <input id="phone_prefix" type="text" v-model="formData.phone_prefix" class="prefix-input">
        </div>
        <div class="phone-number-wrapper">
          <label for="number_phone">Numer telefonu</label>
          <input id="number_phone" type="tel" v-model.number="formData.number_phone">
        </div>
      </div>

      <div class="form-group">
        <label for="country">Kraj</label>
        <input id="country" type="text" v-model="formData.country">
      </div>

      <button type="submit" class="submit-button" :disabled="smallLoading">
        Aktualizuj dane
        <SmallLoader :active="smallLoading"/>
      </button>
    </form>
  </div>
</template>

<script setup>
import {onMounted, ref, watch, reactive} from 'vue'
import axios from 'axios'
import Loader from '@/component/Loader.vue'
import SmallLoader from "@/component/SmallLoader";

const props = defineProps({
  clientId: {
    type: Number,
    required: true
  }
})

const formData = reactive({
  name: '',
  nip: '',
  regon: '',
  pesel: '',
  email: '',
  phone_prefix: '',
  number_phone: '',
  country: ''
})


const loading = ref(false)
const smallLoading = ref(false)

const originalData = ref({})
const changedFields = reactive({})
let debounceTimer = null

onMounted(async () => {
  await loadClient()
})

async function loadClient() {
  loading.value = true
  try {
    const response = await axios.get(`/api/client/get-${props.clientId}`)
    const data = response.data.items

    Object.assign(formData, data)
    originalData.value = {...data}
  } catch (error) {
    console.error('Błąd ładowania danych klienta:', error)
  } finally {
    loading.value = false
  }
}

async function handleUpdate() {
  try {
    smallLoading.value = true
    await axios.put(`/api/client/update/${props.clientId}`, formData.value)
    alert('Dane zaktualizowane pomyślnie!')
  } catch (error) {
    console.error('Błąd aktualizacji danych klienta:', error)
    alert('Wystąpił błąd przy aktualizacji.')
  } finally {
    smallLoading.value = false
  }
}

Object.keys(formData).forEach((key) => {
  watch(
      () => formData[key],
      (newVal) => {
        if (originalData.value[key] !== newVal) {
          changedFields[key] = newVal
          scheduleUpdate()
        }
      }
  )
})

function scheduleUpdate() {
  if (debounceTimer) clearTimeout(debounceTimer)
  debounceTimer = setTimeout(updateChangedFields, 3000)
}

async function updateChangedFields() {
  if (Object.keys(changedFields).length === 0) return
  smallLoading.value = true
  try {
    const payload = {...changedFields}
    await axios.patch(`/api/client/update/${props.clientId}`, payload)

    for (const key in changedFields) {
      originalData.value[key] = changedFields[key]
    }
    Object.keys(changedFields).forEach(k => delete changedFields[k])

    console.log('Zaktualizowano pola:', payload)
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
  width: 100%;
  padding: 0.85rem;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
  font-size: 1rem;
  cursor: pointer;
}
</style>
