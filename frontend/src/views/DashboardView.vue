<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'

import EventCard from '@/components/EventCard.vue'
import WeatherCard from '@/components/WeatherCard.vue'
import { eventService } from '@/services/eventService'
import { useWeatherStore } from '@/stores/weatherStore'
import type { EventItem } from '@/types/event'

const store = useWeatherStore()
const events = ref<EventItem[]>([])
const loading = ref(true)

const upcoming = computed(() =>
  events.value
    .filter((event) => event.status === 'planned' || event.status === 'confirmed')
    .sort((a, b) => new Date(a.start_date).getTime() - new Date(b.start_date).getTime()),
)

onMounted(async () => {
  try {
    const page = await eventService.list(50)
    events.value = page.data
    const first = upcoming.value[0]
    if (first) {
      await store.fetchWeather(first.lat, first.lon)
    }
  } catch (cause) {
    void cause
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <section>
    <h1>Início</h1>
    <p class="hint">Clima dos próximos eventos</p>

    <div v-if="loading" class="loading">Carregando...</div>

    <template v-else>
      <template v-if="upcoming.length && store.current">
        <h2>Clima agora em {{ upcoming[0].location }}</h2>
        <WeatherCard :weather="store.current" />
      </template>

      <h2>Próximos eventos</h2>
      <div v-if="upcoming.length" class="events-grid">
        <EventCard v-for="event in upcoming" :key="event.id" :event="event" :actions="false" />
      </div>
      <p v-else>Nenhum evento planejado.</p>
    </template>
  </section>
</template>

<style scoped>
.hint {
  color: #64748b;
  margin-top: -0.5rem;
}

.events-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 1rem;
}
</style>
