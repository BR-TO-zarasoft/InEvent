export type EventStatus = 'planned' | 'confirmed' | 'cancelled' | 'finished'

export interface EventItem {
  id: number
  name: string
  location: string
  lat: number
  lon: number
  start_date: string
  end_date: string
  description: string | null
  status: EventStatus
  created_at: string
  updated_at: string
}

export interface EventPayload {
  name: string
  location: string
  lat: number
  lon: number
  start_date: string
  end_date: string
  description?: string | null
  status?: EventStatus
}

export interface Paginated<T> {
  current_page: number
  data: T[]
  first_page_url: string | null
  from: number | null
  last_page: number
  last_page_url: string | null
  links: Array<{ url: string | null; label: string; active: boolean }>
  next_page_url: string | null
  path: string
  per_page: number
  prev_page_url: string | null
  to: number | null
  total: number
}
