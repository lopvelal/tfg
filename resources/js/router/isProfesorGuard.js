import useAuthStore from "../stores/authStore"


const isProfesor = async(to, from, next) => {
    const authStore = useAuthStore()
    let roles = authStore.permisos.roles
    if (roles.includes('profesor')) {
        next()
    } else {
        next({ name: 'home' })
    }

}

export default isProfesor
