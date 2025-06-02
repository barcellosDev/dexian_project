import { createRouter, createWebHistory } from 'vue-router'
import { useAuth } from '@/composables/useAuth'
import Login from '../views/Login.vue'
import Products from '@/views/Products.vue'
import Clients from '@/views/Clients.vue'
import Orders from '@/views/Orders.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'root',
      component: Login
    },
    {
      path: '/products',
      name: 'products',
      component: Products,
      meta: { requiresAuth: true }
    },
    {
      path: '/login',
      name: 'login',
      component: Login
    },
    {
      path: '/clients',
      name: 'clients',
      component: Clients,
      meta: { requiresAuth: true }
    },
    {
      path: '/orders',
      name: 'orders',
      component: Orders,
      meta: { requiresAuth: true }
    }
  ],
})

router.beforeEach(async (to, from, next) => {
  const { checkAuth, user } = useAuth()
  await checkAuth()
  const isAuthenticated = !!user.value

  if (to?.meta?.requiresAuth && !isAuthenticated) {
    next({ name: 'login' })
  } else if ((to.name === 'login' || to.name === 'root') && isAuthenticated) {
    next({ name: 'products' })
  } else {
    next()
  }
})

export default router
