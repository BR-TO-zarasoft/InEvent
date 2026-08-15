import type { CurrentWeather, DailyForecast, Place, WeatherResponse } from '@/types/weather'

import { api } from './api'

export const weatherService = {
  async searchPlaces(q: string, limit = 5): Promise<Place[]> {
    const { data } = await api.get('/weather/geocode', { params: { q, limit } })
    return data
  },

  async getCurrent(lat: number, lon: number, units = 'metric'): Promise<WeatherResponse<CurrentWeather>> {
    const { data } = await api.get('/weather/current', { params: { lat, lon, units } })
    return data
  },

  async getForecast(lat: number, lon: number, days = 10, units = 'metric'): Promise<WeatherResponse<DailyForecast>> {
    const { data } = await api.get('/weather/forecast', { params: { lat, lon, days, units } })
    return data
  },
}
