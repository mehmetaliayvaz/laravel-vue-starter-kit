import { createApp } from "vue"

import App from "./App.vue"
import router from "./router"
import store from "./store"

import PrimeVue from "primevue/config"
import Aura from "@primevue/themes/aura"
import { definePreset } from "@primevue/themes"

const MyPreset = definePreset(Aura, {
    semantic: {
        primary: {
            50: "{red.50}",
            100: "{red.100}",
            200: "{red.200}",
            300: "{red.300}",
            400: "{red.400}",
            500: "{red.500}",
            600: "{red.600}",
            700: "{red.700}",
            800: "{red.800}",
            900: "{red.900}",
            950: "{red.950}",
        },
    },
})

const app = createApp(App)

app.use(router)
app.use(store)
app.use(PrimeVue, {
    theme: {
        preset: MyPreset,
    },
})

app.mount("#app")
