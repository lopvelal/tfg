<script setup>
import { onMounted, ref, watch, defineAsyncComponent, onUnmounted } from 'vue';
import useAuthStore from '../stores/authStore';

const authStore = new useAuthStore();

const Loading = defineAsyncComponent(() => import('../components/LoadingContent.vue'))

const form = ref({
    email: '',
    password: '',
})

const loading = ref(false)

watch(() => authStore.status, (newValue) => {
    if (newValue == 'authenticating') {
        loading.value = true
    }
})

onUnmounted(()=> {
    loading.value = false
})

</script>
<template>
    <section class="bg-light">
        <div class="d-flex flex-column align-items-center justify-content-center px-2 py-3 vh-100">
            <div>
                <router-link :to="{ name: 'home' }">
                    <img style="height: 220px; margin-left: 35px;" src="../assets/img/ETSI SIST_INFORM_COLOR.png"
                        alt="logo">
                </router-link>
            </div>
            <div class="bg-white rounded shadow w-100 p-0" style="max-width: 32rem;">
                <div class="p-3">
                    <h1 class="fs-2 fw-bold">
                        Entra en la aplicación
                    </h1>
                    <form @submit.prevent="authStore.handleLogin(form)" class="my-4">
                        <div class="mb-3">
                            <label for="email" class="form-label">Tu email</label>
                            <input type="email" v-model="form.email" name="email" id="email" class="form-control"
                                placeholder="name@email.com">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" v-model="form.password" name="password" id="password"
                                class="form-control" placeholder="••••••••">
                        </div>
                        <div>
                            <span v-if="authStore.errores" class="fw-semibold text-danger">Usuario o contraseña
                                incorrectos</span>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Sign in</button>
                        <Loading v-if="loading && !authStore.errores" />
                    </form>
                </div>
            </div>
        </div>
    </section>

</template>
