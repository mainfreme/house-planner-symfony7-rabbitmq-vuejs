import { createRouter, createWebHistory } from 'vue-router'

// Importuj komponenty
import WoodenHouseConfigurator from '../../src/Application/WoodCalculate/UI/Vue/components/WoodenHouseConfigurator.vue';
import ProductList from '../../src/Product/Application/UI/Vue/ProductList.vue';

const routes = [
    {
        path: '/product/konfigurator-domku',
        name: 'WoodenHouseConfigurator',
        component: WoodenHouseConfigurator
    },
    {
        path: '/product/:category?',
        name: 'product-list',
        component: ProductList,
        props: true
    },
    // Możesz dodać inne trasy tutaj
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router
