import useAuthStore from "../stores/authStore"


const canVisualize = async(to, from, next) => {
    const authStore = useAuthStore()
    let roles = authStore.permisos.roles
    if (roles.includes('profesor') || roles.includes('admin')) {
        next()
    } else {
        next({ name: 'home' })
    }

}

export default canVisualize
