<template>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Listado de reservas</h1>
        <template v-if="reservas">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Titulo</th>
                        <th scope="col">Fecha Reserva</th>
                        <th scope="col">Plazas</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="reserva in reservas" :key="reserva.id">
                        <td class="fw-bold" scope="row">{{ reserva.id }}</td>
                        <td>{{ reserva.titulo }}</td>
                        <td>{{ reserva.fecha_reserva }}</td>
                        <td></td>
                        <td>
                            <RouterLink :to="{ name: 'reserva.info', params: { id: reserva.id } }">+ Mas info</RouterLink>
                        </td>
                    </tr>
                </tbody>

            </table>
            <div class="mt-4 mb-4 d-flex flex-column align-items-center">
                <!-- Help text -->
                <span class="text-sm text-gray-700">
                    Mostrando del
                    <span class="fw-bold text-gray-900">{{ pagination.from }}</span> al
                    <span class="fw-bold text-gray-900">{{ pagination.to }}</span> de entre <span
                        class="fw-bold text-gray-900">{{ pagination.total }}</span> registros
                </span>
                <!-- Buttons -->
                <div class="mt-4">
                    <button @click="getReservas(pagination.prev_page_url)" :disabled="!pagination.prev_page_url"
                        class="me-2 btn btn-primary"
                        :class="{ 'disabled': !pagination.prev_page_url }">Anterior</button>
                    <button @click="getReservas(pagination.next_page_url)" :disabled="!pagination.next_page_url"
                        class="btn btn-primary" :class="{ 'disabled': !pagination.next_page_url }">Siguiente</button>
                </div>
            </div>
        </template>
        <template v-else>
            <LoadingContent/>
        </template>
    </div>
</template>

<script setup>
import { ref, defineAsyncComponent } from 'vue';

const LoadingContent = defineAsyncComponent(() => import('../../../components/LoadingContent.vue'))

const reservas = ref(null); // Inicializa reservas como un array vacío
const pagination = ref({});

const getReservas = async (url = '/api/reservas') => {
    try {
        const alumnos = await axios.get(url);
        reservas.value = alumnos.data.data;
        pagination.value = {
            current_page: alumnos.data.current_page,
            last_page: alumnos.data.last_page,
            per_page: alumnos.data.per_page,
            next_page_url: alumnos.data.next_page_url,
            prev_page_url: alumnos.data.prev_page_url,
            total: alumnos.data.total,
            from: alumnos.data.from,
            to: alumnos.data.to,
        };
    } catch (error) {
        console.error("Error al cargar los alumnos:", error);
    }
};

getReservas()

</script>

<style lang="scss" scoped></style>
