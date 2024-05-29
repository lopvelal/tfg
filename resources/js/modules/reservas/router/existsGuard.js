const exists = async(to, from, next) => {
    try {
        console.log(to.params.id);
        const data = await axios.get(`/api/reservas/${to.params.id}`)
        next()
    } catch (error) {
        if (error.response.status === 404) {
            next({ name: '404' })
        } else {
            next({ name: 'reservas.index' })
        }
     }


}

export default exists
