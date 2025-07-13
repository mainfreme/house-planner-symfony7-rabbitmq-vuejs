import { createApp } from 'vue'
import ProductList from '../../src/application/Product/UI/Vue/ProductList.vue';

const app = createApp({})

// // Rejestracja globalna komponentu
app.component('product-list', ProductList)

app.mount('#app')
