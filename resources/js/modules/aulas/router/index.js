
import isAuthenticatedGuard from '../../../router/isAuthenticathedGuard';


export default {
    component: () => import('../layouts/AulaLayout.vue'),
    beforeEnter: [isAuthenticatedGuard],
    children: [
        {
            path: '',
            name: 'aulas',
            component: () => import('../views/Listado.vue')
        },
        {
            path: ':id',
            name: 'aula.info',
            component: () => import('../views/InfoAula.vue'),
            props: (route) => {
                return{
                    id: route.params.id
                }
            }
        }
    ]
}
