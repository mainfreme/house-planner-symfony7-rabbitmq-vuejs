<template>
  <div class="filter-form">
    <form @submit.prevent>
      <div class="form-group">
        <label for="street">Ulica</label>
        <input
            :value="filters.street"
            @input="updateField('street', $event.target.value)"
            @change="updateField('street', $event.target.value)"
            type="text"
            id="street"
            placeholder="np. Jana Pawła II"
        />
      </div>

      <div class="form-group">
        <label for="house_number">Numer domu</label>
        <input
            :value="filters.house_number"
            @input="updateField('house_number', $event.target.value)"
            @change="updateField('house_number', $event.target.value)"
            type="text"
            id="house_number"
        />
      </div>

      <div class="form-group">
        <label for="apartment_number">Numer mieszkania</label>
        <input
            :value="filters.apartment_number"
            @input="updateField('apartment_number', $event.target.value)"
            @change="updateField('apartment_number', $event.target.value)"
            type="text"
            id="apartment_number"
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
        />
      </div>

      <div class="form-group">
        <label for="city">Miasto</label>
        <input
            :value="filters.city"
            @input="updateField('city', $event.target.value)"
            @change="updateField('city', $event.target.value)"
            type="text"
            id="city"
        />
      </div>

      <div class="form-group">
        <label for="state_province">Województwo / Prowincja</label>
        <input
            :value="filters.state_province"
            @input="updateField('state_province', $event.target.value)"
            @change="updateField('state_province', $event.target.value)"
            type="text"
            id="state_province"
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
        />
      </div>

      <div class="form-group">
        <label for="added_at">Data dodania</label>
        <input
            :value="filters.added_at"
            @input="updateField('added_at', $event.target.value)"
            @change="updateField('added_at', $event.target.value)"
            type="date"
            id="added_at"
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
const props = defineProps({
  filters: { type: Object, required: true }
});
const emit = defineEmits(["update:filters"]);

let debounceTimer = null;

function updateField(key, value) {
  const updated = { ...props.filters, [key]: value };

  clearTimeout(debounceTimer);

  debounceTimer = setTimeout(() => {
    emit("update:filters", updated);
  }, 1500);
}

function resetFilters() {
  const cleared = Object.fromEntries(
      Object.keys(props.filters).map((key) => [key, ""])
  );
  emit("update:filters", cleared);
}
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
