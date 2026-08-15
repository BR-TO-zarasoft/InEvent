import { reactive } from 'vue'

export interface Toast {
  id: number
  type: 'error' | 'success' | 'info'
  message: string
}

export const toasts = reactive<Toast[]>([])

let nextId = 0

const TOAST_DURATION = 5000

export function dismissToast(id: number) {
  const index = toasts.findIndex((toast) => toast.id === id)
  if (index !== -1) toasts.splice(index, 1)
}

export function showToast(message: string, type: Toast['type'] = 'error'): number {
  const toast: Toast = { id: ++nextId, type, message }
  toasts.push(toast)
  window.setTimeout(() => dismissToast(toast.id), TOAST_DURATION)
  return toast.id
}