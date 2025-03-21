import { createRouter, createWebHistory } from "vue-router";
import userRouter from '../modules/user/router'
import aulasRouter from '../modules/aulas/router'
import reservasRouter from '../modules/reservas/router'
import homeRouter from '../modules/home/router'

import isAuthenticatedGuard from "./isAuthenticathedGuard";
import isNoAuthenticatedGuard from "./isNoAuthenticathedGuard";

const routes =[
    {
        path: '/',
        ...homeRouter,
    },
    {
        path: '/user',
        ...userRouter,
    },
    {
        path: '/aulas',
        ...aulasRouter,
    },
    {
        path: '/actividades',
        ...reservasRouter,
    },
    {
        path:'/login',
        name: 'login',
        beforeEnter: [isNoAuthenticatedGuard],
        component: () => import('../views/Login.vue')
    },
    {
        path: '/:catchAll(.*)*',
        name: '404',
        beforeEnter: [isAuthenticatedGuard],
        component: () => import('../views/404.vue')
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router
