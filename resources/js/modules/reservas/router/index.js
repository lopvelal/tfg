import isAuthenticatedGuard from '../../../router/isAuthenticathedGuard';
import numericIDGuard from '../../../router/numericIDGuard';
import canCreate from './canCreateGuard';

export default {
    component: () => import('../layouts/ReservaLayout.vue'),
    beforeEnter: [isAuthenticatedGuard],
    children: [
        {
            path: '',
            name: 'actividades',
            component: () => import('../views/Listado.vue')
        },
        {
            path: 'nueva',
            name: 'actividad.nueva',
            beforeEnter: [canCreate],
            component: () => import('../views/Nueva.vue')
        },
        {
            path: ':id',
            name: 'actividad.info',
            beforeEnter: [numericIDGuard],
            component: () => import('../views/Info.vue'),
            props: (route) => {
                return{
                    id: route.params.id
                }
            }
        }
    ]
}
