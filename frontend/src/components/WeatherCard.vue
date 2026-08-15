<script setup lang="ts">
import type { CurrentWeather } from '@/types/weather'

defineProps<{ weather: CurrentWeather }>()

function iconUrl(icon: string): string {
  return `https://openweathermap.org/img/wn/${icon}@2x.png`
}
</script>

<template>
  <article class="card weather-card">
    <img
      v-if="weather.weather[0]"
      class="weather-icon"
      :src="iconUrl(weather.weather[0].icon)"
      :alt="weather.weather[0].description"
    />
    <div class="weather-summary">
      <h2>{{ Math.round(weather.temp) }}°C</h2>
      <p class="condition">{{ weather.weather[0]?.description }}</p>
    </div>
    <dl class="weather-metrics">
      <div>
        <dt>Sensação</dt>
        <dd>{{ Math.round(weather.feels_like) }}°C</dd>
      </div>
      <div>
        <dt>Umidade</dt>
        <dd>{{ weather.humidity }}%</dd>
      </div>
      <div>
        <dt>Vento</dt>
        <dd>{{ weather.wind_speed }} m/s</dd>
      </div>
      <div>
        <dt>Pressão</dt>
        <dd>{{ weather.pressure }} hPa</dd>
      </div>
    </dl>
  </article>
</template>

<style scoped>
.weather-card {
  display: flex;
  align-items: center;
  gap: 1.25rem;
  margin: 1rem 0 2rem;
}

.weather-icon {
  width: 72px;
  height: 72px;
}

.weather-summary h2 {
  margin: 0;
  font-size: 2rem;
}

.condition {
  margin: 0;
  color: #475569;
  text-transform: capitalize;
}

.weather-metrics {
  display: grid;
  grid-template-columns: repeat(2, auto);
  gap: 0.5rem 2rem;
  margin: 0 0 0 auto;
}

.weather-metrics dt {
  color: #64748b;
  font-size: 0.8rem;
}

.weather-metrics dd {
  margin: 0;
  font-weight: 600;
}
</style>
