<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const apiUrl = "http://localhost:8000/api/items"; // Symfony API
const items = ref([]);
const form = ref({
  id: null,
  type: "plant",
  name: "",
  latin_name: "",
  product_category: "",
  purchase_date: "",
  producer: "",
  price: "",
  quantity: "",
  meta: {},
});

const loadItems = async () => {
  const res = await axios.get(apiUrl);
  items.value = res.data;
};

const saveItem = async () => {
  if (form.value.id) {
    await axios.put(`${apiUrl}/${form.value.id}`, form.value);
  } else {
    await axios.post(apiUrl, form.value);
  }
  resetForm();
  loadItems();
};

const editItem = (item) => {
  form.value = { ...item, id: item.id };
};

const deleteItem = async (id) => {
  await axios.delete(`${apiUrl}/${id}`);
  loadItems();
};

const resetForm = () => {
  form.value = {
    id: null,
    type: "plant",
    name: "",
    latin_name: "",
    product_category: "",
    purchase_date: "",
    producer: "",
    price: "",
    quantity: "",
    meta: {},
  };
};

onMounted(loadItems);
</script>

<template>
  <div class="p-4">
    <h2>CRUD Items (Products & Plants)</h2>

    <!-- FORM -->
    <form @submit.prevent="saveItem" class="mb-4">
      <label>Type:
        <select v-model="form.type">
          <option value="plant">Plant</option>
          <option value="product">Product</option>
        </select>
      </label>
      <input v-model="form.name" placeholder="Name" required />
      <input v-model="form.latin_name" placeholder="Latin Name" />
      <input v-model="form.product_category" placeholder="Product Category" />
      <input v-model="form.purchase_date" type="date" />
      <input v-model="form.producer" placeholder="Producer" />
      <input v-model="form.price" type="number" step="0.01" placeholder="Price" />
      <input v-model="form.quantity" type="number" placeholder="Quantity" />
      <button type="submit">{{ form.id ? "Update" : "Add" }}</button>
      <button type="button" @click="resetForm">Clear</button>
    </form>

    <!-- TABLE -->
    <table border="1" cellpadding="5">
      <thead>
      <tr>
        <th>ID</th>
        <th>Type</th>
        <th>Name</th>
        <th>Producer</th>
        <th>Price</th>
        <th>Qty</th>
        <th>Actions</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="item in items" :key="item.id">
        <td>{{ item.id }}</td>
        <td>{{ item.type }}</td>
        <td>{{ item.name }}</td>
        <td>{{ item.producer }}</td>
        <td>{{ item.price }}</td>
        <td>{{ item.quantity }}</td>
        <td>
          <button @click="editItem(item)">Edit</button>
          <button @click="deleteItem(item.id)">Delete</button>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>
