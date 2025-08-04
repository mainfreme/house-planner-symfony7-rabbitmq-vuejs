<template>
  <ul>
    <li v-for="address in addresses" :key="address.id" class="mb-2">
      <div>{{ address.street }}, {{ address.city }}</div>
    </li>
  </ul>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import Loader from '@/component/Loader.vue';

const props = defineProps({ clientId: Number })
const addresses = ref([])

onMounted(async () => {
  const res = await fetch(`/api/client/${props.clientId}/addresses`)
  addresses.value = await res.json()
})
</script>

<style scoped>

</style>
