import './styles/app.css';
import './styles/menu.css';
import './styles/list.css';


import Loader from './vue/component/Loader'

import axios from 'axios'
import { createApp } from 'vue';
import App from './vue/App.vue';
import router from './vue/router';
import ProductList from "../src/Product/Application/UI/Vue/ProductList";

const app = createApp(App);
// app.component('WoodenHouseLoader', WoodenHouseLoader)
app.use(router);
app.config.globalProperties.$axios = axios

app.use(Loader)
// Rejestracja globalna komponentu
app.component('product-list', ProductList)
app.mount('#app');
