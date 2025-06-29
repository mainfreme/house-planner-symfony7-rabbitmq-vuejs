/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

import './styles/app.css';
import './styles/menu.css';
import './styles/list.css';


import Loader from './vue/component/Loader'

import { createApp } from 'vue';
import App from './vue/App.vue';
import router from './vue/router'; // Import routera

const app = createApp(App);
// app.component('WoodenHouseLoader', WoodenHouseLoader)
app.use(router);

app.use(Loader)
app.mount('#app');
