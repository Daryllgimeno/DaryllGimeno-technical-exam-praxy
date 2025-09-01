<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { defineProps } from 'vue';


const props = defineProps({
  productCount: Number,
  userCount: Number,
  orderCount: Number,
  errorCount: Number,
});


const cards = [
  { label: 'Products', value: props.productCount, color: 'bg-blue-500' },
  { label: 'Users', value: props.userCount, color: 'bg-green-500' },
  { label: 'Orders', value: props.orderCount, color: 'bg-yellow-500' },
  { label: 'Errors', value: props.errorCount, color: 'bg-red-500' },
];
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div 
        v-for="(card, index) in cards" 
        :key="index"
        :class="`${card.color} text-white p-6 rounded-lg shadow`"
      >
        <h3 class="text-2xl font-bold">{{ card.value }}</h3>
        <p>{{ card.label }}</p>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
