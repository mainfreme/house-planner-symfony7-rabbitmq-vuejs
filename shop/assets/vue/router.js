import { createRouter, createWebHistory } from 'vue-router'

// Importuj komponenty
import WoodenHouseConfigurator from '../../src/Application/WoodCalculate/UI/Vue/components/WoodenHouseConfigurator.vue';
import ProductList from '../../src/Application/Product/UI/Vue/ProductList.vue';

const routes = [
    {
        path: '/product/konfigurator-domku',
        name: 'WoodenHouseConfigurator',
        component: WoodenHouseConfigurator
    },
    {
        path: '/product',
        name: 'product-list',
        component: ProductList
    },
    // Możesz dodać inne trasy tutaj
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router
