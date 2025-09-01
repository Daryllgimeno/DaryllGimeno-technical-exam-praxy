<script setup>
import AuthenticatedPageLayout from '@/Layouts/AuthenticatedLayout.vue'
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { router } from '@inertiajs/vue3'

// Reactive state
const products = ref({ data: [], current_page: 1, last_page: 1 })
const selectedCategory = ref('')
const keyword = ref('')
const allCategories = ref([])

// Fetch products
const fetchProducts = async (page = 1) => {
  const response = await axios.get('/api/products', {
    params: { keyword: keyword.value, category: selectedCategory.value, page }
  })
  products.value = response.data

  if (!allCategories.value.length) {
    allCategories.value = [...new Set(response.data.data.map(p => p.category))]
  }
}

// Delete product
const deleteProduct = async (id) => {
  if (!confirm("Are you sure you want to delete this product?")) return
  await axios.delete(`/api/products/${id}`)
  fetchProducts(products.value.current_page)
}

// Edit product
const editProduct = (id) => {
  router.get(`/products/${id}/edit`)
}

// On mounted
onMounted(() => fetchProducts())
</script>

<template>
  <AuthenticatedPageLayout>
    <template #default>

      <!-- Main content wrapper aligned with sidebar -->
      <div class="max-w-6xl mx-auto px-4">

        <!-- Search & Filter -->
        <div class="flex mb-4 gap-4">
          <input
            v-model="keyword"
            @input="fetchProducts"
            type="text"
            placeholder="Search by name or description"
            class="border p-2 rounded flex-1"
          />
          <select
            v-model="selectedCategory"
            @change="fetchProducts"
            class="border p-2 rounded"
          >
            <option value="">All Categories</option>
            <option v-for="cat in allCategories" :key="cat" :value="cat">{{ cat }}</option>
          </select>
        </div>

        <!-- Product Table -->
        <div class="overflow-x-auto">
          <table class="w-full border">
            <thead>
              <tr class="bg-gray-200">
                <th class="p-2 border">ID</th>
                <th class="p-2 border">Name</th>
                <th class="p-2 border">Description</th>
                <th class="p-2 border">Category</th>
                <th class="p-2 border">Images</th>
                <th class="p-2 border">Date & Time</th>
                <th class="p-2 border">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="product in products.data" :key="product.id">
                <td class="p-2 border">{{ product.id }}</td>
                <td class="p-2 border">{{ product.name }}</td>
                <td class="p-2 border">{{ product.description }}</td>
                <td class="p-2 border">{{ product.category }}</td>
                <td class="p-2 border flex gap-1">
                  <img
                    v-for="img in product.images"
                    :key="img"
                    :src="`/storage/${img}`"
                    class="w-16 h-16 object-cover border rounded"
                  />
                </td>
                <td class="p-2 border">
                  {{ product.date_time ? new Date(product.date_time).toLocaleString() : '-' }}
                </td>
                <td class="p-2 border flex gap-2">
                  <button
                    @click="editProduct(product.id)"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded"
                  >
                    Edit
                  </button>
                  <button
                    @click="deleteProduct(product.id)"
                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded"
                  >
                    Delete
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

       
        <div class="mt-4 flex justify-end">
          <div class="flex gap-2">
            <button
              v-for="page in products.last_page"
              :key="page"
              @click="fetchProducts(page)"
              :class="{ 'font-bold': page === products.current_page }"
              class="p-2 border rounded"
            >
              {{ page }}
            </button>
          </div>
        </div>

      </div>
    </template>
  </AuthenticatedPageLayout>
</template>
