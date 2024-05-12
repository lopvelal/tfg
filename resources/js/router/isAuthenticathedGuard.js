import useAuthStore from "../stores/authStore"


const isAuthenticatedGuard = async(to, from, next) => {

    const authStore = useAuthStore()
    const authResult = authStore.checkAuthentication()
    if (authResult.ok) {
        if (!authStore.user) {
            await authStore.getUser()
        }
        next()
    } else {
        next({ name: 'login' })
    }

}

export default isAuthenticatedGuard
