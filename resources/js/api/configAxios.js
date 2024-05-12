import router from '../router'

// Configura un interceptor de respuestas
axios.interceptors.response.use(
    response => response, // Pasa la respuesta si todo está bien
    error => {
        if (error.response && error.response.status === 401) {
            // Maneja errores 401, por ejemplo, redirigiendo al login
            localStorage.removeItem('logged')
            router.push({ name: 'login' });
        }
        // Rechaza la promesa con el error
        return Promise.reject(error);
    }
);

export default axios;
