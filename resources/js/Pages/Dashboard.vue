<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { defineProps, ref, onMounted, onUnmounted } from 'vue';

import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
} from 'chart.js';
import { Bar } from 'vue-chartjs';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

// Props 
const props = defineProps({
  productCount: { type: Number, default: 0 },
  userCount: { type: Number, default: 0 },
  categoryLabels: { type: Array, default: () => [] },
  categoryData: { type: Array, default: () => [] },
  topCategory: { type: Object, default: null },
  recentProduct: { type: Object, default: null },
});

// Dashboard cards
const cards = [
  { label: 'Products', value: props.productCount, color: 'bg-blue-500' },
  { label: 'Users', value: props.userCount, color: 'bg-green-500' },
  {
    label: 'Top Category',
    value: props.topCategory
      ? `${props.topCategory.name} (${props.topCategory.count})`
      : 'No data',
    color: 'bg-yellow-500',
  },
  {
    label: 'Most Recent Product',
    value: props.recentProduct ? props.recentProduct.name : 'No product',
    color: 'bg-red-500',
  },
];

// Chart config
const chartData = {
  labels: props.categoryLabels,
  datasets: [
    {
      label: 'Number of Products',
      data: props.categoryData,
      backgroundColor: '#3b82f6',
    },
  ],
};

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: { position: 'top' },
    title: { display: true, text: 'Number of Products' },
  },
};

const currentDate = ref('');

function updateDate() {
  currentDate.value = new Date().toLocaleDateString('en-US', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
}

let intervalId;
onMounted(() => {
  updateDate();
  intervalId = setInterval(updateDate, 60000);
});

onUnmounted(() => clearInterval(intervalId));
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    
    <div class="flex justify-end mb-6">
      <div
        class="flex items-center gap-2 bg-white px-4 py-2 rounded-lg shadow border border-gray-200"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="w-5 h-5 text-blue-500"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M8 7V3m8 4V3m-9 8h10m-12 5h14a2 2 0 
               002-2V7a2 2 0 00-2-2h-2V3m-8 0v2H6a2 2 0 
               00-2 2v10a2 2 0 002 2z"
          />
        </svg>
        <p class="text-gray-700 font-medium">{{ currentDate }}</p>
      </div>
    </div>


    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
      <div
        v-for="(card, index) in cards"
        :key="index"
        :class="`${card.color} text-white p-6 rounded-lg shadow`"
      >
        <h3 class="text-2xl font-bold truncate">{{ card.value }}</h3>
        <p>{{ card.label }}</p>
      </div>
    </div>

  
    <div class="bg-white p-6 rounded-lg shadow h-80">
      <Bar :data="chartData" :options="chartOptions" />
    </div>
  </AuthenticatedLayout>
</template>
