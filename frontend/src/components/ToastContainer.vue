<script setup lang="ts">
import { X } from 'lucide-vue-next'

import { dismissToast, toasts } from '@/lib/toast'
</script>

<template>
  <div class="toast-container" aria-live="polite">
    <TransitionGroup name="toast">
      <div v-for="toast in toasts" :key="toast.id" class="toast" :class="toast.type">
        <span class="toast-message">{{ toast.message }}</span>
        <button type="button" class="toast-close" aria-label="Fechar notificação" @click="dismissToast(toast.id)">
          <X :size="14" />
        </button>
      </div>
    </TransitionGroup>
  </div>
</template>

<style scoped>
.toast-container {
  position: fixed;
  top: 1rem;
  right: 1rem;
  z-index: 100;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  max-width: 360px;
}

.toast {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 0.75rem;
  padding: 0.75rem 1rem;
  border-radius: 10px;
  box-shadow: 0 10px 25px rgb(15 23 42 / 0.15);
  font-size: 0.9rem;
  color: #ffffff;
}

.toast.error {
  background: #b91c1c;
}

.toast.success {
  background: #15803d;
}

.toast.info {
  background: #1d4ed8;
}

.toast-message {
  line-height: 1.4;
}

.toast-close {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  width: 22px;
  height: 22px;
  padding: 0;
  border: none;
  border-radius: 999px;
  background: rgb(255 255 255 / 0.2);
  color: #ffffff;
  cursor: pointer;
}

.toast-close:hover {
  background: rgb(255 255 255 / 0.35);
}

.toast-enter-active,
.toast-leave-active {
  transition:
    opacity 0.2s ease,
    transform 0.2s ease;
}

.toast-enter-from,
.toast-leave-to {
  opacity: 0;
  transform: translateX(16px);
}
</style>