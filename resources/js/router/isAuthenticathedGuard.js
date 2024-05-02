import useAuthStore from "../stores/authStore"


const isAuthenticatedGuard = (to, from, next) => {

    const authStore = useAuthStore()

    if (authStore.checkAuthentication().ok) {
        if (!authStore.user) {
            authStore.getUser()
        }
        next()
    } else {
        next({ name: 'login' })
    }

}

export default isAuthenticatedGuard
