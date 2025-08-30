<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <!-- Small box -->
      <div class="bg-blue-500 text-white p-6 rounded-lg shadow">
        <h3 class="text-2xl font-bold">150</h3>
        <p>Products</p>
      </div>
      <div class="bg-green-500 text-white p-6 rounded-lg shadow">
        <h3 class="text-2xl font-bold">53</h3>
        <p>Users</p>
      </div>
      <div class="bg-yellow-500 text-white p-6 rounded-lg shadow">
        <h3 class="text-2xl font-bold">44</h3>
        <p>Orders</p>
      </div>
      <div class="bg-red-500 text-white p-6 rounded-lg shadow">
        <h3 class="text-2xl font-bold">65</h3>
        <p>Errors</p>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
