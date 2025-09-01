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
      <div class="max-w-6xl mx-auto px-4">

        <!-- Search & Filter -->
        <div class="flex items-center mb-4 gap-2">
          <input
            v-model="keyword"
            @input="fetchProducts"
            type="text"
            placeholder="Search..."
            class="border p-1 rounded text-sm w-64"
          />
          <select
            v-model="selectedCategory"
            @change="fetchProducts"
            class="border p-1 rounded text-sm w-40"
          >
            <option value="">All Categories</option>
            <option v-for="cat in allCategories" :key="cat" :value="cat">{{ cat }}</option>
          </select>
        </div>

        <!-- Product Table -->
        <div class="overflow-x-auto border rounded">
          <table class="w-full border text-sm">
            <thead>
              <tr class="bg-gray-200 text-xs">
                <th class="p-1 border">ID</th>
                <th class="p-1 border">Name</th>
                <th class="p-1 border">Description</th>
                <th class="p-1 border">Category</th>
                <th class="p-1 border">Images</th>
                <th class="p-1 border">Date & Time</th>
                <th class="p-1 border">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="product in products.data" :key="product.id">
                <td class="p-1 border">{{ product.id }}</td>
                <td class="p-1 border">{{ product.name }}</td>
                <td class="p-1 border">{{ product.description }}</td>
                <td class="p-1 border">{{ product.category }}</td>
                <td class="p-1 border flex gap-1">
                  <img
                    v-for="img in product.images"
                    :key="img"
                    :src="`/storage/${img}`"
                    class="w-10 h-10 object-cover border rounded cursor-pointer"
                    @click="openModal(img)"
                  />
                </td>
                <td class="p-1 border">
                  {{ product.date_time ? new Date(product.date_time).toLocaleString() : '-' }}
                </td>
                <td class="p-1 border flex gap-1">
                  <button
                    @click="editProduct(product.id)"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-0.5 rounded text-xs"
                  >
                    Edit
                  </button>
                  <button
                    @click="deleteProduct(product.id)"
                    class="bg-red-500 hover:bg-red-600 text-white px-2 py-0.5 rounded text-xs"
                  >
                    Delete
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="mt-2 flex justify-end">
          <div class="flex gap-1">
            <button
              v-for="page in products.last_page"
              :key="page"
              @click="fetchProducts(page)"
              :class="{ 'font-bold bg-gray-200': page === products.current_page }"
              class="p-1 border rounded text-sm"
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
          <div class="bg-white p-2 rounded relative max-w-3xl w-full">
            <button
              class="absolute top-1 right-1 text-black font-bold px-2 py-1"
              @click="closeModal"
            >
              X
            </button>
            <img :src="modalImage" class="w-full h-auto object-contain" />
          </div>
        </div>

      </div>
    </template>
  </AuthenticatedPageLayout>
</template>
