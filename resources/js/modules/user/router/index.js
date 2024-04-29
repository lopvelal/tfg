import isAuthenticatedGuard from '../../../router/isAuthenticathedGuard';


export default {
    component: () => import('../layouts/UserLayout.vue'),
    beforeEnter: [isAuthenticatedGuard],
    children: [
        {
            path: '',
            name: 'user',
            component: () => import('../views/User.vue')
        }
    ]
}
