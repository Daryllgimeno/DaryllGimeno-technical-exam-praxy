<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Checkbox from '@/Components/Checkbox.vue';

defineProps({
  canResetPassword: Boolean,
  status: String,
});

const form = useForm({
  login: '',
  password: '',
  remember: false,
});

const submit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  });
};
</script>

<template>
  <GuestLayout>
    <Head title="Log in" />

    <div class="max-w-md w-full">
     
      <h2 class="text-2xl font-bold text-center text-gray-900 mb-6">
        Welcome to Product Management System
      </h2>

      
      <div v-if="status" class="mb-4 text-sm font-medium text-green-600 text-center">
        {{ status }}
      </div>

      <form @submit.prevent="submit" class="space-y-6">
    
        <div>
          <InputLabel for="login" value="Username or Email" />
          <TextInput
            id="login"
            type="text"
            v-model="form.login"
            required
            autofocus
            autocomplete="username"
            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
          />
          <InputError class="mt-1" :message="form.errors.login" />
        </div>

        <div>
          <InputLabel for="password" value="Password" />
          <TextInput
            id="password"
            type="password"
            v-model="form.password"
            required
            autocomplete="current-password"
            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
          />
          <InputError class="mt-1" :message="form.errors.password" />
        </div>

        <div class="flex items-center">
          <Checkbox name="remember" v-model:checked="form.remember" />
          <span class="ml-2 text-sm text-gray-600">Remember me</span>
        </div>

      
        <div class="flex items-center justify-between">
          <Link
            v-if="canResetPassword"
            :href="route('password.request')"
            class="text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
          >
            Forgot password?
          </Link>

          <PrimaryButton
            :disabled="form.processing"
            :class="{ 'opacity-50': form.processing }"
          >
            Log in
          </PrimaryButton>
        </div>
      </form>
    </div>
  </GuestLayout>
</template>
