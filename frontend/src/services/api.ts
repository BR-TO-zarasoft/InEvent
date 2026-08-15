import axios from 'axios'

import { showToast } from '@/lib/toast'

export const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL ?? '/api',
  headers: {
    Accept: 'application/json',
  },
})

api.interceptors.response.use(
  (response) => response,
  (error) => {
    const message = error.response?.data?.message ?? error.message ?? 'Erro inesperado'
    showToast(message)
    return Promise.reject(new Error(message))
  },
)
