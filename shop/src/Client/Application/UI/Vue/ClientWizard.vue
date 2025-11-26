<template>
  <div class="container mt-4">
    <ul class="nav nav-pills mb-4 justify-content-center">
      <li class="nav-item" v-for="(step, index) in steps" :key="index">
        <button
            class="nav-link"
            :class="{ active: currentStep === index }"
            @click="goToStep(index)"
        >
          {{ step.label }}
        </button>
      </li>
    </ul>

    <hr />

    <!-- Krok 1: Dane klienta -->
    <div v-if="currentStep === 0">
<!--          :data="formData.client" @update="formData.client = $event"-->
      <ClientForm
          v-if="currentStep === 0"
          v-model="formData.client"
          :parentName="'Nowy klient'"
      />
      <div class="d-flex justify-content-end mt-4">
        <button
            class="btn btn-success"
            :disabled="smallLoading"
            @click="saveClientAndNext"
        >
          <span v-if="smallLoading">Zapisywanie...</span>
          <span v-else>Zapisz i przejdź dalej</span>
        </button>
      </div>
    </div>

    <!-- Krok 2: Adres -->
    <div v-if="currentStep === 1">
      <ClientAddress v-model="formData.address" />

      <div class="d-flex justify-content-between mt-4">
        <button class="btn btn-secondary" @click="prevStep">Wstecz</button>
        <button class="btn btn-primary" @click="nextStep">Dalej</button>
      </div>
    </div>

    <!-- Krok 3: Kontakt -->
    <div v-if="currentStep === 2">
      <ClientContact v-model="formData.contact" />

      <div class="d-flex justify-content-between mt-4">
        <button class="btn btn-secondary" @click="prevStep">Wstecz</button>
        <button
            class="btn btn-success"
            :disabled="smallLoading"
            @click="handleSubmit"
        >
          <SmallLoader :active="smallLoading"/>
          <span v-if="smallLoading">Zapisywanie...</span>
          <span v-else>Zapisz klienta</span>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import {ref, reactive} from "vue";
import axios from "axios";
import SmallLoader from "@/component/SmallLoader.vue";
import ClientForm from "./General/ClientGeneral.vue";
import ClientContact from "./Contact/ContactCard.vue";
import ClientAddress from "./Address/AddressCard.vue";

const currentStep = ref(0);

const smallLoading = ref(false);
const steps = [
  { label: "Dane klienta" },
  { label: "Adres" },
  { label: "Kontakt" },
];

const formData = reactive({
  client: {},
  address: {},
  contact: {},
});

// Nawigacja między krokami
function goToStep(index) {
  currentStep.value = index;
}
function nextStep() {
  if (currentStep.value < steps.length - 1) currentStep.value++;
}
function prevStep() {
  if (currentStep.value > 0) currentStep.value--;
}

// 🔹 Funkcja zapisująca dane klienta z 1 kroku i przechodząca dalej
async function saveClientAndNext() {
  smallLoading.value = true;
  try {
    const payload = formData.client;
    const response = await axios.post("/api/client/add", payload);

    // Możesz tu zapisać ID klienta, jeśli backend zwraca
    formData.client = response.data;
    console.log(formData.client);
    return;
    nextStep();
  } catch (error) {
    console.error("Błąd zapisu klienta:", error);
    alert("Nie udało się zapisać danych klienta.");
  } finally {
    smallLoading.value = false;
  }
}

// 🔹 Zapis końcowy (całość)
async function handleSubmit() {
  this.loading.value = true;
  try {
    const payload = {
      clientId: formData.client.id, // jeśli masz ID
      address: formData.address,
      contact: formData.contact,
    };

    await axios.post("/api/client/update-details", payload);

    alert("Klient został w pełni zapisany!");
    currentStep.value = 0; // reset
  } catch (error) {
    console.error("Błąd zapisu danych kontaktowych:", error);
    alert("Nie udało się zapisać danych klienta.");
  } finally {
    this.loading.value = false;
  }
}


</script>
