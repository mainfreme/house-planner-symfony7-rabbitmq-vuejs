/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import './styles/menu.css';
import './styles/list.css';


// import { createApp } from 'vue';
// import App from './vue/App.vue';
// import ProductTypeAdd from './components/AddProduct.vue';
//
// createApp(App).mount('#app');


import GlobalLoader from './plugins/global-loader'

import { createApp } from 'vue';
import App from './vue/App.vue';
import router from './vue/router'; // Import routera

const app = createApp(App);
// app.component('WoodenHouseLoader', WoodenHouseLoader)
app.use(router);

app.use(GlobalLoader)
app.mount('#app');
