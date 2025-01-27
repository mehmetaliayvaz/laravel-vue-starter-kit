import { setCookie } from "@/helpers/cookies"
import axios from "@/helpers/axios"

export const setToken = (token) => {
  setCookie("token", token)
  axios.defaults.headers.common["Authorization"] = `Bearer ${token}`
}
