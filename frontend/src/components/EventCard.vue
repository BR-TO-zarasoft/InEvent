<script setup lang="ts">
import { CloudSun, Pencil, Trash2 } from 'lucide-vue-next'
import { computed, ref } from 'vue'

import { weatherService } from '@/services/weatherService'
import type { EventItem } from '@/types/event'
import type { DailyForecast } from '@/types/weather'

const props = withDefaults(
  defineProps<{
    event: EventItem
    actions?: boolean
  }>(),
  {
    actions: true,
  },
)

const emit = defineEmits<{ edit: []; delete: [] }>()

const statusLabels: Record<EventItem['status'], string> = {
  planned: 'Planejado',
  confirmed: 'Confirmado',
  cancelled: 'Cancelado',
  finished: 'Encerrado',
}

const confirmOpen = ref(false)
const weatherFetched = ref(false)
const weatherLoading = ref(false)
const startWeather = ref<DailyForecast | null>(null)

const isFinished = computed(
  () => props.event.status === 'finished' || new Date(props.event.end_date).getTime() < Date.now(),
)

const hasPastEnd = computed(() => new Date(props.event.end_date).getTime() < Date.now())

const showActions = computed(() => props.actions && (!isFinished.value || !hasPastEnd.value))

function formatDate(value: string): string {
  return new Date(value).toLocaleDateString('pt-BR', {
    day: '2-digit',
    month: 'long',
    year: 'numeric',
  })
}

function iconUrl(icon: string): string {
  return `https://openweathermap.org/img/wn/${icon}@2x.png`
}

function sameDay(dt: number, date: string): boolean {
  const day = new Date(dt * 1000)
  const target = new Date(date)
  return (
    day.getFullYear() === target.getFullYear() &&
    day.getMonth() === target.getMonth() &&
    day.getDate() === target.getDate()
  )
}

async function fetchStartWeather() {
  weatherFetched.value = true
  weatherLoading.value = true
  startWeather.value = null
  try {
    const response = await weatherService.getForecast(props.event.lat, props.event.lon, 10)
    startWeather.value = response.data.find((day) => sameDay(day.dt, props.event.start_date)) ?? null
  } catch (cause) {
    void cause
    startWeather.value = null
  } finally {
    weatherLoading.value = false
  }
}

function openConfirm() {
  confirmOpen.value = true
}

function cancelDelete() {
  confirmOpen.value = false
}

function confirmDelete() {
  confirmOpen.value = false
  emit('delete')
}
</script>

<template>
  <article class="card event-card">
    <div class="event-head">
      <h3>{{ event.name }}</h3>
      <span class="badge" :class="event.status">{{ statusLabels[event.status] }}</span>
    </div>
    <p class="location">{{ event.location }}</p>
    <p class="dates">
      {{ formatDate(event.start_date) }} → {{ formatDate(event.end_date) }}
    </p>
    <p v-if="event.description" class="description">{{ event.description }}</p>
    <p class="coords">Lat {{ event.lat }} · Lon {{ event.lon }}</p>

    <div v-if="weatherFetched" class="card-weather">
      <p v-if="weatherLoading" class="loading">Buscando clima...</p>
      <div v-else-if="startWeather" class="weather-result">
        <img
          v-if="startWeather.weather[0]"
          :src="iconUrl(startWeather.weather[0].icon)"
          :alt="startWeather.weather[0].description"
        />
        <div class="weather-info">
          <p class="weather-condition">{{ startWeather.weather[0]?.description }}</p>
          <p class="weather-temp">
            <strong>{{ Math.round(startWeather.temp.max) }}°</strong> /
            {{ Math.round(startWeather.temp.min) }}°
          </p>
          <p class="weather-rain">Chuva: {{ Math.round(startWeather.pop * 100) }}%</p>
        </div>
      </div>
      <p v-else class="no-weather">Sem previsão para a data de início do evento.</p>
    </div>

    <div v-if="showActions" class="actions">
      <template v-if="!isFinished">
        <button
          type="button"
          class="icon-btn edit"
          title="Editar evento"
          aria-label="Editar evento"
          @click="emit('edit')"
        >
          <Pencil :size="16" />
        </button>
        <button
          type="button"
          class="icon-btn danger"
          title="Excluir evento"
          aria-label="Excluir evento"
          @click="openConfirm"
        >
          <Trash2 :size="16" />
        </button>
      </template>
      <button
        v-if="!hasPastEnd"
        type="button"
        class="icon-btn weather"
        title="Buscar clima na data de início"
        aria-label="Buscar clima na data de início"
        @click="fetchStartWeather"
      >
        <CloudSun :size="16" />
      </button>
    </div>

    <div v-if="confirmOpen" class="confirm-overlay">
      <div class="confirm-box">
        <p class="confirm-text">Excluir o evento "{{ event.name }}"?</p>
        <div class="confirm-actions">
          <button type="button" class="ghost" @click="cancelDelete">Cancelar</button>
          <button type="button" class="danger" @click="confirmDelete">Excluir</button>
        </div>
      </div>
    </div>
  </article>
</template>

<style scoped>
.event-card {
  position: relative;
}

.event-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 0.75rem;
}

.event-head h3 {
  margin: 0;
}

.badge {
  padding: 0.2rem 0.6rem;
  border-radius: 999px;
  font-size: 0.75rem;
  font-weight: 600;
  flex-shrink: 0;
}

.badge.planned {
  background: #fef9c3;
  color: #854d0e;
}

.badge.confirmed {
  background: #dcfce7;
  color: #166534;
}

.badge.cancelled {
  background: #fee2e2;
  color: #991b1b;
}

.badge.finished {
  background: #e2e8f0;
  color: #334155;
}

.location {
  margin: 0.75rem 0 0;
  font-weight: 600;
}

.dates {
  margin: 0.25rem 0 0;
  color: #475569;
  font-size: 0.9rem;
}

.description {
  margin: 0.5rem 0 0;
  color: #475569;
}

.coords {
  margin: 0.5rem 0 0;
  color: #94a3b8;
  font-size: 0.8rem;
}

.actions {
  display: flex;
  justify-content: flex-end;
  gap: 0.5rem;
  margin-top: 1rem;
}

.icon-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  border: none;
  border-radius: 999px;
  cursor: pointer;
  transition:
    background-color 0.15s ease,
    color 0.15s ease;
}

.icon-btn.danger {
  background: #dc2626;
  color: #ffffff;
}

.icon-btn.danger:hover {
  background: #b91c1c;
}

.icon-btn.weather {
  background: #eff6ff;
  color: #2563eb;
}

.icon-btn.weather:hover {
  background: #dbeafe;
}

.icon-btn.edit {
  background: #f1f5f9;
  color: #0f172a;
}

.icon-btn.edit:hover {
  background: #e2e8f0;
}

.card-weather {
  margin-top: 0.75rem;
  padding-top: 0.75rem;
  border-top: 1px solid #e2e8f0;
}

.card-weather .loading,
.no-weather {
  margin: 0;
  font-size: 0.85rem;
  color: #64748b;
}

.weather-result {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.weather-result img {
  width: 44px;
  height: 44px;
}

.weather-info p {
  margin: 0;
}

.weather-condition {
  color: #475569;
  text-transform: capitalize;
  font-size: 0.9rem;
}

.weather-temp {
  font-size: 0.95rem;
}

.weather-rain {
  color: #64748b;
  font-size: 0.8rem;
}

.confirm-overlay {
  position: absolute;
  inset: 0;
  z-index: 5;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
  background: rgba(15, 23, 42, 0.45);
  border-radius: 12px;
}

.confirm-box {
  width: 100%;
  max-width: 260px;
  padding: 1rem;
  background: #ffffff;
  border-radius: 10px;
  box-shadow: 0 10px 25px rgb(15 23 42 / 0.2);
}

.confirm-text {
  margin: 0 0 0.75rem;
  font-size: 0.9rem;
  color: #0f172a;
}

.confirm-actions {
  display: flex;
  justify-content: flex-end;
  gap: 0.5rem;
}

.confirm-actions .ghost {
  padding: 0.4rem 0.9rem;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  background: #ffffff;
  color: #475569;
  font-size: 0.85rem;
}

.confirm-actions .danger {
  padding: 0.4rem 0.9rem;
  border: none;
  border-radius: 8px;
  background: #dc2626;
  color: #ffffff;
  font-size: 0.85rem;
  font-weight: 600;
}

.confirm-actions .danger:hover {
  background: #b91c1c;
}
</style>
