<template>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Listado de aulas</h1>
        <template v-if="aulas">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Plazas</th>
                            <th scope="col">Disponible</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="aula in aulas" :key="aula.id">
                            <td class="fw-bold" scope="row">{{ aula.id }}</td>
                            <td>{{ aula.nombre }}</td>
                            <td>{{ aula.plazas }}</td>
                            <td><i class="fa-lg"
                                    :class="{ 'fa-solid fa-circle-check': aula.disponible, 'fa-solid fa-circle-xmark': !aula.disponible }"
                                    :style="aula.disponible ? 'color: #189A3F' : 'color: #BE1313'"></i></td>
                            <td>
                                <RouterLink :to="{ name: 'aula.info', params: { id: aula.id } }" class="fw-bold">+ Mas info</RouterLink>
                            </td>
                        </tr>
                    </tbody>

                </table>
            </div>
            <div class="mt-4 mb-4 d-flex flex-column align-items-center">
                <!-- Help text -->
                <span class="text-sm text-gray-700">
                    Mostrando del
                    <span class="fw-bold text-gray-900">{{ pagination.from }}</span> al
                    <span class="fw-bold text-gray-900">{{ pagination.to }}</span> de entre <span
                        class="fw-bold text-gray-900">{{ pagination.total }}</span> registros
                </span>
                <!-- Buttons -->
                <div class="mt-4">
                    <button @click="getAulas(pagination.prev_page_url)" :disabled="!pagination.prev_page_url"
                        class="me-2 btn btn-primary"
                        :class="{ 'disabled': !pagination.prev_page_url }">Anterior</button>
                    <button @click="getAulas(pagination.next_page_url)" :disabled="!pagination.next_page_url"
                        class="btn btn-primary" :class="{ 'disabled': !pagination.next_page_url }">Siguiente</button>
                </div>
            </div>
        </template>
        <template v-else>
            <LoadingContent />
        </template>
    </div>
</template>

<script setup>
import { ref, defineAsyncComponent } from 'vue';

const LoadingContent = defineAsyncComponent(() => import('../../../components/LoadingContent.vue'))

const aulas = ref(null); // Inicializa aulas como un array vacío
const pagination = ref({});

const getAulas = async (url = '/api/aulas') => {
    try {
        const alumnos = await axios.get(url);
        aulas.value = alumnos.data.data;
        pagination.value = {
            current_page: alumnos.data.current_page,
            last_page: alumnos.data.last_page,
            per_page: alumnos.data.per_page,
            next_page_url: alumnos.data.next_page_url,
            prev_page_url: alumnos.data.prev_page_url,
            total: alumnos.data.total,
            from: alumnos.data.from,
            to: alumnos.data.to,
        };
    } catch (error) {
        console.error("Error al cargar los alumnos:", error);
    }
};

getAulas()

</script>

<style lang="scss" scoped></style>
