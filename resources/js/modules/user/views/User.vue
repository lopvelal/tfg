<template>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        Datos de usuario
                    </div>
                    <div class="card-body">
                        <h5 class="text-center card-title text-capitalize">{{ authStore.user.name }}</h5>
                        <p class="text-center mt-3"><strong>{{ authStore.user.email }}</strong></p>
                        <div class="d-flex justify-content-center">
                            <button type="button" class="text-center btn btn-primary mt-2" data-bs-toggle="modal"
                                data-bs-target="#modal">
                                Cambiar contraseña
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <PassModal />
</template>

<script setup>
import useAuthStore from '../../../stores/authStore';
import { defineAsyncComponent } from 'vue';

const PassModal = defineAsyncComponent(() => import('../components/PassModal.vue'))

const authStore = useAuthStore()




</script>

<style lang="scss" scoped></style>
