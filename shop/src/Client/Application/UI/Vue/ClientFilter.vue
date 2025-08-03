<template>
  <div class="row">
    <div class="col-md-12">
      <input v-model="localFilters.name" placeholder="Nazwa klienta"/>
      <input v-model="localFilters.nip" placeholder="NIP"/>
      <input v-model="localFilters.regon" placeholder="REGON"/>
      <input v-model="localFilters.pesel" placeholder="PESEL"/>
      <input v-model="localFilters.email" placeholder="Email"/>
      <input v-model="localFilters.number_phone" placeholder="Telefon"/>
      <input v-model="localFilters.country" placeholder="Kraj"/>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ClientFilter',
  props: {
    filters: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      localFilters: {...this.filters}
    }
  },
  watch: {
    localFilters: {
      handler(val) {
        this.$emit('update-filters', val)
      },
      deep: true
    }
  }
}
</script>

<style scoped>
input {
  padding: 6px;
  border: 1px solid #ccc;
  border-radius: 4px;
}
</style>
