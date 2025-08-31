<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

// Reactive state
const products = ref({ data: [], current_page: 1, last_page: 1 })
const selectedCategory = ref('')
const keyword = ref('')
const allCategories = ref([]); 
// Computed property for unique categories
const categories = computed(() => {
  return [...new Set(products.value.data.map(p => p.category))]
})

// Fetch products
const fetchProducts = async (page = 1) => {
  const response = await axios.get('/api/products', {
    params: {
      keyword: keyword.value,
      category: selectedCategory.value,
      page: page
    }
  })
  products.value = response.data

  if (allCategories.value.length === 0) {
    allCategories.value = [...new Set(response.data.data.map(p => p.category))];
  }
}

// Load products on mount
onMounted(() => {
  fetchProducts()
})

// Delete product
const deleteProduct = async (id) => {
  if (confirm("Are you sure you want to delete this product?")) {
    try {
      await axios.delete(`/api/products/${id}`)
      fetchProducts(products.value.current_page)
    } catch (error) {
      console.error(error)
      alert("Failed to delete the product.")
    }
  }
}
</script>

<template>
  <div class="p-6">
    <h1 class="text-xl font-bold mb-4">Product List</h1>

    <!-- Search & Filter -->
    <div class="flex mb-4 gap-4">
      <input
        v-model="keyword"
        @input="fetchProducts"
        type="text"
        placeholder="Search by name or description"
        class="border p-2 rounded"
      />

      <select v-model="selectedCategory" @change="fetchProducts">
  <option value="">All Categories</option>
  <option v-for="cat in allCategories" :key="cat" :value="cat">{{ cat }}</option>
</select>
    </div>

    <!-- Product Table -->
    <table class="w-full border">
      <thead>
        <tr class="bg-gray-200">
          <th class="p-2 border">ID</th>
          <th class="p-2 border">Name</th>
          <th class="p-2 border">Description</th>
          <th class="p-2 border">Category</th>
          <th class="p-2 border">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="product in products.data" :key="product.id">
          <td class="p-2 border">{{ product.id }}</td>
          <td class="p-2 border">{{ product.name }}</td>
          <td class="p-2 border">{{ product.description }}</td>
          <td class="p-2 border">{{ product.category }}</td>
          <td class="p-2 border">
            <button @click="deleteProduct(product.id)" class="text-red-500">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-4 flex gap-2">
      <button
        v-for="page in products.last_page"
        :key="page"
        @click="fetchProducts(page)"
        :class="{'font-bold': page === products.current_page}"
        class="p-2 border rounded"
      >
        {{ page }}
      </button>
    </div>
  </div>
</template>
