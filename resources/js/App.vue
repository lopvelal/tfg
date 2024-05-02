<script setup>

import { computed, defineAsyncComponent, onMounted } from 'vue'
import { useRoute } from 'vue-router';
import useAuthStore from './stores/authStore';
import Navbar from './components/Navbar.vue'

// initialize components based on data attribute selectors
const Loading = defineAsyncComponent(() => import('./components/Loading.vue'))

const route = useRoute()

const loginRoute = computed(() => route.name === 'login')

const authStore = useAuthStore()

const autenticado = computed(() => authStore.getStatus === 'authenticated' ? true : false)

</script>

<template>
    <template v-if="autenticado || loginRoute">
        <header>
            <Navbar v-if="!loginRoute" />
        </header>
        <main>
            <RouterView />
        </main>
    </template>
    <template v-else>
        <Loading />
    </template>
</template>
