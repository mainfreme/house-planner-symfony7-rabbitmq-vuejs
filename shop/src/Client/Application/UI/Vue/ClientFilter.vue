<template>
  <div class="row">
    <div class="col-md-12" v-memo="[...Object.values(localFilters)]">
      <input v-model="localFilters.name" placeholder="Nazwa klienta" />
      <input v-model="localFilters.nip" placeholder="NIP" />
      <input v-model="localFilters.regon" placeholder="REGON" />
      <input v-model="localFilters.pesel" placeholder="PESEL" />
      <input v-model="localFilters.email" placeholder="Email" />
      <input v-model="localFilters.phoneNumber" placeholder="Telefon" />
      <input v-model="localFilters.country" placeholder="Kraj" />
    </div>

    <div class="col-md-6" v-memo="[Object.values(localFilters).join('')]">
      <button
          v-if="!isClearDisabled"
          class="btn btn-sm btn-outline-primary"
          title="Wyczyść filtr"
          @click="clearForm"
          :disabled="isClearDisabled"
      >
        Clear X
      </button>
    </div>
  </div>
</template>

<script setup>
import { reactive, watch, computed} from "vue"

const props = defineProps({
  filters: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(["update-filters"])

const localFilters = reactive({ ...props.filters })

let skipWatcher = false
let debounceTimer = null

const isClearDisabled = computed(() => {
  return Object.values(localFilters).every(v => v === "")
})

watch(
    localFilters,
    (val) => {
      if (skipWatcher) {
        skipWatcher = false
        return
      }
      clearTimeout(debounceTimer)
      debounceTimer = setTimeout(() => {
        emit("update-filters", val)
      }, 1000)
    },
    { deep: true }
)

function clearForm() {
  skipWatcher = true
  Object.keys(localFilters).forEach((key) => {
    localFilters[key] = ""
  })
  emit("update-filters", { ...localFilters })
}
</script>

<style scoped>
input {
  padding: 6px;
  border: 1px solid #ccc;
  border-radius: 4px;
}
</style>
