import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/', redirect: '/dashboard' },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: () => import('@/views/DashboardView.vue'),
      meta: { title: 'Início' },
    },
    {
      path: '/weather',
      name: 'weather',
      component: () => import('@/views/WeatherView.vue'),
      meta: { title: 'Clima' },
    },
    {
      path: '/events',
      name: 'events',
      component: () => import('@/views/EventsView.vue'),
      meta: { title: 'Eventos' },
    },
    {
      path: '/:pathMatch(.*)*',
      redirect: '/dashboard',
    },
  ],
})

export default router
