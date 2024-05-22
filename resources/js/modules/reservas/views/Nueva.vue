<template>
    <div class="container">
        <h1 class="text-center">Nueva actividad</h1>
        <form @submit.prevent="storeElement">
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
                <label for="aula" class="form-label fw-bold">Aula</label>
                <select v-model="form.aula_id" id="aula" class="form-select">
                    <option v-for="aula in aulas" :value="aula.id">{{ aula.nombre }}</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="hora" class="form-label fw-bold">Hora de inicio</label>
                <select :disabled="loadingHorarios" v-model="form.hora_inicio" id="hora" class="form-select">
                    <option v-for="hora in horasDisponibles">{{ hora }}</option>
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
                <textarea v-model="form.descripcion" class="form-control" id="descripcion" rows="3"
                    placeholder="Describa la actividad"></textarea>
            </div>
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save me-2"></i>Guardar</button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { computed, ref, watch } from 'vue';
import useAuthStore from '../../../stores/authStore';
import { useRouter } from 'vue-router';
import Swal from 'sweetalert2';

const router = useRouter()

const authStore = useAuthStore()


const form = ref({
    titulo: '',
    fecha: '',
    hora_inicio: '',
    duracion: '',
    descripcion: '',
    aula_id: '',
    user_id: authStore.user.id,
})

const horasDisponibles = ref(null)
const loadingHorarios = ref(true)
const aulas = ref(null)

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

// obtener los horarios disponibles para una fecha
const getHorariosDisponiblesFecha = async () => {
    if (form.value.fecha && form.value.aula_id) {
        try {
            loadingHorarios.value = true
            const { data } = await axios.get(`/api/obtener-espacios-disponibles/${form.value.fecha}/${form.value.aula_id}`)
            console.log(data);
            horasDisponibles.value = Object.values(data)
            horasDisponibles.value.sort()
            loadingHorarios.value = false
        } catch (error) {
            console.error(error);
        }
    } else {
        console.log('Falta uno de los datos');
    }

}

const getAulas = async () => {
    try {
        const { data } = await axios.get('/api/aulas/disponibles')
        aulas.value = data
        console.log(data);
    } catch (error) {
        console.log(error);
    }
}

const storeElement = async () => {
    try {
        const { data } = await axios.post('/api/reservas', form.value)
        console.log(data);
        router.push({ name: 'actividad.info', params: { id: data.id } }).then(() => {
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Se ha añadido la actividad con éxito",
                showConfirmButton: false,
                timer: 2500
            })
        })
    } catch (error) {
        Swal.fire({
            title: "Actividad nueva",
            text: error.response.data.mensaje || "Ha ocurrido un error al intentar añadir la actividad",
            icon: "error"
        });
    }
}

watch(() => form.value.fecha, async () => {
    await getHorariosDisponiblesFecha()
})

watch(() => form.value.aula_id, async () => {
    await getHorariosDisponiblesFecha()
})

getAulas()
</script>

<style lang="scss" scoped></style>
