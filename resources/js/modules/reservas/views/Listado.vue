<template>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Listado de actividades</h1>
        <RouterLink v-if="autorizado" :to="{ name: 'actividad.nueva' }" class="btn btn-success mb-3"><i class="fa fa-bookmark me-2"></i>Nueva Actividad</RouterLink>
        <template v-if="reservas">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Titulo</th>
                            <th scope="col">Fecha Actividad</th>
                            <th scope="col">Aula</th>
                            <th scope="col">Plazas</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="reserva in reservas" :key="reserva.id">
                            <td class="fw-bold" scope="row">{{ reserva.id }}</td>
                            <td>{{ reserva.titulo }}</td>
                            <td>{{ reserva.fecha }}</td>
                            <td>{{ reserva.aula.nombre }} ({{ reserva.aula.id }})</td>
                            <td>{{ reserva.plazas_ocupadas ?? 0 }} / {{ reserva.aula.plazas }}</td>
                            <td>
                                <RouterLink :to="{ name: 'actividad.info', params: { id: reserva.id } }"
                                    class="fw-bold">
                                    + Mas info
                                </RouterLink>
                            </td>
                        </tr>
                    </tbody>

                </table>
            </div>

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
        <template v-else-if="loading">
            <LoadingContent />
        </template>
        <template v-else>
            <h3 class="text-center"> No se han encontrado registros </h3>
        </template>
    </div>
</template>

<script setup>
import { ref, defineAsyncComponent, computed } from 'vue';
import { RouterLink } from 'vue-router';
import useAuthStore from '../../../stores/authStore';

const authStore = useAuthStore()

const LoadingContent = defineAsyncComponent(() => import('../../../components/LoadingContent.vue'))

const loading = ref(true)
const reservas = ref(null)
const pagination = ref({})

const autorizado = computed(() => {
    return !authStore.permisos.roles.includes('alumno')
})


const getReservas = async (url = '/api/reservas') => {
    try {
        const { data } = await axios.get(url);
        if (data.data.length) {
            reservas.value = data.data;
            pagination.value = {
                current_page: data.current_page,
                last_page: data.last_page,
                per_page: data.per_page,
                next_page_url: data.next_page_url,
                prev_page_url: data.prev_page_url,
                total: data.total,
                from: data.from,
                to: data.to,
            };
        }
        loading.value = false
    } catch (error) {
        console.error("Error al cargar las reservas:", error);
    }
};

getReservas()

</script>

<style lang="scss" scoped></style>
