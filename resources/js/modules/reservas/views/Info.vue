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
                                <form @submit.prevent="updateInfo">
                                    <div class="mb-3">
                                        <label for="titulo" class="form-label fw-bold">Título</label>
                                        <input v-model="form.titulo" type="text" class="form-control" id="titulo"
                                            placeholder="Ingrese el título de la actividad">
                                    </div>
                                    <div class="mb-3">
                                        <label for="fecha" class="form-label fw-bold">Fecha de Actividad</label>
                                        <input v-model="form.fecha" type="date" class="form-control" id="fecha"
                                            placeholder="Seleccione la fecha">
                                    </div>
                                    <div class="mb-3">
                                        <label for="hora" class="form-label fw-bold">Hora de inicio</label>
                                        <select :disabled="loadingHorarios" v-model="form.hora_inicio" id="hora"
                                            class="form-select" aria-label="Default select example">
                                            <option v-for="hora in horasDisponibles"
                                                :selected="hora === form.hora_inicio">{{ hora }}</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="duracion" class="form-label fw-bold">Duración (Horas)</label>
                                        <select v-model="form.duracion" class="form-select" id="duracion">
                                            <option value="1">1 hora</option>
                                            <option v-if="posibilidadDosHoras" value="2">2 horas</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="descripcion" class="form-label fw-bold">Descripción</label>
                                        <textarea v-model="form.descripcion" class="form-control" id="descripcion"
                                            rows="3" placeholder="Describa la actividad"></textarea>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <button type="submit" class="btn btn-primary"><i
                                                class="fa fa-save me-2"></i>Guardar</button>
                                        <button @click="modoEdicion = !modoEdicion" type="submit"
                                            class="btn btn-danger"><i class="fa fa-cancel me-2"></i>Cancelar</button>
                                    </div>
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
                                <div class="d-flex justify-content-between">
                                    <button @click="modoEdicion = !modoEdicion" class="btn btn-success"><i
                                            class="fa fa-pen me-2"></i>Editar</button>
                                    <button @click="deleteReserva" class="btn btn-danger"><i
                                            class="fa fa-trash me-2"></i>Eliminar</button>
                                </div>
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
import { ref, onUnmounted, watch, computed } from 'vue';
import useAuthStore from '../../../stores/authStore'

import LoadingContent from '../../../components/LoadingContent.vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import { useRouter } from 'vue-router';

// props
const props = defineProps({
    id: {
        required: true
    }
})

// propiedades
const authStore = useAuthStore()
const reserva = ref(null)
const reservas_usuarios = ref(null)
const modoEdicion = ref(false)
const horasDisponibles = ref(null)
const loadingHorarios = ref(false)
const form = ref({})
const fechaReserva = ref(null)
const router = useRouter()

// computed
const posibilidadDosHoras = computed(() => {
    if (form.value.hora_inicio && horasDisponibles) {
        let i = horasDisponibles.value.indexOf(form.value.hora_inicio)
        const baseDate = "2023-01-01"; // Usar cualquier fecha como referencia

        const date1 = new Date(`${baseDate} ${horasDisponibles.value[i]}`);
        const date2 = new Date(`${baseDate} ${horasDisponibles.value[i + 1]}`);

        // Calcular la diferencia en milisegundos y convertir a horas
        const diferenciaHoras = (date2 - date1) / (1000 * 60 * 60);
        return diferenciaHoras === 1;

    } else {
        return true
    }
})

// funciones

// obtener los datos de la reserva
const getInfoReserva = async () => {
    try {
        if (!modoEdicion.value) {
            const { data } = await axios.get(`/api/reservas/${props.id}`)
            reserva.value = data
            form.value.titulo = data.titulo
            form.value.fecha = data.fecha
            form.value.aula_id = data.aula_id
            fechaReserva.value = data.fecha
            form.value.hora_inicio = data.hora_inicio
            form.value.duracion = data.duracion
            form.value.descripcion = data.descripcion

        }
    } catch (error) {
        console.error(error);
    }
}

// obtener el listado de usuarios de la reserva
const getUsuariosReserva = async () => {
    try {
        const { data } = await axios.get(`/api/reservas/usuarios/${props.id}`)
        reservas_usuarios.value = data.data
    } catch (error) {
        console.error(error);
    }

}

// obtener los horarios disponibles para una fecha
const getHorariosDisponiblesFecha = async (fecha) => {
    try {
        console.log(fecha);
        loadingHorarios.value = true
        const { data } = await axios.get(`/api/obtenerespaciosdisponibles/${fecha}/${form.value.aula_id}`)
        console.log(data);
        horasDisponibles.value = Object.values(data)
        if (fecha == fechaReserva.value) {
            horasDisponibles.value.push(form.value.hora_inicio)
        }
        horasDisponibles.value.sort()
        loadingHorarios.value = false
    } catch (error) {
        console.error(error);
    }
}

// eliminar un usuario de la reserva

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
                    await getUsuariosReserva()
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

// acutalizar info reserva actividad

const updateInfo = async () => {
    try {
        const { data } = await axios.put(`/api/reservas/${props.id}`, form.value)
        modoEdicion.value = false
        await getInfoReserva()
        Swal.fire({
            title: "Actualización",
            text: "Se ha acutalizado la reserva correctamente",
            icon: "success"
        });
    } catch (error) {
        Swal.fire({
            title: "Actualización",
            text: "Se ha producido un error. Inténtalo de nuevo",
            icon: "error"
        });
    }
}

// borrar la reserva

const deleteReserva = async () => {
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
                const { data } = await axios.delete(`/api/reservas/${props.id}`)
                router.push({ name: 'reservas' }).then(() => {
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Se ha borrado la actividad con éxito",
                        showConfirmButton: false,
                        timer: 2500
                    });
                });
            } catch (error) {
                Swal.fire({
                    title: "Error en el borrado",
                    text: "Se ha producido un error. Inténtalo de nuevo",
                    icon: "error"
                });
            }
        }
    });
}


// watch
watch(() => form.value.fecha, async (newValue) => {
    await getHorariosDisponiblesFecha(newValue)
})


// ejecución de funciones
getUsuariosReserva()
getInfoReserva()


let interval = setInterval(async () => {
    await getUsuariosReserva()
    await getInfoReserva()
}, 6000)

onUnmounted(() => {
    clearInterval(interval) //Limpiar intervalo para que no se ejecute si no está en la página
})
</script>

<style lang="scss" scoped></style>
