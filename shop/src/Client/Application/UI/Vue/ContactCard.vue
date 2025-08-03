<template>
  <div class="card">
    <p><strong>{{ contact.name }} {{ contact.surname }}</strong></p>
    <p>{{ contact.email }} | {{ contact.phoneNumber }}</p>
    <p>{{ shortNote }}</p>
    <button @click="openEdit">Edytuj</button>
    <button @click="remove">Usuń</button>

    <ContactModal
        v-if="editing"
        :clientId="contact.client.id"
        :contact="contact"
        @saved="onSaved"
        @cancel="editing = false" />
  </div>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue'
import ContactModal from './Modal/ContactModal.vue'
import axios from 'axios'

export default {
  name: 'ContactCard',
  props: {
    contact: { type: Object, required: true }
  },
  components: {
    ContactModal
  },

  computed: {
    shortNote(): string {
      return this.contact.note.length > 100 ? this.contact.note.slice(0, 100) + '…' : this.contact.note
    }
  },
  setup(props, { emit }) {
    const editing = ref(false)

    const openEdit = () => editing.value = true
    const remove = async () => {
      await axios.delete(`/api/contacts/${props.contact.id}`)
      emit('deleted')
    }
    const onSaved = () => {
      editing.value = false
      emit('edited')
    }

    return { editing, openEdit, remove, onSaved }
  }
}
</script>

<style scoped>

</style>
