import { ref, onUnmounted, watch, computed } from 'vue';

import Swal from 'sweetalert2';
import { useRouter } from 'vue-router';


export default function gestionReserva(id) {

    // propiedades
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

    /* funciones */

    // obtener el listado de usuarios de la reserva
    const getUsuariosReserva = async () => {
        try {
            const { data } = await axios.get(`/api/reservas/usuarios/${id}`)
            reservas_usuarios.value = data.data
        } catch (error) {
            console.error(error);
        }

    }

    // obtener los horarios disponibles para una fecha
    const getHorariosDisponiblesFecha = async () => {
        try {
            let fecha = form.value.fecha
            loadingHorarios.value = true
            const { data } = await axios.get(`/api/obtener-espacios-disponibles/${form.value.fecha}/${form.value.aula_id}`)
            horasDisponibles.value = Object.values(data)
            if (fecha == fechaReserva.value) {
                horasDisponibles.value.push(form.value.hora_inicio)

                if (form.value.duracion == 2) {
                    let horaString = form.value.hora_inicio
                    let fecha = new Date(`1970-01-01T${horaString}Z`)
                    fecha.setHours(fecha.getHours() + 1)
                    let horaSiguiente = fecha.toISOString().split('T')[1].substring(0, 8)
                    horasDisponibles.value.push(horaSiguiente)
                }
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
            const { data } = await axios.put(`/api/reservas/${id}`, form.value)
            modoEdicion.value = false
            await getInfoReserva()
            Swal.fire({
                title: "Actualización",
                text: "Se ha acutalizado la reserva correctamente",
                icon: "success"
            });
        } catch (error) {
            console.log(error);
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
                    const { data } = await axios.delete(`/api/reservas/${id}`)
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
    watch(() => form.value.fecha, async () => {
        await getHorariosDisponiblesFecha()
    })

    watch(modoEdicion, async (newValue) => {
        if (newValue) {
            await getHorariosDisponiblesFecha()
        }
    })

    // ejecución de funciones
    getUsuariosReserva()


    let intervalGetUsuariosReserva = setInterval(async () => {
        await getUsuariosReserva()
    }, 6000)

    return {
        reservas_usuarios,
        modoEdicion,
        horasDisponibles,
        loadingHorarios,
        form,
        fechaReserva,
        posibilidadDosHoras,
        eliminarUsuarioReserva,
        updateInfo,
        deleteReserva,
        intervalGetUsuariosReserva,
    }


}
