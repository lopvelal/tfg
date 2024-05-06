<template>
    <div class="container mt-3">
        <h1 class="text-center">Información de la actividad</h1>
        <template v-if="reserva">
            <div class="row mt-3">
                <div class="col-lg-4 col-12">
                    <div class="card">
                        <div class="card-header">
                            Datos de la actividad
                        </div>
                        <div class="card-body">
                            <template v-if="modoEdicion">
                                <form @submit.prevent="actualizarInfo">
                                    <div class="mb-3">
                                        <label for="titulo" class="form-label fw-bold">Título</label>
                                        <input v-model="form.titulo" type="text" class="form-control" id="titulo"
                                            placeholder="Ingrese el título de la actividad">
                                    </div>
                                    <div class="mb-3">
                                        <label for="fecha" class="form-label fw-bold">Fecha de Actividad</label>
                                        <input v-model="fechaReserva" type="date" class="form-control" id="fecha"
                                            placeholder="Seleccione la fecha">
                                    </div>
                                    <div class="mb-3">
                                        <label for="hora_inicio" class="form-label fw-bold">Hora de Inicio</label>
                                        <input v-model="form.hora_inicio" type="time" class="form-control"
                                            id="hora_inicio">
                                    </div>
                                    <div class="mb-3">
                                        <label for="duracion" class="form-label fw-bold">Duración (Horas)</label>
                                        <select v-model="form.duracion" class="form-select" id="duracion">
                                            <option value="1">1 hora</option>
                                            <option value="2">2 horas</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="descripcion" class="form-label fw-bold">Descripción</label>
                                        <textarea v-model="form.descripcion" class="form-control" id="descripcion"
                                            rows="3" placeholder="Describa la actividad"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary"><i
                                            class="fa fa-save me-2"></i>Guardar</button>
                                </form>
                            </template>
                            <template v-else>
                                <h5 class="card-title">{{ reserva.titulo }}</h5>
                                <p class="mt-4 card-text"><strong>Aula: </strong>{{ reserva.aula.nombre }}</p>
                                <p class="card-text"><strong>Fecha: </strong>{{ reserva.fecha }}</p>
                                <p class="card-text"><strong>Hora Inicio: </strong>{{ reserva.hora_inicio }}</p>
                                <p class="card-text"><strong>Duración: </strong>{{ reserva.duracion }}</p>
                                <p class="card-text"><strong>Descripción:</strong> {{ reserva.descripcion }}</p>
                                <p class="card-text"><strong>Plazas:</strong> {{ reserva.plazas_ocupadas ?? 0 }} /
                                    {{ reserva.aula.plazas }}</p>
                                <button @click="modoEdicion = !modoEdicion" class="btn btn-success"><i
                                        class="fa fa-pen me-2"></i>Editar</button>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-12 mt-lg-0 mt-4">
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
import { ref, onUnmounted, watch } from 'vue';
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

const modoEdicion = ref(false)
const fechaReserva = ref(null)
const horasDisponibles = ref(null)
const form = ref({})

const getInfoReserva = async () => {
    try {
        const { data } = await axios.get(`/api/reservas/${props.id}`)
        reserva.value = data
        if (!modoEdicion.value) {
            form.value.titulo = data.titulo
            form.value.fecha = data.fecha
            fechaReserva.value = data.fecha
            form.value.hora_inicio = data.hora_inicio
            form.value.duracion = data.duracion
            form.value.descripcion = data.descripcion
        }
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
        title: "¿Estás seguro?",
        text: "Esta acción no se puede revertir",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: "No",
        confirmButtonText: "Si"
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

const actualizarInfo = async () => {
    try {
        const { data } = await axios.put(`/api/reservas/${props.id}`, form.value)
    } catch (error) {
        console.error(error);
    }
}

getUsuariosReserva()
getInfoReserva()


watch(fechaReserva, (newValue, oldValue)=>{

})

let interval = setInterval(async () => {
    await getUsuariosReserva()
    await getInfoReserva()
}, 6000)

onUnmounted(() => {
    clearInterval(interval) //Limpiar intervalo para que no se ejecute si no está en la página
})
</script>

<style lang="scss" scoped></style>
