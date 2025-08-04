<template>
  <ul>
    <li v-for="contact in contacts" :key="contact.id" class="mb-2">
      <div class="font-medium">{{ contact.name }}</div>
      <div class="text-sm text-gray-600">{{ contact.phone }}</div>
    </li>
  </ul>
</template>

<script setup>
import { onMounted, ref } from 'vue'

const props = defineProps({ clientId: Number })
const contacts = ref([])

onMounted(async () => {
  const res = await fetch(`/api/client/${props.clientId}/contacts`)
  contacts.value = await res.json()
})
</script>
