<script setup lang="ts">
import { ChevronLeft, ChevronRight } from 'lucide-vue-next'

const props = defineProps<{
  modelValue: number
  lastPage: number
}>()

const emit = defineEmits<{ 'update:modelValue': [page: number] }>()

function pageNumbers(): Array<number | '...'> {
  const current = props.modelValue
  const last = props.lastPage
  const numbers: Array<number | '...'> = []
  for (let page = 1; page <= last; page++) {
    if (page === 1 || page === last || Math.abs(page - current) <= 1) {
      numbers.push(page)
    } else if (numbers[numbers.length - 1] !== '...') {
      numbers.push('...')
    }
  }
  return numbers
}

function goTo(page: number) {
  if (page < 1 || page > props.lastPage || page === props.modelValue) return
  emit('update:modelValue', page)
}
</script>

<template>
  <nav v-if="lastPage > 1" class="pagination" aria-label="Paginação">
    <button
      type="button"
      class="page-btn"
      :disabled="modelValue <= 1"
      aria-label="Página anterior"
      @click="goTo(modelValue - 1)"
    >
      <ChevronLeft :size="16" />
    </button>

    <template v-for="page in pageNumbers()" :key="page">
      <span v-if="page === '...'" class="ellipsis">…</span>
      <button
        v-else
        type="button"
        class="page-btn"
        :class="{ active: page === modelValue }"
        :aria-current="page === modelValue ? 'page' : undefined"
        @click="goTo(page)"
      >
        {{ page }}
      </button>
    </template>

    <button
      type="button"
      class="page-btn"
      :disabled="modelValue >= lastPage"
      aria-label="Próxima página"
      @click="goTo(modelValue + 1)"
    >
      <ChevronRight :size="16" />
    </button>
  </nav>
</template>

<style scoped>
.pagination {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.25rem;
  margin-top: 1.5rem;
  flex-wrap: wrap;
}

.page-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 36px;
  height: 36px;
  padding: 0 0.5rem;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  background: #ffffff;
  color: #475569;
  font-weight: 500;
  transition:
    background-color 0.15s ease,
    color 0.15s ease,
    border-color 0.15s ease;
}

.page-btn:hover:not(:disabled):not(.active) {
  background: #f1f5f9;
  color: #0f172a;
}

.page-btn:disabled {
  cursor: not-allowed;
  opacity: 0.4;
}

.page-btn.active {
  background: #0f172a;
  border-color: #0f172a;
  color: #f8fafc;
}

.ellipsis {
  min-width: 24px;
  text-align: center;
  color: #94a3b8;
}
</style>
