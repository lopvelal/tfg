<script setup>
import { onMounted, ref } from 'vue';
import useAuthStore from '../store/authStore';

const authStore = new useAuthStore();

const form = ref({
    email: '',
    password: '',
})

</script>
<template>
    <section class="bg-gray-50">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <router-link :to="{name: 'home'}" class="mb-4 ml-8 md:w-1/4 sm:w-full text-center">
                <img class="" src="../assets/img/ETSI SIST_INFORM_COLOR.png" alt="logo">
            </router-link>
            <div class="w-full bg-white rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                        Entra en la aplicación
                    </h1>
                    <form @submit.prevent="authStore.handleLogin(form)" class="space-y-4 md:space-y-6" action="#">
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Tu email</label>
                            <input type="email" v-model="form.email" name="email" id="email"
                                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                placeholder="name@email.com" >
                        </div>
                        <div>
                            <label for="password"
                                class="block mb-2 text-sm font-medium text-gray-900">Contraseña</label>
                            <input type="password" v-model="form.password" name="password" id="password"
                                placeholder="••••••••"
                                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                >
                        </div>
                        <div>
                            <span v-if="authStore.errores" class="font-semibold text-red-700">Usuario o contraseña incorrectos</span>
                        </div>
                        <button type="submit"
                            class="w-full text-white bg-upm-default hover:bg-white hover:text-upm-default hover:outline-upm-default hover:outline hover:outline-solid focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Sign
                            in</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</template>
