<template>
    <div class="max-w-md mx-auto mt-8">
      <form @submit.prevent="register" class="space-y-4">
        <input
          v-model="name"
          type="text"
          placeholder="Full Name"
          class="w-full px-4 py-2 border rounded"
          required
        />
        <input
          v-model="email"
          type="email"
          placeholder="Email"
          class="w-full px-4 py-2 border rounded"
          required
        />
        <input
          v-model="password"
          type="password"
          placeholder="Password"
          class="w-full px-4 py-2 border rounded"
          required
        />
        <input
          v-model="password_confirmation"
          type="password"
          placeholder="Confirm Password"
          class="w-full px-4 py-2 border rounded"
          required
        />
        <button
          type="submit"
          :disabled="disabled"
          class="w-full bg-green-500 text-white py-2 rounded"
        >
          Register
        </button>
      </form>

      <p class="mt-4 text-center">
          Already have an account?
          <router-link to="/" class="text-blue-500 hover:underline">
            Login here
          </router-link>
      </p>
  
      <p v-if="errorMessage" class="text-red-500 mt-2">{{ errorMessage }}</p>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import axios from 'axios';
  import { useRouter } from 'vue-router';

    const name = ref('');
    const email = ref('');
    const password = ref('');
    const password_confirmation = ref('');
    const errorMessage = ref('');
    const disabled = ref(false);
    const router = useRouter();

    const register = async () => {
        try {
            disabled.value = true;
            const response = await axios.post('/api/register', {
              name: name.value,
              email: email.value,
              password: password.value,
              password_confirmation: password_confirmation.value
            });
            disabled.value = false;
            alert(response.data.message);
            router.push('/');
            console.log('Registration successful', response.data);
            // Redirect or show a success message
        } catch (error) {
            disabled.value = false;
            errorMessage.value = error.response?.data?.message || 'An error occurred.';
        }
    };
  </script>
  