<template>
  <div>
    <h2>Kontakty klienta</h2>

    <div class="grid grid-cols-3 gap-4 mb-4">
      <div v-for="contact in contacts" :key="contact.id" class="p-4 border rounded shadow">
        <div v-if="editingContact?.id !== contact.id">
          <p><strong>{{ contact.name }} {{ contact.surname }}</strong></p>
          <p>{{ contact.email }}</p>
          <p>{{ contact.phone }}</p>
          <button @click="editContact(contact)">Edytuj</button>
          <button @click="deleteContact(contact.id)">Usuń</button>
        </div>
        <div v-else>
          <input v-model="editingContact.name" placeholder="Imię" />
          <input v-model="editingContact.surname" placeholder="Nazwisko" />
          <input v-model="editingContact.email" placeholder="Email" />
          <input v-model="editingContact.phone" placeholder="Telefon" />
          <button @click="saveEditedContact">Zapisz</button>
          <button @click="cancelEdit">Anuluj</button>
        </div>
      </div>
    </div>

    <h3>Dodaj nowy kontakt</h3>
    <form @submit.prevent="addContact">
      <input v-model="newContact.name" placeholder="Imię" required />
      <input v-model="newContact.surname" placeholder="Nazwisko" required />
      <input v-model="newContact.email" placeholder="Email" required />
      <input v-model="newContact.phone" placeholder="Telefon" required />
      <button type="submit">Dodaj</button>
    </form>
  </div>
</template>

<script lang="js">
import { defineComponent, ref, onMounted } from 'vue'
import axios from 'axios'

export default defineComponent({
  name: 'ContactList',
  props: {
    clientId: {
      type: Number,
      required: true
    }
  },
  setup(props) {
    const contacts = ref([])
    const newContact = ref({
      name: '',
      surname: '',
      email: '',
      phone: ''
    })

    const editingContact = ref(null)

    const fetchContacts = async () => {
      const response = await axios.get(`/api/client/${props.clientId}/contacts`)
      contacts.value = response.data
    }

    const addContact = async () => {
      const response = await axios.post(`/api/client/${props.clientId}/contacts`, {
        ...newContact.value
      })
      contacts.value.push(response.data)
      newContact.value = { name: '', surname: '', email: '', phone: '' }
    }

    const editContact = (contact) => {
      editingContact.value = { ...contact }
    }

    const saveEditedContact = async () => {
      if (!editingContact.value) return
      const response = await axios.put(
          `/api/client/${props.clientId}/contacts/${editingContact.value.id}`,
          editingContact.value
      )
      const index = contacts.value.findIndex(c => c.id === editingContact.value.id)
      if (index !== -1) contacts.value[index] = response.data
      editingContact.value = null
    }

    const cancelEdit = () => {
      editingContact.value = null
    }

    const deleteContact = async (id) => {
      await axios.delete(`/api/client/${props.clientId}/contacts/${id}`)
      contacts.value = contacts.value.filter(c => c.id !== id)
    }

    onMounted(fetchContacts)

    return {
      contacts,
      newContact,
      editingContact,
      addContact,
      editContact,
      saveEditedContact,
      cancelEdit,
      deleteContact
    }
  }
})
</script>

<style scoped>
input {
  display: block;
  margin-bottom: 8px;
  padding: 4px;
}
button {
  margin-right: 8px;
}
</style>
