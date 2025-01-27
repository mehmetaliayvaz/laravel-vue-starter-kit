<template>
    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-white shadow-md border border-gray-200 rounded-lg max-w-sm p-4 sm:p-6 lg:p-8 w-[350px]">
            <form class="grid gap-4" @submit.prevent="login()">
                <div class="flex justify-center mb-5">
                    <h1 class="text-2xl italic font-bold">Login</h1>
                </div>
                <div>
                    <label for="email" class="text-sm font-medium text-gray-900 block mb-1">Email</label>
                    <InputText type="email" class="w-full" size="small" v-model="userData.email" />
                </div>
                <div>
                    <label for="password" class="text-sm font-medium text-gray-900 block mb-1">Password</label>
                    <InputText type="password" size="small" class="w-full" v-model="userData.password" />
                </div>
                <button
                    type="submit"
                    class="w-full bg-black/90 text-white font-medium rounded-lg text-sm px-5 py-2.5 text-center mt-5"
                >
                    Sign In
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue"
import { useRouter } from "vue-router"
import InputText from "primevue/inputtext"
import Checkbox from "primevue/checkbox"
import { setCookie } from "@/helpers/cookies"
import { useAuthStore } from "@/store/AuthStore"

const authStore = useAuthStore()

const router = useRouter()
const userData = ref({
    email: "",
    password: "",
})

const login = async () => {
    const result = await authStore.login({ email: userData.value.email, password: userData.value.password })

    if (result) {
        router.push({ name: "home" })
    }
}
</script>

<style></style>
