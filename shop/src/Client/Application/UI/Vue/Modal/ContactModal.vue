<template>
  <div class="modal">
    <form @submit.prevent="save">
      <input v-model="form.name" placeholder="Imię" required />
      <input v-model="form.surname" placeholder="Nazwisko" required />
      <input v-model="form.email" placeholder="Email" />
      <input v-model="form.areaCode" placeholder="Kierunkowy" />
      <input v-model="form.phoneNumber" placeholder="Numer telefonu" />
      <input v-model="form.country" placeholder="Kraj" />
      <input v-model="form.language" placeholder="Język" />
      <textarea rows="10" cols="5" v-model="form.note" placeholder="Notatka" />

      <button type="submit">{{ contact ? 'Zapisz' : 'Dodaj' }}</button>
      <button type="button" @click="$emit('cancel')">Anuluj</button>
    </form>
  </div>
</template>

<script lang="ts">
import { defineComponent, reactive, onMounted } from 'vue'
import axios from 'axios'

export default {
  name: "ContactModal",
  props: {
    clientId: { type: Number, required: true },
    contact: { type: Object, required: false }
  },
  setup(props, { emit }) {
    const form = reactive({
      name: props.contact?.name || '',
      surname: props.contact?.surname || '',
      email: props.contact?.email || '',
      phoneNumber: props.contact?.phoneNumber || '',
      country: props.contact?.country || '',
      language: props.contact?.language || '',
      note: props.contact?.note || '',
      areaCode: props.contact?.areaCode || ''
    })

    const save = async () => {
      if (props.contact) {
        await axios.put(`/api/contacts/${props.contact.id}`, form)
      } else {
        await axios.post(`/api/clients/${props.clientId}/contacts`, form)
      }
      emit('saved')
    }

    return { form, save }
  }
}
</script>
