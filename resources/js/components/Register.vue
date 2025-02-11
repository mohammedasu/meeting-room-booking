<template>
    <div class="max-w-md mx-auto mt-8">
      <p v-if="errorMessage" class="text-red-500 mt-2">{{ errorMessage }}</p>
      <form @submit.prevent="onSubmit" class="space-y-4">
        <input
          v-model="name"
          type="text"
          placeholder="Full Name"
          class="w-full px-4 py-2 border rounded"
          @blur="validateField('name')"
          :class="{ 'border-red-500': errors.name }"
        />
        <span v-if="errors.name" class="text-red-500 text-sm">{{ errors.name }}</span>
        <input
          v-model="email"
          type="email"
          placeholder="Email"
          class="w-full px-4 py-2 border rounded"
          @blur="validateField('email')"
          :class="{ 'border-red-500': errors.email }"
        />
        <span v-if="errors.email" class="text-red-500 text-sm">{{ errors.email }}</span>
        <input
          v-model="password"
          type="password"
          placeholder="Password"
          class="w-full px-4 py-2 border rounded"
          @blur="validateField('password')"
          :class="{ 'border-red-500': errors.password }"
        />
        <span v-if="errors.password" class="text-red-500 text-sm">{{ errors.password }}</span>
        <input
          v-model="password_confirmation"
          type="password"
          placeholder="Confirm Password"
          class="w-full px-4 py-2 border rounded"
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
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { useRouter } from 'vue-router';
  import { registerSchema } from '@/utils/validation';
  import { useForm } from 'vee-validate';

    const { defineField, handleSubmit, errors, validateField } = useForm({
      validationSchema: registerSchema,
    });

    const [name] = defineField('name');
    const [email] = defineField('email');
    const [password] = defineField('password');
    const [password_confirmation] = defineField('password_confirmation');

    const errorMessage = ref('');
    const disabled = ref(false);
    const router = useRouter();

    const onSubmit = handleSubmit(async (values) => {

      await axios.post('/api/register', values).then((response) => {
        disabled.value = false;
        alert(response.data.message);
        router.push('/');
      }).catch((error) => {
        disabled.value = false;
        errorMessage.value = error.response?.data?.message || 'An error occurred.';
      });
  });
  </script>
  