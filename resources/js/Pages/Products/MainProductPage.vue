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

// Fetch products with optional category filter
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
  } catch (err) {
    console.error('Failed to fetch products', err)
  } finally {
    loading.value = false
  }
}

// Fetch categories from API
const fetchCategories = async () => {
  try {
    const res = await axios.get('/api/categories')
    allCategories.value = res.data
  } catch (err) {
    console.error('Failed to fetch categories', err)
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

// Modal functions
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

// On mount: fetch products AND categories
onMounted(() => {
  fetchProducts()
  fetchCategories()
})
</script>


<template>
  <AuthenticatedPageLayout>
    <template #default>
      <div class="max-w-6xl mx-auto px-4 py-4">

        <!-- Search, Category & Create Button -->
        <div class="flex flex-wrap items-center gap-2 mb-4">
          <!-- Search input -->
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
              aria-label="Clear search"
            >
              ✕
            </button>
          </div>

         <!-- Category select -->
<select
  v-model="selectedCategory"
  @change="fetchProducts"
  class="border p-2 rounded w-40 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
>
  <option value="">All Categories</option>
  <option v-for="cat in allCategories" :key="cat" :value="cat">{{ cat }}</option>
</select>

          <!-- Create Product button -->
          <Link
            href="/products/create"
            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition text-sm"
          >
            Create Product
          </Link>
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
                <td colspan="7" class="p-4 text-gray-500">No products found.</td>
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

<style>
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

.zoom-enter-active { transition: transform 0.3s; transform: scale(0.8); }
.zoom-enter-to { transform: scale(1); }
.zoom-leave-active { transition: transform 0.3s; transform: scale(0.8); }
</style>
