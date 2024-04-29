const isNoAuthenticatedGuard =async (to, from, next) => {

    if(!localStorage.getItem('logged')){
        next()
    } else {
        next({name: 'home'})
    }

}

export default isNoAuthenticatedGuard
