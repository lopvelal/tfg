<script setup>

import { computed, defineAsyncComponent, onMounted } from 'vue'
import { initFlowbite } from 'flowbite'
import { useRoute } from 'vue-router';

// initialize components based on data attribute selectors

const Navbar = defineAsyncComponent(() => import('./components/Navbar.vue'))

const route = useRoute()

const mostrarNavbar = computed(() => route.name !== 'login')

onMounted(() => {
    initFlowbite();
});

</script>

<template>
    <header>
        <Navbar v-if="mostrarNavbar" />
    </header>
    <main :class="{ 'p-4 sm:ml-64': mostrarNavbar }">
        <RouterView />
    </main>
</template>
