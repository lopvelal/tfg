<template>
    <div>
        <RouterLink v-if="autorizado" :to="{ name: 'actividad.nueva' }" class="btn btn-success mb-3"><i
                class="fa fa-bookmark me-2"></i>Nueva Actividad</RouterLink>

        <div class="row justify-content-end">
            <div class="col-4">
                <input class="float-end form-control mb-3" type="text" placeholder="Introduce tu búsqueda" name="buscar"
                    v-model="barraBusqueda" id="barraBusqueda">
            </div>
        </div>
        <template v-if="reservas">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th style="cursor: pointer;" @click="sortBy('titulo')" scope="col">Título <i
                                    v-if="sortField == 'titulo' && sortDirection == 'asc'"
                                    class="fa-solid fa-arrow-up"></i><i
                                    v-if="sortField == 'titulo' && sortDirection == 'desc'"
                                    class="fa-solid fa-arrow-down"></i></th>
                            <th style="cursor: pointer;" @click="sortBy('fecha')" scope="col">Fecha Actividad <i
                                    v-if="sortField == 'fecha' && sortDirection == 'asc'"
                                    class="fa-solid fa-arrow-up"></i><i
                                    v-if="sortField == 'fecha' && sortDirection == 'desc'"
                                    class="fa-solid fa-arrow-down"></i></th>
                            <th style="cursor: pointer;" @click="sortBy('aula_id')" scope="col">Aula <i
                                    v-if="sortField == 'aula_id' && sortDirection == 'asc'"
                                    class="fa-solid fa-arrow-up"></i><i
                                    v-if="sortField == 'aula_id' && sortDirection == 'desc'"
                                    class="fa-solid fa-arrow-down"></i></th>
                            <th scope="col">Plazas</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="reserva in reservas" :key="reserva.id">
                            <td class="fw-bold" scope="row">{{ reserva.id }}</td>
                            <td>{{ reserva.titulo }}</td>
                            <td>{{ reserva.fecha }}</td>
                            <td><router-link :to="{ name: 'aula.info', params: { id: reserva.aula.id } }"
                                    class="fw-bold">{{
                                        reserva.aula.nombre }} ({{ reserva.aula.alias }})</router-link></td>
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
                <span class="text-sm text-gray-700">
                    Mostrando del
                    <span class="fw-bold text-gray-900">{{ pagination.from }}</span> al
                    <span class="fw-bold text-gray-900">{{ pagination.to }}</span> de entre <span
                        class="fw-bold text-gray-900">{{ pagination.total }}</span> registros
                </span>
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
            <h3 class="text-center mt-4"> No hay ninguna actividad disponible </h3>
        </template>
    </div>
</template>

<script setup>
import { ref, defineAsyncComponent, computed, watch } from 'vue';
import { RouterLink } from 'vue-router';
import useAuthStore from '../../../stores/authStore';

const props = defineProps({
    miListado: {
        type: Boolean,
        default: false,
    }
})

const authStore = useAuthStore()

const LoadingContent = defineAsyncComponent(() => import('../../../components/LoadingContent.vue'))

const loading = ref(true)
const reservas = ref(null)
const pagination = ref({})
const barraBusqueda = ref('')

const sortField = ref(null)
const sortDirection = ref('asc')


const sortBy = (field) => {
    if (sortField.value === field) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
    } else {
        sortField.value = field
        sortDirection.value = 'asc'
    }
    getReservas()
}

const autorizado = computed(() => {
    return !authStore.permisos.roles.includes('alumno')
})

const getReservas = async (url = null) => {
    if (!props.miListado) {
        if (!url) {
            url = '/api/reservas'
        }
    } else {
        if (!url) {
            if (authStore.permisos.roles.includes('alumno')) {
                url = '/api/reservas/alumno'
            } else {
                url = '/api/reservas/profesor'
            }
        }
    }
    if (sortField.value) {
        url += (url.includes('?') ? '&' : '?') + `sort_by=${sortField.value}&order=${sortDirection.value}`
    }
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
        else {
            reservas.value = null;
        }
        loading.value = false
    } catch (error) {
        console.error("Error al cargar las reservas:", error);
    }
};



// watch barraBusqueda
watch(barraBusqueda, (value) => {
    if (value.length > 2) {
        getReservas(`/api/reservas?search=${value}`)
    } else {
        getReservas()
    }
})

getReservas()
</script>

<style lang="css" scoped></style>
