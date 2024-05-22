<template>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Mis actividades</h1>
        <listado-reservas :miListado="true" />
    </div>
</template>

<script setup>
import { defineAsyncComponent } from 'vue';

const ListadoReservas = defineAsyncComponent(() => import('../../reservas/components/ListadoReservas.vue'));

</script>

<style lang="scss" scoped></style>
