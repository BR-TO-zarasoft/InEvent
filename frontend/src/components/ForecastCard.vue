<script setup lang="ts">
import type { DailyForecast } from '@/types/weather'

defineProps<{ day: DailyForecast }>()

function iconUrl(icon: string): string {
  return `https://openweathermap.org/img/wn/${icon}@2x.png`
}

function formatDate(dt: number): string {
  return new Date(dt * 1000).toLocaleDateString('pt-BR', {
    weekday: 'short',
    day: '2-digit',
    month: 'short',
  })
}
</script>

<template>
  <article class="card forecast-card">
    <p class="date">{{ formatDate(day.dt) }}</p>
    <img
      v-if="day.weather[0]"
      class="forecast-icon"
      :src="iconUrl(day.weather[0].icon)"
      :alt="day.weather[0].description"
    />
    <p class="temp">
      <strong>{{ Math.round(day.temp.max) }}°</strong> / {{ Math.round(day.temp.min) }}°
    </p>
    <p class="condition">{{ day.weather[0]?.description }}</p>
    <p class="rain">Chuva: {{ Math.round(day.pop * 100) }}%</p>
  </article>
</template>

<style scoped>
.forecast-card {
  text-align: center;
}

.date {
  margin: 0 0 0.5rem;
  color: #64748b;
  text-transform: capitalize;
}

.forecast-icon {
  width: 56px;
  height: 56px;
}

.temp {
  margin: 0.25rem 0;
  font-size: 1.1rem;
}

.condition {
  margin: 0.25rem 0;
  color: #475569;
  text-transform: capitalize;
  font-size: 0.9rem;
}

.rain {
  margin: 0.25rem 0 0;
  color: #64748b;
  font-size: 0.85rem;
}
</style>
