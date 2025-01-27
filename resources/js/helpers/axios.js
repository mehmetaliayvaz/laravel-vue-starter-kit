import axios from "axios"
import { getCookie } from "@/helpers/cookies"

const token = getCookie("token")

if (token) {
    axios.defaults.headers.common["Authorization"] = `Bearer ${token}`
}

export default axios
