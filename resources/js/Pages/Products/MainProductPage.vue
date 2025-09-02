<script setup>
import AuthenticatedPageLayout from '@/Layouts/AuthenticatedLayout.vue'
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { router } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'

// Reactive state
const products = ref({ data: [], current_page: 1, last_page: 1 })
const selectedCategory = ref('')
const keyword = ref('')
const allCategories = ref([])
const loading = ref(false)

// Modal state
const showModal = ref(false)
const modalImage = ref('')
const modalAlt = ref('')

// Bulk mode & selection
const bulkMode = ref(false)
const selectedProducts = ref([])
const selectAll = ref(false)

// Fetch products
const fetchProducts = async (page = 1) => {
  loading.value = true
  try {
    const res = await axios.get('/api/products', {
      params: {
        keyword: keyword.value,
        category: selectedCategory.value,
        page
      }
    })
    products.value = res.data

    // If select all is active, keep checkboxes selected
    if (bulkMode.value && selectAll.value) {
      selectedProducts.value = products.value.data.map(p => p.id)
    }
  } catch (err) {
    console.error('Failed to fetch products', err)
  } finally {
    loading.value = false
  }
}

// Fetch categories
const fetchCategories = async () => {
  try {
    const res = await axios.get('/api/categories')
    allCategories.value = res.data
  } catch (err) {
    console.error('Failed to fetch categories', err)
  }
}

// Delete a single product
const deleteProduct = async (id) => {
  if (!confirm("Are you sure you want to delete this product?")) return
  await axios.delete(`/api/products/${id}`)
  fetchProducts(products.value.current_page)
}

// Bulk delete
const bulkDelete = async () => {
  if (!selectedProducts.value.length) return alert("No products selected")
  if (!confirm(`Are you sure you want to delete ${selectedProducts.value.length} products?`)) return

  try {
    await axios.post('/api/products/bulk-delete', { ids: selectedProducts.value })
    alert('Selected products deleted successfully!')
    selectedProducts.value = []
    selectAll.value = false
    fetchProducts(products.value.current_page)
  } catch (err) {
    console.error('Failed to delete products', err)
  }
}

// Edit product
const editProduct = (id) => {
  router.get(`/products/${id}/edit`)
}

// Modal
const openModal = (img, name) => {
  modalImage.value = `/storage/${img}`
  modalAlt.value = name
  showModal.value = true
}
const closeModal = () => showModal.value = false

// Clear search
const clearSearch = () => {
  keyword.value = ''
  fetchProducts()
}

// Select all toggle
const toggleSelectAll = () => {
  if (selectAll.value) {
    selectedProducts.value = products.value.data.map(p => p.id)
  } else {
    selectedProducts.value = []
  }
}

// On mount
onMounted(() => {
  fetchProducts()
  fetchCategories()
})
</script>

<template>
<AuthenticatedPageLayout>
  <template #default>
    <div class="max-w-6xl mx-auto px-4 py-4">

      <!-- Search, Category & Buttons -->
      <div class="flex flex-wrap items-center gap-2 mb-4">
        <div class="relative">
          <input
            v-model="keyword"
            @input="fetchProducts"
            type="text"
            placeholder="Search by name..."
            class="border p-2 rounded w-64 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
          />
          <button
            v-if="keyword"
            @click="clearSearch"
            class="absolute right-1 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 px-1"
          >
            ✕
          </button>
        </div>

        <select
          v-model="selectedCategory"
          @change="fetchProducts"
          class="border p-2 rounded w-40 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
        >
          <option value="">All Categories</option>
          <option v-for="cat in allCategories" :key="cat" :value="cat">{{ cat }}</option>
        </select>

        <Link
          href="/products/create"
          class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition text-sm"
        >
          Create Product
        </Link>

        <!-- Toggle bulk mode -->
        <button
          @click="bulkMode = !bulkMode"
          class="bg-red-500 text-white p-2 rounded hover:bg-red-600 transition"
          :title="bulkMode ? 'Cancel Bulk Delete' : 'Bulk Delete'"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M6 2a1 1 0 011-1h6a1 1 0 011 1v1h5a1 1 0 110 2h-1v12a2 2 0 01-2 2H4a2 2 0 01-2-2V5H1a1 1 0 110-2h5V2zm2 3a1 1 0 012 0v8a1 1 0 11-2 0V5zm4 0a1 1 0 10-2 0v8a1 1 0 102 0V5z" clip-rule="evenodd" />
          </svg>
        </button>

        <!-- Delete selected button -->
        <button
          v-if="bulkMode"
          @click="bulkDelete"
          :disabled="!selectedProducts.length"
          class="bg-red-500 text-white p-2 rounded hover:bg-red-600 transition disabled:opacity-50"
          title="Delete Selected Products"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M6 2a1 1 0 011-1h6a1 1 0 011 1v1h5a1 1 0 110 2h-1v12a2 2 0 01-2 2H4a2 2 0 01-2-2V5H1a1 1 0 110-2h5V2zm2 3a1 1 0 012 0v8a1 1 0 11-2 0V5zm4 0a1 1 0 10-2 0v8a1 1 0 102 0V5z" clip-rule="evenodd" />
          </svg>
        </button>
      </div>

      <!-- Loading Spinner -->
      <div v-if="loading" class="flex justify-center py-4">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
      </div>

      <!-- Product Table -->
      <div class="overflow-x-auto border rounded shadow-sm">
        <table class="w-full border text-sm">
          <thead class="bg-gray-200 sticky top-0 z-10">
            <tr class="text-xs">
              <th class="p-2 border" v-if="bulkMode">
                <input type="checkbox" v-model="selectAll" @change="toggleSelectAll" />
              </th>
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
            <tr v-for="(product, index) in products.data" :key="product.id"
                :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50'"
                class="hover:bg-gray-100 transition duration-200">
              <td class="p-2 border text-center" v-if="bulkMode">
                <input type="checkbox" :value="product.id" v-model="selectedProducts" />
              </td>
              <td class="p-2 border">{{ product.id }}</td>
              <td class="p-2 border">{{ product.name }}</td>
              <td class="p-2 border break-words max-w-xs">{{ product.description }}</td>
              <td class="p-2 border">{{ product.category }}</td>
              <td class="p-2 border flex gap-1">
                <img
                  v-for="img in product.images"
                  :key="img"
                  :src="`/storage/${img}`"
                  :alt="product.name"
                  loading="lazy"
                  class="w-12 h-12 object-cover border rounded cursor-pointer hover:scale-110 hover:shadow-lg transition-transform duration-200"
                  @click="openModal(img, product.name)"
                />
              </td>
              <td class="p-2 border">{{ product.date_time ? new Date(product.date_time).toLocaleString() : '-' }}</td>
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
            <tr v-if="!products.data.length && !loading" class="text-center">
              <td colspan="8" class="p-4 text-gray-500">No products found.</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="mt-3 flex justify-between items-center">
        <div class="flex gap-1">
          <button
            :disabled="products.current_page === 1"
            @click="fetchProducts(products.current_page - 1)"
            class="p-2 rounded text-sm bg-gray-100 text-gray-700 hover:bg-blue-100 disabled:opacity-50 transition"
          >
            Prev
          </button>
          <button
            v-for="page in products.last_page"
            :key="page"
            @click="fetchProducts(page)"
            :class="page === products.current_page ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-blue-100'"
            class="p-2 rounded text-sm transition duration-200"
          >
            {{ page }}
          </button>
          <button
            :disabled="products.current_page === products.last_page"
            @click="fetchProducts(products.current_page + 1)"
            class="p-2 rounded text-sm bg-gray-100 text-gray-700 hover:bg-blue-100 disabled:opacity-50 transition"
          >
            Next
          </button>
        </div>
        <div class="text-sm text-gray-600">
          Page {{ products.current_page }} of {{ products.last_page }}
        </div>
      </div>

      <!-- Image Modal -->
      <transition name="fade">
        <div
          v-if="showModal"
          class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
          @click.self="closeModal"
        >
          <transition name="zoom">
            <div class="bg-white p-4 rounded-lg shadow-lg max-w-3xl w-full relative">
              <button
                class="absolute top-2 right-2 text-black font-bold hover:text-red-500 transition duration-200"
                @click="closeModal"
                aria-label="Close image"
              >
                ✕
              </button>
              <div class="text-center mb-2 font-semibold">{{ modalAlt }}</div>
              <img :src="modalImage" class="w-full h-auto object-contain rounded" />
            </div>
          </transition>
        </div>
      </transition>

    </div>
  </template>
</AuthenticatedPageLayout>
</template>
