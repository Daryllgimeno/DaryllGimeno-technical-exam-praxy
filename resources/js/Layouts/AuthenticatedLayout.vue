<script setup>
import { Link, useForm } from '@inertiajs/vue3';

const form = useForm({});

const logout = () => {
  form.post(route('logout'));
};
</script>

<template>
  <div class="min-h-screen bg-gray-100 flex">
    <!-- Sidebar -->
    <aside class="#">
      <div class="p-4 text-2xl font-bold text-center border-b border-gray-700">
        PMS
      </div>
      <nav class="flex-1 px-2 py-4 space-y-2">
        <Link href="/dashboard" class="block px-3 py-2 rounded hover:bg-gray-700">Dashboard</Link>
        <Link href="/products" class="block px-3 py-2 rounded hover:bg-gray-700">Products</Link>
        <Link href="/users" class="block px-3 py-2 rounded hover:bg-gray-700">Users</Link>
        <Link href="/settings" class="block px-3 py-2 rounded hover:bg-gray-700">Settings</Link>
      </nav>
    </aside>

    <!-- Main content -->
    <div class="flex-1 flex flex-col">
      <!-- Top Navbar -->
      <header class="bg-white shadow flex items-center justify-between px-6 py-4">
        <h1 class="text-lg font-semibold">Product Management System</h1>
        <div class="flex items-center space-x-4">
          <span class="text-gray-600">Hello, {{ $page.props.auth.user.name }}</span>

          <button
            @click="logout"
            class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600"
            :disabled="form.processing"
          >
            Logout
          </button>
        </div>
      </header>

      <!-- Page Content -->
      <main class="flex-1 p-6 bg-gray-100">
        <slot />
      </main>
    </div>
  </div>
</template>
