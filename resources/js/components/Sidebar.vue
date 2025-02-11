<template>
      <!-- Sidebar -->
      <div class="w-64 h-screen bg-gray-800 text-white p-5">
        <h2 class="text-2xl font-semibold mb-6">Booking System</h2>
        <ul>
          <li>
            <router-link to="/booking" class="block py-2 px-4 hover:bg-gray-700 rounded transition">Booking</router-link>
          </li>
          <li>
            <router-link to="/my-booking" class="block py-2 px-4 hover:bg-gray-700 rounded transition">My Bookings</router-link>
          </li>
          <li>
            <router-link to="/subscribe" class="block py-2 px-4 hover:bg-gray-700 rounded transition">Subscribe</router-link>
          </li>
          <li>
            <a @click="logout()" class="block py-2 px-4 hover:bg-gray-700 rounded transition">Log Out</a>
          </li>
        </ul>
      </div>
  </template>

<script setup>
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';

const router = useRouter();
const authStore = useAuthStore();

const logout = async () => {
  try {
    await authStore.logout();
    router.push('/');
  } catch (err) {
    console.log(err);
    
  }
};
</script>