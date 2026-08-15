<script setup lang="ts">
import { LoaderCircle, MapPin, Search } from 'lucide-vue-next'
import { onBeforeUnmount, onMounted, ref, watch } from 'vue'

import { weatherService } from '@/services/weatherService'
import type { Place } from '@/types/weather'

const props = withDefaults(
  defineProps<{
    initialQuery?: string
    autoSelectFirst?: boolean
    placeholder?: string
  }>(),
  {
    initialQuery: '',
    autoSelectFirst: false,
    placeholder: 'Buscar cidade...',
  },
)

const emit = defineEmits<{ select: [place: Place] }>()

const query = ref(props.initialQuery)
const results = ref<Place[]>([])
const open = ref(false)
const loading = ref(false)
const activeIndex = ref(-1)

let debounceTimer: ReturnType<typeof setTimeout> | undefined
let searchCount = 0
let suppressWatch = false
let autoSelected = false

function formatPlace(place: Place): string {
  return [place.name, place.state, place.country].filter(Boolean).join(', ')
}

async function search() {
  const q = query.value.trim()
  if (q.length < 2) {
    results.value = []
    open.value = false
    return
  }
  loading.value = true
  const requestId = ++searchCount
  try {
    const places = await weatherService.searchPlaces(q, 5)
    if (requestId !== searchCount) return
    results.value = places
    if (props.autoSelectFirst && !autoSelected && places.length > 0) {
      autoSelected = true
      select(places[0])
      return
    }
    open.value = true
    activeIndex.value = -1
  } catch (cause) {
    if (requestId !== searchCount) return
    void cause
    results.value = []
    open.value = false
  } finally {
    if (requestId === searchCount) loading.value = false
  }
}

watch(query, () => {
  if (suppressWatch) {
    suppressWatch = false
    return
  }
  if (debounceTimer) clearTimeout(debounceTimer)
  debounceTimer = setTimeout(search, 350)
})

function select(place: Place) {
  if (debounceTimer) clearTimeout(debounceTimer)
  suppressWatch = true
  query.value = formatPlace(place)
  results.value = [place]
  open.value = false
  emit('select', place)
}

function onKeydown(event: KeyboardEvent) {
  if (!open.value || results.value.length === 0) {
    if (event.key === 'ArrowDown' && results.value.length > 0) {
      open.value = true
      activeIndex.value = 0
    }
    return
  }
  if (event.key === 'ArrowDown') {
    event.preventDefault()
    activeIndex.value = (activeIndex.value + 1) % results.value.length
  } else if (event.key === 'ArrowUp') {
    event.preventDefault()
    activeIndex.value = activeIndex.value <= 0 ? results.value.length - 1 : activeIndex.value - 1
  } else if (event.key === 'Enter') {
    event.preventDefault()
    if (activeIndex.value >= 0 && results.value[activeIndex.value]) {
      select(results.value[activeIndex.value])
    }
  } else if (event.key === 'Escape') {
    open.value = false
  }
}

function onFocus() {
  if (results.value.length > 0) open.value = true
}

function closeOnBlur() {
  setTimeout(() => {
    open.value = false
  }, 150)
}

onMounted(() => {
  if (props.initialQuery) void search()
})

onBeforeUnmount(() => {
  if (debounceTimer) clearTimeout(debounceTimer)
})
</script>

<template>
  <div class="autocomplete">
    <div class="input-wrap">
      <Search :size="16" class="prefix" />
      <input
        v-model="query"
        type="search"
        :placeholder="placeholder"
        aria-label="Buscar cidade"
        role="combobox"
        aria-expanded="open"
        autocomplete="off"
        @keydown="onKeydown"
        @focus="onFocus"
        @blur="closeOnBlur"
      />
      <LoaderCircle v-if="loading" :size="16" class="spinner" />
    </div>

    <ul v-if="open && results.length" class="dropdown" role="listbox">
      <li
        v-for="(place, index) in results"
        :key="`${place.name}-${place.lat}-${place.lon}`"
        role="option"
        :class="{ active: index === activeIndex }"
        @mousedown.prevent
        @click="select(place)"
      >
        <MapPin :size="16" class="pin" />
        <span class="place-label">
          {{ place.name }}{{ place.state ? `, ${place.state}` : '' }}
          <small>{{ place.country }}</small>
        </span>
      </li>
    </ul>
  </div>
</template>

<style scoped>
.autocomplete {
  position: relative;
  max-width: 480px;
}

.input-wrap {
  position: relative;
  display: flex;
  align-items: center;
}

.prefix {
  position: absolute;
  left: 0.75rem;
  color: #94a3b8;
  pointer-events: none;
}

.spinner {
  position: absolute;
  right: 0.75rem;
  color: #94a3b8;
  animation: spin 0.8s linear infinite;
}

.input-wrap input {
  width: 100%;
  padding: 0.6rem 2.5rem 0.6rem 2.25rem;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  outline: none;
}

.input-wrap input:focus {
  border-color: #2563eb;
  box-shadow: 0 0 0 3px rgb(37 99 235 / 0.15);
}

.dropdown {
  position: absolute;
  top: calc(100% + 4px);
  left: 0;
  right: 0;
  z-index: 20;
  margin: 0;
  padding: 0.25rem;
  list-style: none;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  box-shadow: 0 10px 25px rgb(15 23 42 / 0.12);
}

.dropdown li {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 0.6rem;
  border-radius: 6px;
  cursor: pointer;
}

.dropdown li.active,
.dropdown li:hover {
  background: #eff6ff;
}

.pin {
  flex-shrink: 0;
  color: #2563eb;
}

.place-label {
  font-size: 0.9rem;
  color: #0f172a;
}

.place-label small {
  display: block;
  color: #64748b;
  font-size: 0.75rem;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
</style>
