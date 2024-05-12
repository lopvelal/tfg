import './bootstrap';

import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap/dist/js/bootstrap";

import { createApp } from 'vue';
import App from './App.vue'
import router from './router'
import { createPinia } from 'pinia';
import './api/configAxios'

const app = createApp(App)
const pinia = createPinia()

app.use(pinia)
app.use(router)
app.mount('#app')
