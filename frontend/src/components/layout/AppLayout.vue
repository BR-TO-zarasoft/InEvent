<script setup lang="ts">
import { CalendarDays, CloudSun, LayoutDashboard } from 'lucide-vue-next'
import { computed } from 'vue'
import { useRoute } from 'vue-router'

import ToastContainer from '@/components/ToastContainer.vue'

const route = useRoute()

const navItems = [
  { to: '/dashboard', label: 'Início', icon: LayoutDashboard },
  { to: '/weather', label: 'Clima', icon: CloudSun },
  { to: '/events', label: 'Eventos', icon: CalendarDays },
]

const pageTitle = computed(() => (route.meta.title as string | undefined) ?? 'Event Weather')
</script>

<template>
  <div class="layout">
    <aside class="sidebar">
      <div class="brand">
        <CloudSun :size="24" class="brand-icon" />
        <span class="brand-name">Event Weather</span>
      </div>

      <nav class="nav">
        <RouterLink v-for="item in navItems" :key="item.to" :to="item.to" class="nav-item">
          <component :is="item.icon" :size="18" />
          <span>{{ item.label }}</span>
        </RouterLink>
      </nav>
    </aside>

    <div class="main-col">
      <header class="topbar">
        <h1 class="page-title">{{ pageTitle }}</h1>
      </header>

      <main class="content">
        <RouterView />
      </main>
    </div>

    <ToastContainer />
  </div>
</template>

<style scoped>
.layout {
  display: flex;
  min-height: 100vh;
}

.sidebar {
  position: sticky;
  top: 0;
  display: flex;
  flex-direction: column;
  flex-shrink: 0;
  width: 240px;
  height: 100vh;
  padding: 0.75rem;
  background: #ffffff;
  border-right: 1px solid #e2e8f0;
}

.brand {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 0.5rem 1.25rem;
}

.brand-icon {
  color: #2563eb;
}

.brand-name {
  font-weight: 700;
  font-size: 1.05rem;
  color: #0f172a;
}

.nav {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.6rem 0.75rem;
  border-radius: 8px;
  color: #475569;
  font-weight: 500;
  font-size: 0.925rem;
  text-decoration: none;
  transition:
    background-color 0.15s ease,
    color 0.15s ease;
}

.nav-item:hover {
  background: #f1f5f9;
  color: #0f172a;
}

.nav-item.router-link-active {
  background: #eff6ff;
  color: #2563eb;
}

.main-col {
  display: flex;
  flex: 1;
  flex-direction: column;
  min-width: 0;
}

.topbar {
  position: sticky;
  top: 0;
  z-index: 10;
  padding: 1rem 2rem;
  background: #ffffff;
  border-bottom: 1px solid #e2e8f0;
}

.page-title {
  margin: 0;
  font-size: 1.25rem;
  color: #0f172a;
}

.content {
  flex: 1;
  padding: 2rem;
  max-width: 1100px;
  width: 100%;
}
</style>
