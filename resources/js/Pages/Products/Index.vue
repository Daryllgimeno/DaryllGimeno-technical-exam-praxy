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

      <select v-model="selectedCategory" @change="fetchProducts" class="border p-2 rounded">
        <option value="">All Categories</option>
        <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
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

<script>
import axios from "axios";

export default {
  data() {
    return {
      products: { data: [], current_page: 1, last_page: 1 },
      keyword: "",
      selectedCategory: "",
      categories: [],
    };
  },
  mounted() {
    this.fetchCategories();
    this.fetchProducts();
  },
  methods: {
    async fetchProducts(page = 1) {
      const response = await axios.get("/api/products", {
        params: {
          keyword: this.keyword,
          category_id: this.selectedCategory,
          page: page,
        },
      });
      this.products = response.data;
    },
    async fetchCategories() {
      const response = await axios.get("/api/categories"); // create API if needed
      this.categories = response.data;
    },
   async deleteProduct(id) {
  if (confirm("Are you sure you want to delete this product?")) {
    try {
      await axios.delete(`/api/products/${id}`);
      this.fetchProducts(this.products.current_page); 
    } catch (error) {
      console.error(error);
      alert("Failed to delete the product.");
    }
  }
}

  },
};
</script>
