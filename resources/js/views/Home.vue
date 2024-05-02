<template>
    <div class="mt-2">
        <h1 class="">Bienvenido {{  }}</h1>
        <RouterLink :to="{ name: 'user' }" class="">
            Enlace
        </RouterLink>
    </div>
</template>

<script setup>
import { RouterLink } from 'vue-router';
import useAuthStore from '../stores/authStore';

const authUser = useAuthStore()

</script>

<style lang="scss" scoped></style>
