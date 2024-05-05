<template>
    <div class="container mt-3">
        <h1 class="text-center">Info de la reserva {{ props.id }}</h1>
        <template v-if="reserva">
            <div class="row mt-3">
                <div class="col-lg-4 col-12">
                    <div class="card">
                        <div class="card-header">
                            Datos de la actividad
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ reserva.titulo }}</h5>
                            <p class="mt-4 card-text"><strong>Fecha: </strong>{{ reserva.fecha_reserva }}</p>
                            <p class="card-text"><strong>Descripción:</strong> {{ reserva.descripcion }}</p>
                            <p class="card-text"><strong>Plazas:</strong> {{ reserva.plazas_ocupadas ?? 0 }} /
                                {{ reserva.aula.plazas }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-12">
                    <template v-if="authStore.permisos.roles.includes('alumno')">
                        <h3>Capado por el rol</h3>
                    </template>
                    <template v-else>
                        <div class="card">
                            <div class="card-header">
                                Alumnos inscritos
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <h5 class="card-title">{{ reserva.titulo }}</h5>
                                </div>
                                <p v-if="!reservas_usuarios.length" class="fw-bold">No hay alumnos inscritos</p>
                                <table v-else class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="reserva_usuario in reservas_usuarios">
                                            <td>{{ reserva_usuario.user.name }}</td>
                                            <td>{{ reserva_usuario.user.email }}</td>
                                            <td>
                                                <a href="#" @click="eliminarUsuarioReserva(reserva_usuario.id)"
                                                    class="text-danger fw-bold">Eliminar</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </template>
        <template v-else>
            <LoadingContent />
        </template>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import useAuthStore from '../../../stores/authStore'

import LoadingContent from '../../../components/LoadingContent.vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const props = defineProps({
    id: {
        required: true
    }
})
const authStore = useAuthStore()
const reserva = ref(null)
const reservas_usuarios = ref(null)

const getInfoReserva = async () => {
    try {
        const { data } = await axios.get(`/api/reservas/${props.id}`)
        reserva.value = data
    } catch (error) {
        console.error(error);
    }
}

const getUsuariosReserva = async () => {
    try {
        const { data } = await axios.get(`/api/reservas/usuarios/${props.id}`)
        reservas_usuarios.value = data.data
    } catch (error) {
        console.error(error);
    }

}

const eliminarUsuarioReserva = async (id) => {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const { data } = await axios.delete(`/api/reservas-usuarios/${id}`)
                if (data === 'true') {
                    Swal.fire({
                        title: "Borrado",
                        text: "Se ha borrado la reserva de plaza",
                        icon: "success"
                    });
                } else {
                    Swal.fire({
                        title: "Borrado",
                        text: "No se ha podido borrar el fichero",
                        icon: "error"
                    });
                }
            } catch (error) {
                console.error(error);
            }
        }
    });

}

getUsuariosReserva()
getInfoReserva()

setInterval(async () => {
   await getUsuariosReserva()
   await getInfoReserva()
}, 6000)
</script>

<style lang="scss" scoped></style>
