<template>

    <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalTitle" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalTitle">Cambie la contraseña</h1>
                    <button id="cerrar-modal" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Introduzca su contraseña actual y la nueva contraseña.</strong></p>
                    <form @submit.prevent="changePassword">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Contraseña Actual</label>
                            <input v-model="form.current_password" type="password" placeholder="·····"
                                class="form-control" id="current_password" aria-describedby="emailHelp">
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Nueva contraseña</label>
                            <input v-model="form.new_password" placeholder="·····" type="password" class="form-control"
                                id="new_password">
                        </div>
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Repita nueva contraseña</label>
                            <input v-model="form.new_password_confirmation" placeholder="·····" type="password"
                                class="form-control" id="new_password_confirmation">
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios'
import Swal from 'sweetalert2'

const form = ref({})
const changePassword = async () => {
    try {
        if (new_password.value === new_password_confirmation.value) {
            await axios.put('/api/user/changePassword', form.value)
            document.getElementById('cerrar-modal').click()
            Swal.fire({
                title: "Actualización contraseña",
                text: "La contraseña se ha actualizado correctamente.",
                icon: "success",
                confirmButtonText: 'Confirmar',
                confirmButtonColor: '#0d6efd',
            });
        } else {
            console.error('Las contraseñas no coinciden');
        }
    } catch (error) {
        console.error(error);
    }
}
</script>

<style lang="scss" scoped></style>
