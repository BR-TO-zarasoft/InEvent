import { defineStore } from 'pinia'
import { ref } from 'vue'

import { weatherService } from '@/services/weatherService'
import type { CurrentWeather, DailyForecast, Place } from '@/types/weather'

export const useWeatherStore = defineStore('weather', () => {
  const places = ref<Place[]>([])
  const current = ref<CurrentWeather | null>(null)
  const forecast = ref<DailyForecast[]>([])
  const loading = ref(false)

  async function searchPlaces(query: string) {
    loading.value = true
    try {
      places.value = await weatherService.searchPlaces(query)
    } catch (cause) {
      void cause
    } finally {
      loading.value = false
    }
  }

  async function fetchWeather(lat: number, lon: number) {
    loading.value = true
    try {
      const [currentResponse, forecastResponse] = await Promise.all([
        weatherService.getCurrent(lat, lon),
        weatherService.getForecast(lat, lon),
      ])
      current.value = currentResponse.data[0] ?? null
      forecast.value = forecastResponse.data
    } catch (cause) {
      void cause
    } finally {
      loading.value = false
    }
  }

  function selectPlace(place: Place) {
    places.value = [place]
  }

  return { places, current, forecast, loading, searchPlaces, fetchWeather, selectPlace }
})
