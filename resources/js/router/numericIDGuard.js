const numericID = (to, from, next) => {
    const { id } = to.params;
    if (/^\d+$/.test(id)) {
        next(); // Continúa con la ruta si id es un número
    } else {
        next({ name: '404' });
    }

}

export default numericID
