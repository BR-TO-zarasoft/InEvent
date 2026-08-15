import type { EventItem, EventPayload, Paginated } from '@/types/event'

import { api } from './api'

export const eventService = {
  async list(perPage = 15, page = 1): Promise<Paginated<EventItem>> {
    const { data } = await api.get('/events', { params: { per_page: perPage, page } })
    return data
  },

  async get(id: number): Promise<EventItem> {
    const { data } = await api.get(`/events/${id}`)
    return data
  },

  async create(payload: EventPayload): Promise<EventItem> {
    const { data } = await api.post('/events', payload)
    return data
  },

  async update(id: number, payload: Partial<EventPayload>): Promise<EventItem> {
    const { data } = await api.put(`/events/${id}`, payload)
    return data
  },

  async remove(id: number): Promise<void> {
    await api.delete(`/events/${id}`)
  },
}
