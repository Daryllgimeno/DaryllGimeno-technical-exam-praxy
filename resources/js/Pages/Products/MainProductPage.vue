<script setup>
import AuthenticatedPageLayout from '@/Layouts/AuthenticatedLayout.vue'
import { ref, onMounted, onBeforeUnmount } from 'vue'
import axios from 'axios'
import { router } from '@inertiajs/vue3'

// Reactive state
const products = ref({ data: [], current_page: 1, last_page: 1 })
const selectedCategory = ref('')
const keyword = ref('')
const allCategories = ref([])

// Modal state
const showModal = ref(false)
const modalImage = ref('')

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

// Open modal
const openModal = (img) => {
  modalImage.value = `/storage/${img}`
  showModal.value = true
}

// Close modal
const closeModal = () => {
  showModal.value = false
}

// Close modal on Esc
const handleEsc = (e) => {
  if (e.key === 'Escape') closeModal()
}

// Add/remove event listener
onMounted(() => {
  fetchProducts()
  window.addEventListener('keydown', handleEsc)
})
onBeforeUnmount(() => {
  window.removeEventListener('keydown', handleEsc)
})
</script>

<template>
  <AuthenticatedPageLayout>
    <template #default>
      <div class="max-w-6xl mx-auto px-4 py-4">

        <!-- Search & Filter -->
        <div class="flex items-center gap-2 mb-4">
          <input
            v-model="keyword"
            @input="fetchProducts"
            type="text"
            placeholder="Search by name..."
            class="border p-2 rounded w-64 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
          />
          <select
            v-model="selectedCategory"
            @change="fetchProducts"
            class="border p-2 rounded w-40 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
          >
            <option value="">All Categories</option>
            <option v-for="cat in allCategories" :key="cat" :value="cat">{{ cat }}</option>
          </select>
        </div>

        <!-- Product Table -->
        <div class="overflow-x-auto border rounded shadow-sm">
          <table class="w-full border text-sm">
            <thead class="bg-gray-200 sticky top-0">
              <tr class="text-xs">
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
              <tr v-for="product in products.data" :key="product.id" class="hover:bg-gray-50 transition">
                <td class="p-2 border">{{ product.id }}</td>
                <td class="p-2 border">{{ product.name }}</td>
                <td class="p-2 border">{{ product.description }}</td>
                <td class="p-2 border">{{ product.category }}</td>
                <td class="p-2 border flex gap-1">
                  <img
                    v-for="img in product.images"
                    :key="img"
                    :src="`/storage/${img}`"
                    class="w-12 h-12 object-cover border rounded cursor-pointer hover:shadow-lg transition duration-200"
                    @click="openModal(img)"
                  />
                </td>
                <td class="p-2 border">
                  {{ product.date_time ? new Date(product.date_time).toLocaleString() : '-' }}
                </td>
                <td class="p-2 border flex gap-1">
                  <button
                    @click="editProduct(product.id)"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs hover:shadow-md transition duration-200"
                  >
                    Edit
                  </button>
                  <button
                    @click="deleteProduct(product.id)"
                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs hover:shadow-md transition duration-200"
                  >
                    Delete
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3 flex justify-end">
          <div class="flex gap-1">
            <button
              v-for="page in products.last_page"
              :key="page"
              @click="fetchProducts(page)"
              :class="page === products.current_page ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-blue-100'"
              class="p-2 rounded text-sm transition duration-200"
            >
              {{ page }}
            </button>
          </div>
        </div>

        <!-- Image Modal -->
        <div
          v-if="showModal"
          class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
          @click.self="closeModal"
        >
          <div class="bg-white p-4 rounded-lg shadow-lg max-w-3xl w-full relative">
            <button
              class="absolute top-2 right-2 text-black font-bold hover:text-red-500 transition duration-200"
              @click="closeModal"
            >
              ✕
            </button>
            <img :src="modalImage" class="w-full h-auto object-contain rounded" />
          </div>
        </div>

      </div>
    </template>
  </AuthenticatedPageLayout>
</template>
