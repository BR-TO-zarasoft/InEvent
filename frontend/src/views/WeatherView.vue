<script setup lang="ts">
import ForecastCard from '@/components/ForecastCard.vue'
import PlaceAutocomplete from '@/components/PlaceAutocomplete.vue'
import WeatherCard from '@/components/WeatherCard.vue'
import { useWeatherStore } from '@/stores/weatherStore'
import type { Place } from '@/types/weather'

const store = useWeatherStore()

async function onSelect(place: Place) {
  store.selectPlace(place)
  await store.fetchWeather(place.lat, place.lon)
}
</script>

<template>
  <section>
    <h1>Previsão do tempo</h1>

    <PlaceAutocomplete initial-query="São Paulo" auto-select-first @select="onSelect" />

    <div v-if="store.loading" class="loading">Carregando...</div>

    <template v-if="store.current">
      <WeatherCard :weather="store.current" />
      <h2>Próximos 10 dias</h2>
      <div class="forecast-grid">
        <ForecastCard v-for="day in store.forecast" :key="day.dt" :day="day" />
      </div>
    </template>
  </section>
</template>

<style scoped>
.forecast-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
  gap: 1rem;
}
</style>
