import { createRouter, createWebHistory } from 'vue-router';
import Login from '../components/Login.vue';
import Register from '../components/Register.vue';
import Subscription from '../components/Subscription.vue';
import BookingForm from '../components/BookingForm.vue';
import MyBooking from '../components/MyBooking.vue';

const routes = [
  { path: '/', name: 'login', component: Login, meta: { requiresGuest: true } },
  { path: '/register', component: Register, meta: { requiresGuest: true } },
  { path: '/subscribe', component: Subscription, meta: { requiresAuth: true } },
  { path: '/booking', component: BookingForm, meta: { requiresAuth: true } },
  { path: '/my-booking', component: MyBooking, meta: { requiresAuth: true } },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

router.beforeEach((to, from, next) => {
    const isAuthenticated = localStorage.getItem('token');
    if (to.meta.requiresAuth && !isAuthenticated) {
      next('/');
    } else if (to.meta.requiresAuth && isAuthenticated) {
      next();
    } else {
      if (to.name === 'login') 
        return next();
      next('/');
    }
});

export default router;
