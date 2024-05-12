import useAuthStore from "../../../stores/authStore"

const canCreate = async(to, from, next) => {
    const authStore = useAuthStore()
    let roles = authStore.permisos.roles
    if (!roles.includes('alumno')) {
        next()
    } else {
        next({ name: 'actividades' })
    }

}

export default canCreate
