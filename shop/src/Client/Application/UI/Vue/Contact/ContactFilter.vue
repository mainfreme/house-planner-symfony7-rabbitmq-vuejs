<template>
  <div class="filter-form">
    <form @submit.prevent>
      <div class="form-group">
        <label for="name">Imię</label>
        <input
            :value="filters.name"
            @input="updateField('name', $event.target.value)"
            @change="updateField('name', $event.target.value)"
            type="text"
            id="name"
            maxlength="100"
            placeholder="np. Jan"
        />
      </div>

      <div class="form-group">
        <label for="surname">Nazwisko</label>
        <input
            :value="filters.surname"
            @input="updateField('surname', $event.target.value)"
            @change="updateField('surname', $event.target.value)"
            type="text"
            id="surname"
            maxlength="100"
            placeholder="np. Kowalski"
        />
      </div>

      <div class="form-group">
        <label for="postal_code">Kod pocztowy</label>
        <input
            :value="filters.postal_code"
            @input="updateField('postal_code', $event.target.value)"
            @change="updateField('postal_code', $event.target.value)"
            type="text"
            id="postal_code"
            pattern="^\\d{2}-\\d{3}$"
            placeholder="00-000"
        />
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input
            :value="filters.email"
            @input="updateField('email', $event.target.value)"
            @change="updateField('email', $event.target.value)"
            type="email"
            id="email"
            placeholder="example@email.com"
        />
      </div>

      <div class="form-group">
        <label for="phoneNumber">Telefon</label>
        <input
            :value="filters.phoneNumber"
            @input="updateField('phoneNumber', $event.target.value)"
            @change="updateField('phoneNumber', $event.target.value)"
            type="text"
            id="phoneNumber"
            pattern="^\\+?[0-9\\s]{7,15}$"
            placeholder="+48123123123"
        />
      </div>

      <div class="form-group">
        <label for="country">Kraj</label>
        <input
            :value="filters.country"
            @input="updateField('country', $event.target.value)"
            @change="updateField('country', $event.target.value)"
            type="text"
            id="country"
            maxlength="2"
            placeholder="PL"
        />
      </div>

      <div class="form-group">
        <label for="language">Język</label>
        <input
            :value="filters.language"
            @input="updateField('language', $event.target.value)"
            @change="updateField('language', $event.target.value)"
            type="text"
            id="language"
            maxlength="2"
            placeholder="pl"
        />
      </div>

      <div class="form-group">
        <label for="added_from">Data dodania do</label>
        <input
            :value="filters.added_from"
            @input="updateField('added_from', $event.target.value)"
            @change="updateField('added_from', $event.target.value)"
            type="date"
            id="added_from"
            :max="today"
        />
      </div>
      <div class="form-group">
        <label for="added_to">Data dodania do</label>
        <input
            :value="filters.added_to"
            @input="updateField('added_to', $event.target.value)"
            @change="updateField('added_to', $event.target.value)"
            type="date"
            id="added_to"
            :max="today"
        />
      </div>


      <button
          type="button"
          @click="resetFilters"
          class="btn btn-sm btn-outline-primary"
          title="Wyczyść filtr"
      >
        Clear X
      </button>
    </form>
  </div>
</template>

<script setup>
import { computed } from "vue"

const props = defineProps({
  filters: { type: Object, required: true }
})
const emit = defineEmits(["update:filters"])

let debounceTimer = null

function updateField(key, value) {
  const updated = { ...props.filters, [key]: value }

  clearTimeout(debounceTimer)
  debounceTimer = setTimeout(() => {
    emit("update:filters", updated)
  }, 1500)
}

function resetFilters() {
  const cleared = Object.fromEntries(
      Object.keys(props.filters).map((key) => [key, ""])
  )
  emit("update:filters", cleared)
}

// dzisiejsza data dla ograniczenia w polu daty
const today = computed(() => new Date().toISOString().split("T")[0])
</script>

<style scoped>
.filter-form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.form-group {
  display: flex;
  flex-direction: column;
}

input {
  padding: 0.4rem;
  border: 1px solid #ccc;
  border-radius: 4px;
}
input:-webkit-autofill {
  animation-name: onAutoFillStart;
  animation-duration: 0.01s;
}

@keyframes onAutoFillStart {}
</style>
