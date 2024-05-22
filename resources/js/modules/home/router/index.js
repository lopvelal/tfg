import isAuthenticatedGuard from '../../../router/isAuthenticathedGuard';

export default  {
    component: () => import('../layouts/HomeLayout.vue'),
    beforeEnter: [isAuthenticatedGuard],
    children: [
        {
            path: '',
            name: 'home',
            component: () => import('../views/Home.vue')
        }
    ]
}
