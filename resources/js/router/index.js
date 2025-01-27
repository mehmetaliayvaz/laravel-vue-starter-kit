import { createRouter, createWebHistory } from "vue-router"
import { getCookie } from "@/helpers/cookies"

import DashboardPage from "@/pages/DashboardPage.vue"
import LoginPage from "@/pages/LoginPage.vue"

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: "/admin",
            name: "home",
            component: DashboardPage,
        },
        {
            path: "/admin/login",
            name: "login",
            component: LoginPage,
        },
    ],
})

router.beforeEach((to, from, next) => {
    const token = getCookie("token")
    if (to.name !== "login" && !token) {
        next({ name: "login" })
    } else if (to.name === "login" && token) {
        next({ name: "home" })
    } else {
        next()
    }
})

export default router
