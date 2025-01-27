import { defineStore } from "pinia"
import axios from "@/helpers/axios"
import { setCookie } from "@/helpers/cookies"
import { setToken } from "@/helpers/token"

export const useAuthStore = defineStore("auth", {
    state: () => ({
        user: {},
    }),

    getters: {
        getUser: (state) => state.user,
    },

    actions: {
        async login({ email, password }) {
            const { data } = await axios.post("/api/login", {
                email,
                password,
            })

            if (data.status) {
                setCookie("token", data.token)
                setToken(data.token)
                this.user = data.user
                return true
            }

            return false
        },

        async register({ name, email, password }) {
            const { data } = await axios.post("/api/register", {
                name,
                email,
                password,
            })

            if (data.status) {
                setCookie("token", data.token)
                setToken(data.token)
                this.user = data.user
                return true
            }

            return false
        },

        async me() {
            try {
                const { data } = await axios.get("/api/profile")

                if (data.status) {
                    this.user = data.data
                    return true
                } else {
                    return false
                }
            } catch (error) {
                return false
            }
        },

        async logout() {
            try {
                const result = await axios.post("/api/logout")
                if (result.data.status) {
                    this.user = {}
                    setCookie("token", "", -1)
                    window.location.href = "/admin/login"
                    return true
                }

                return false
            } catch (error) {
                return false
            }
        },

        async getAllUsers() {
            try {
                const { data } = await axios.get("/api/users")

                if (data.status) {
                    return data.users
                }

                return data
            } catch (error) {
                return error
            }
        },

        async updateUser(user) {
            try {
                const { data } = await axios.put(`/api/users/${user.id}`, user)

                if (data.status) {
                    return data.user
                }

                return false
            } catch (error) {
                console.log(error.message)
                return false
            }
        },
    },
})
