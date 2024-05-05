<template>
    <div class="container mt-5">
        <h1 class="text-center">Info Aula</h1>
        <template v-if="aula">
            <div class="row mt-3">
                <div class="col-lg-4 col-12">
                    <div class="card">
                        <div class="card-header">
                            Datos del aula
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ aula.nombre }}</h5>
                            <p class="mt-3 card-text">Alias: {{ aula.alias }}</p>
                            <p class="card-text">Plazas: {{ aula.plazas }}</p>
                            <p class="card-text">Descripción: {{ aula.descripcion }}</p>
                            <p class="card-text">Disponible: <i class="fa-lg"
                                    :class="{ 'fa-solid fa-circle-check': aula.disponible, 'fa-solid fa-circle-xmark': !aula.disponible }"
                                    :style="aula.disponible ? 'color: #189A3F' : 'color: #BE1313'"></i></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-12">
                    <div class="card">
                        <div class="card-header">
                            Reservas del aula
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h5 class="card-title">{{ aula.nombre }}</h5>
                                <input type="date" name="date" id="date" v-model="fecha">
                            </div>
                            <p class="fw-bold" v-if="!reservas.length">{{ reservas.lenght }} No hay reservas</p>
                            <template v-else>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Título</th>
                                            <th scope="col">Fecha Reserva</th>
                                            <th scope="col">Plazas Ocupadas</th>
                                            <th scope="col">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="reserva in reservas" :key="reserva.id">
                                            <td class="fw-bold" scope="row">{{ reserva.id }}</td>
                                            <td>{{ reserva.titulo }}</td>
                                            <td>{{ reserva.fecha_reserva }}</td>
                                            <td>{{ reserva.plazas_ocupadas }} / {{ aula.plazas }}</td>
                                            <td>
                                                <RouterLink :to="{ name: 'reserva.info', params: { id: reserva.id } }">+ Mas info</RouterLink>
                                            </td>
                                        </tr>
                                    </tbody>

                                </table>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template v-else>
            <LoadingContent />
        </template>

    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import LoadingContent from '../../../components/LoadingContent.vue';
import useAuthStore from '../../../stores/authStore';
const props = defineProps({
    id: {
        required: true
    }
})
const authStore = useAuthStore()
const aula = ref(null)
const reservas = ref([])
const fechaActual = new Date().toISOString().split('T')[0]
const fecha = ref(fechaActual)

const getAula = async () => {
    try {
        const data = await axios.get(`/api/aulas/${props.id}`)
        aula.value = data.data
    } catch (error) {
        console.error(error);
    }
}

const getReservasAulaFecha = async () => {
    try {
        const data = await axios.get(`/api/reservas/${props.id}/${fecha.value}`)
        reservas.value = data.data
    } catch (error) {
        console.error(error);
    }
}

getAula()
getReservasAulaFecha()


watch(fecha, (newValue, oldValue) => { getReservasAulaFecha() })

</script>

<style lang="scss" scoped></style>
