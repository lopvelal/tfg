import useAuthStore from "../store/authStore"


const isAuthenticatedGuard =(to, from, next) => {

    const authStore = useAuthStore()

    if(authStore.checkAuthentication().ok){
        authStore.getUser()
        next()
    } else {
        next({name: 'login'})
    }

}

export default isAuthenticatedGuard
