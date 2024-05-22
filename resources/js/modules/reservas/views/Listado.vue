<template>
    <div class="container mt-5">
        <h1 class="text-center">Listado de actividades</h1>
        <Listado-Reservas />
    </div>
</template>

<script setup>
import { defineAsyncComponent } from 'vue';


const ListadoReservas = defineAsyncComponent(() => import('../components/ListadoReservas.vue'));

</script>

<style lang="scss" scoped></style>
