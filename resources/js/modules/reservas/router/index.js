import isAuthenticatedGuard from '../../../router/isAuthenticathedGuard';
import canVisualize from '../../../router/canVisualizeGuard';

export default {
    component: () => import('../layouts/ReservaLayout.vue'),
    beforeEnter: [isAuthenticatedGuard],
    children: [
        {
            path: '',
            name: 'reservas',
            component: () => import('../views/Listado.vue')
        },
        {
            path: ':id',
            name: 'reserva.info',
            // beforeEnter: [canVisualize],
            component: () => import('../views/Info.vue'),
            props: (route) => {
                return{
                    id: route.params.id
                }
            }
        }
    ]
}
