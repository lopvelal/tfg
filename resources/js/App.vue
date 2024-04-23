<script setup>

import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'
// initialize components based on data attribute selectors
onMounted(async () => {
    initFlowbite();
})
</script>

<template>
    <main>
        <RouterView />
    </main>
</template>
