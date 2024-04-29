<template>
    <template v-if="status === 'authenticated'">
        <div class="mt-2">
            <h1 class="text-6xl font-bold text-center">Bienvenido</h1>
            <RouterLink :to="{ name: 'user' }" class="underline font-bold text-xl text-upm-default">
                Enlace
            </RouterLink>
        </div>
    </template>
    <template v-else>
        <h1 class="text-6xl font-bold text-center">Cargando</h1>
    </template>
</template>

<script setup>
import { RouterLink } from 'vue-router';
import useAuthStore from '../store/authStore';
import { computed } from 'vue';


const authStore = useAuthStore()

const status = computed(() => authStore.getStatus)

</script>

<style lang="scss" scoped></style>
