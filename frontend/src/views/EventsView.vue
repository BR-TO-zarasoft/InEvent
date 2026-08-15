<script setup lang="ts">
import { onMounted, reactive, ref } from 'vue'

import EventCard from '@/components/EventCard.vue'
import PaginationBar from '@/components/PaginationBar.vue'
import { eventService } from '@/services/eventService'
import type { EventItem, EventPayload, Paginated } from '@/types/event'

const perPage = 9
const paginator = ref<Paginated<EventItem> | null>(null)
const events = ref<EventItem[]>([])
const currentPage = ref(1)
const loading = ref(false)
const showForm = ref(false)
const editingId = ref<number | null>(null)

const form = reactive<EventPayload>({
  name: '',
  location: '',
  lat: 0,
  lon: 0,
  start_date: '',
  end_date: '',
  description: '',
  status: 'planned',
})

async function load(page = 1) {
  loading.value = true
  try {
    paginator.value = await eventService.list(perPage, page)
    events.value = paginator.value.data
    currentPage.value = paginator.value.current_page
  } catch (cause) {
    void cause
  } finally {
    loading.value = false
  }
}

function resetForm() {
  form.name = ''
  form.location = ''
  form.lat = 0
  form.lon = 0
  form.start_date = ''
  form.end_date = ''
  form.description = ''
  form.status = 'planned'
}

function toDatetimeLocal(value: string): string {
  const date = new Date(value)
  if (Number.isNaN(date.getTime())) return value
  const pad = (n: number) => String(n).padStart(2, '0')
  return `${date.getUTCFullYear()}-${pad(date.getUTCMonth() + 1)}-${pad(date.getUTCDate())}T${pad(date.getUTCHours())}:${pad(date.getUTCMinutes())}`
}

function openForm() {
  editingId.value = null
  resetForm()
  showForm.value = true
}

function openEdit(event: EventItem) {
  editingId.value = event.id
  form.name = event.name
  form.location = event.location
  form.lat = event.lat
  form.lon = event.lon
  form.start_date = toDatetimeLocal(event.start_date)
  form.end_date = toDatetimeLocal(event.end_date)
  form.description = event.description ?? ''
  form.status = event.status
  showForm.value = true
}

function closeForm() {
  showForm.value = false
  editingId.value = null
}

async function submit() {
  try {
    if (editingId.value !== null) {
      await eventService.update(editingId.value, { ...form })
      await load(currentPage.value)
    } else {
      await eventService.create({ ...form })
      await load(1)
    }
    closeForm()
  } catch (cause) {
    void cause
  }
}

async function remove(id: number) {
  try {
    await eventService.remove(id)
    await load(currentPage.value)
    if (events.value.length === 0 && currentPage.value > 1) {
      await load(currentPage.value - 1)
    }
  } catch (cause) {
    void cause
  }
}

onMounted(load)
</script>

<template>
  <section>
    <div class="head">
      <h1>Eventos</h1>
      <button type="button" class="primary" @click="openForm">Novo evento</button>
    </div>

    <div v-if="loading" class="loading">Carregando...</div>

    <div v-else-if="events.length" class="events-grid">
      <EventCard
        v-for="event in events"
        :key="event.id"
        :event="event"
        @edit="openEdit(event)"
        @delete="remove(event.id)"
      />
    </div>
    <p v-else>Nenhum evento cadastrado.</p>

    <PaginationBar
      v-if="paginator && paginator.last_page > 1"
      :model-value="currentPage"
      :last-page="paginator.last_page"
      @update:model-value="load"
    />

    <Teleport to="body">
      <div v-if="showForm" class="modal-overlay" @click.self="closeForm">
        <form class="modal" @submit.prevent="submit">
          <div class="modal-head">
            <h2>{{ editingId !== null ? 'Editar evento' : 'Novo evento' }}</h2>
            <button type="button" class="close" @click="closeForm" aria-label="Fechar">&times;</button>
          </div>

          <div class="form-grid">
            <label>
              Nome
              <input v-model="form.name" type="text" required />
            </label>
            <label>
              Local
              <input v-model="form.location" type="text" required />
            </label>
            <label>
              Latitude
              <input v-model.number="form.lat" type="number" step="any" required />
            </label>
            <label>
              Longitude
              <input v-model.number="form.lon" type="number" step="any" required />
            </label>
            <label>
              Início
              <input v-model="form.start_date" type="datetime-local" required />
            </label>
            <label>
              Fim
              <input v-model="form.end_date" type="datetime-local" required />
            </label>
            <label>
              Status
              <select v-model="form.status">
                <option value="planned">Planejado</option>
                <option value="confirmed">Confirmado</option>
                <option value="cancelled">Cancelado</option>
                <option value="finished">Encerrado</option>
              </select>
            </label>
          </div>
          <label>
            Descrição
            <textarea v-model="form.description"></textarea>
          </label>

          <div class="modal-actions">
            <button type="button" class="ghost" @click="closeForm">Cancelar</button>
            <button type="submit" class="primary">Salvar</button>
          </div>
        </form>
      </div>
    </Teleport>
  </section>
</template>

<style scoped>
.head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 1rem;
}

.head h1 {
  margin: 0;
}

.primary {
  padding: 0.6rem 1.25rem;
  border: none;
  border-radius: 8px;
  background: #0f172a;
  color: #f8fafc;
  font-weight: 600;
}

.danger {
  margin-top: 0.5rem;
  padding: 0.35rem 0.8rem;
  border: 1px solid #fecaca;
  border-radius: 8px;
  background: #fff;
  color: #b91c1c;
}

.modal-overlay {
  position: fixed;
  inset: 0;
  z-index: 50;
  display: flex;
  align-items: flex-start;
  justify-content: center;
  padding: 4rem 1rem 1rem;
  background: rgba(15, 23, 42, 0.5);
}

.modal {
  width: 100%;
  max-width: 560px;
  padding: 1.5rem;
  background: #ffffff;
  border-radius: 12px;
  box-shadow: 0 20px 40px rgba(15, 23, 42, 0.2);
}

.modal-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 1rem;
}

.modal-head h2 {
  margin: 0;
  font-size: 1.15rem;
  color: #0f172a;
}

.modal .close {
  padding: 0.25rem 0.6rem;
  border: none;
  border-radius: 6px;
  background: transparent;
  color: #64748b;
  font-size: 1.25rem;
  line-height: 1;
  cursor: pointer;
}

.modal .close:hover {
  background: #f1f5f9;
  color: #0f172a;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
  gap: 0.75rem;
}

.modal label {
  display: block;
  font-size: 0.85rem;
  color: #475569;
  margin-bottom: 0.75rem;
}

.modal input,
.modal textarea,
.modal select {
  display: block;
  width: 100%;
  margin-top: 0.25rem;
  padding: 0.5rem 0.75rem;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
}

.modal textarea {
  min-height: 90px;
  resize: vertical;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 0.5rem;
  margin-top: 0.5rem;
}

.ghost {
  padding: 0.6rem 1.25rem;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  background: #fff;
  color: #475569;
  font-weight: 600;
}

.ghost:hover {
  background: #f1f5f9;
}

.events-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 1rem;
}
</style>
