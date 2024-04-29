import { defineStore } from "pinia";
import { computed, ref } from "vue";
import router from '../router'


const useAuthStore = defineStore('auth', () => {

    const status = ref('no-authenticated')
    const user = ref(null)
    const permisos = ref(null)
    const errores = ref(null)


    //getters

    const getStatus = computed(() => status.value)


    const getToken = async () => {
        await axios.get('/sanctum/csrf-cookie')
    }

    const checkAuthentication = () => {
        const logged = localStorage.getItem('logged')

        if (!logged) {
            handleLogout()
            return { ok: false, message: 'Usuario no logueado' }
        } else {
            getUser()
            return { ok: true }
        }
    }

    const getUser = async () => {
        getToken()
        try {
            const dataUser = await axios.get('/api/user')
            const dataPermisos = await axios.get('/api/user/permissions')
            user.value = dataUser.data
            permisos.value = dataPermisos.data
            status.value = 'authenticated'
        } catch (error) {
            localStorage.removeItem('logged')
            router.push({name: 'login'})
        }
    }

    const handleLogout = async () => {
        localStorage.removeItem('logged');
        await axios.post('/logout');
        user.value = null;
        permisos.value = null
        status.value = 'no-authenticated'
        router.push({name: 'login'});
    }

    const handleLogin = async (data) => {
        console.log('Haciendo el login')
        status.value = 'authenticating'
        getToken()
        try {
            await axios.post('/login', {
                email: data.email,
                password: data.password
            })
            localStorage.setItem('logged', true)
            router.push('/')
        } catch (error) {
            if (error.response.status === 422) {
                errores.value = error.response.data.errors;
            } else {
                console.log(error);
            }
        }
    }

    return {
        // state
        status,
        user,
        errores,
        permisos,

        // actions
        getToken,
        checkAuthentication,
        handleLogin,
        handleLogout,
        getUser,
        getStatus,
    }

})


export default useAuthStore
