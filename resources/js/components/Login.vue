<template>
  <div class="max-w-sm mx-auto p-6 bg-white shadow-md rounded">
    <h2 class="text-2xl font-bold mb-4">Login</h2>

    <p v-if="errors.general" class="text-red-500">{{ errors.general }}</p>
    <form @submit.prevent="onSubmit">
      <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-left">Email</label>
        <input
          v-model="email"
          type="email"
          id="email"
          class="w-full p-2 border border-gray-300 rounded"
          @blur="validateField('email')"
          :class="{ 'border-red-500': errors.email }"
        />
        <span v-if="errors.email" class="text-red-500 text-sm">{{ errors.email }}</span>
      </div>

      <div class="mb-4">
        <label for="password" class="block text-sm font-medium text-left">Password</label>
        <input
          v-model="password"
          type="password"
          id="password"
          class="w-full p-2 border border-gray-300 rounded"
          @blur="validateField('password')"
          :class="{ 'border-red-500': errors.password }"
        />
        <span v-if="errors.password" class="text-red-500 text-sm">{{ errors.password }}</span>
      </div>

      <button
        type="submit"
        :disabled="disabled"
        class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600"
      >
        Login
      </button>
    </form>
    <p class="mt-4 text-center">
      Don't have an account?
      <router-link to="/register" class="text-blue-500 hover:underline">
        Register here
      </router-link>
    </p>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import { loginSchema } from '@/utils/validation';
import { useForm } from 'vee-validate';

const { defineField, handleSubmit, setErrors, errors, validateField } = useForm({
  validationSchema: loginSchema,
});

const [email] = defineField('email');
const [password] = defineField('password');
const router = useRouter();
const authStore = useAuthStore();
const disabled = ref(false);

const onSubmit = handleSubmit(async (values) => {
  try {
    disabled.value = true;
    await authStore.login(values);
    disabled.value = false;
    router.push('/booking');
  } catch (err) {
    disabled.value = false;
    setErrors({ general: err });
  }
});
</script>
