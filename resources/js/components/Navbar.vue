<template>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <RouterLink to="/" class="navbar-brands">
                <img style="" src="../assets/img/ETSI SIST_INFORM_COLOR.png" alt="">
            </RouterLink>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav ms-auto">
                    <RouterLink :to="{name: 'home'}" class="nav-link" active-class="active">
                        Inicio
                    </RouterLink>
                    <RouterLink :to="{name: 'user'}" class="nav-link" active-class="active">
                        Aulas
                    </RouterLink>
                    <RouterLink :to="{name: 'user'}" class="nav-link" active-class="active">
                        Reservas
                    </RouterLink>
                    <RouterLink :to="{name: 'user'}" class="nav-link" active-class="active">
                        Cuenta
                    </RouterLink>
                </div>
                <div class="navbar-nav ms-auto">
                    <button @click="authStore.handleLogout()" class="btn btn-danger px-5">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        Salir
                    </button>
                </div>
            </div>
        </div>
    </nav>
</template>
<script setup>
import { RouterLink } from 'vue-router';
import useAuthStore from '../stores/authStore';

const authStore = useAuthStore()

</script>
<style lang="css" scoped>
img {
    height: 100px;
}
.nav-link{
    font-size: 1.4em;
    margin-left: 10px;
    margin-right: 10px;
}
.nav-link:hover{
    color: #1a81be;
    text-decoration: underline;
}
</style>
