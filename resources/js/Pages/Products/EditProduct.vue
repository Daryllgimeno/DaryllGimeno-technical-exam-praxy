<template>
  <AuthenticatedLayout>
    <div class="p-6 max-w-3xl mx-auto">
      <!-- Heading -->
      <h1 class="text-xl font-bold mb-6 mt-2">Edit Product</h1>

      <!-- Step Indicator -->
      <ol class="flex flex-col sm:flex-row sm:justify-between items-center w-full text-sm font-medium text-center sm:text-base mb-6">
        <!-- Step 1 -->
        <li class="flex-1 flex flex-col sm:flex-row sm:items-center mb-4 sm:mb-0">
          <div class="flex items-center w-full sm:w-auto">
            <div
              class="w-8 h-8 rounded-full flex items-center justify-center z-10 flex-shrink-0"
              :class="{'bg-blue-500 text-white': step >= 1,'bg-gray-300 text-gray-600': step < 1}"
            >
              1
            </div>
            <div class="hidden sm:block flex-1 h-1 bg-gray-300" :class="{'bg-blue-500': step > 1}"></div>
          </div>
          <div class="mt-2 sm:mt-0 sm:ml-2 text-center sm:text-left text-gray-500" :class="{'text-blue-500 font-semibold': step === 1}">
            Product Details
          </div>
        </li>

        <!-- Step 2 -->
        <li class="flex-1 flex flex-col sm:flex-row sm:items-center mb-4 sm:mb-0">
          <div class="flex items-center w-full sm:w-auto">
            <div
              class="w-8 h-8 rounded-full flex items-center justify-center z-10 flex-shrink-0"
              :class="{'bg-blue-500 text-white': step >= 2,'bg-gray-300 text-gray-600': step < 2}"
            >
              2
            </div>
            <div class="hidden sm:block flex-1 h-1 bg-gray-300" :class="{'bg-blue-500': step > 2}"></div>
          </div>
          <div class="mt-2 sm:mt-0 sm:ml-2 text-center sm:text-left text-gray-500" :class="{'text-blue-500 font-semibold': step === 2}">
            Images
          </div>
        </li>

        <!-- Step 3 -->
        <li class="flex-1 flex flex-col sm:flex-row sm:items-center">
          <div class="flex items-center w-full sm:w-auto">
            <div
              class="w-8 h-8 rounded-full flex items-center justify-center z-10 flex-shrink-0"
              :class="{'bg-blue-500 text-white': step >= 3,'bg-gray-300 text-gray-600': step < 3}"
            >
              3
            </div>
          </div>
          <div class="mt-2 sm:mt-0 sm:ml-2 text-center sm:text-left text-gray-500" :class="{'text-blue-500 font-semibold': step === 3}">
            Date & Time
          </div>
        </li>
      </ol>

      <!-- Step 1 -->
      <div v-if="step === 1">
        <div class="mb-4">
          <label class="block font-semibold">Name</label>
          <input v-model="form.name" type="text" class="border rounded p-2 w-full" />
          <span v-if="errors.name" class="text-red-500 text-sm">{{ errors.name }}</span>
        </div>

        <div class="mb-4">
          <label class="block font-semibold">Category</label>
          <select v-model="form.category" class="border rounded p-2 w-full">
            <option disabled value="">Select a category</option>
            <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
          </select>
          <span v-if="errors.category" class="text-red-500 text-sm">{{ errors.category }}</span>
        </div>

        <div class="mb-4">
          <label class="block font-semibold">Description</label>
          <textarea v-model="form.description" class="border rounded p-2 w-full"></textarea>
          <span v-if="errors.description" class="text-red-500 text-sm">{{ errors.description }}</span>
        </div>

        <button @click="nextStep" class="bg-blue-500 text-white px-4 py-2 rounded">Next</button>
      </div>

      <!-- Step 2 -->
      <div v-if="step === 2">
        <div class="mb-4">
          <label class="block font-semibold">Images</label>
          <input type="file" multiple @change="handleFiles" class="border rounded p-2 w-full" />
          <p class="text-sm text-gray-500 mt-1">You can add or select multiple pictures.</p>
          <span v-if="errors.images" class="text-red-500 text-sm">{{ errors.images }}</span>
        </div>

        <div class="flex gap-2 flex-wrap mb-4">
          <div
            v-for="(img, i) in previews"
            :key="i"
            class="w-24 h-24 border rounded overflow-hidden relative"
          >
            <img :src="img.src" class="object-cover w-full h-full" />
            <button @click="removeImage(i)" class="absolute top-0 right-0 bg-red-500 text-white rounded-full px-1">x</button>
          </div>
        </div>

        <button @click="prevStep" class="bg-gray-400 text-white px-4 py-2 rounded mr-2">Back</button>
        <button @click="nextStep" class="bg-blue-500 text-white px-4 py-2 rounded">Next</button>
      </div>

      <!-- Step 3 -->
      <div v-if="step === 3">
        <div class="mb-4">
          <label class="block font-semibold">Date and Time</label>
          <input v-model="form.date_time" type="datetime-local" class="border rounded p-2 w-full" />
        </div>

        <button @click="prevStep" class="bg-gray-400 text-white px-4 py-2 rounded mr-2">Back</button>
        <button @click="submitForm" class="bg-green-500 text-white px-4 py-2 rounded">Save Changes</button>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script>
import axios from "axios";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

export default {
  props: { categories: Array, product: Object },
  components: { AuthenticatedLayout },
  data() {
    return {
      step: 1,
      form: {
        name: this.product.name || "",
        category: this.product.category || "",
        description: this.product.description || "",
        date_time: this.product.date_time || "",
        images: [],
      },
      previews: this.product.images
        ? this.product.images.map(img => ({ src: `/storage/${img}`, existing: true, path: img }))
        : [],
      removedImages: [],
      errors: {},
    };
  },
  methods: {
    nextStep() {
      this.errors = {};
      if (this.step === 1) {
        if (!this.form.name) this.errors.name = "Name is required";
        if (!this.form.category) this.errors.category = "Category is required";
        if (!this.form.description) this.errors.description = "Description is required";
        if (Object.keys(this.errors).length === 0) this.step = 2;
      } else if (this.step === 2) {
        if (this.previews.length === 0) this.errors.images = "At least one image is required";
        else this.step = 3;
      }
    },
    prevStep() { this.step -= 1 },
    handleFiles(e) {
      const newFiles = Array.from(e.target.files);
      this.form.images = [...this.form.images, ...newFiles];
      const newPreviews = newFiles.map(file => ({ src: URL.createObjectURL(file), existing: false, file }));
      this.previews = [...this.previews, ...newPreviews];
    },
    removeImage(index) {
      const img = this.previews[index];
      if (img.existing) this.removedImages.push(img.path);
      else {
        const fileIndex = this.form.images.findIndex(f => f === img.file);
        if (fileIndex !== -1) this.form.images.splice(fileIndex, 1);
      }
      this.previews.splice(index, 1);
    },
  async submitForm() {
  const formData = new FormData();
  formData.append("name", this.form.name);
  formData.append("category", this.form.category);
  formData.append("description", this.form.description);
  formData.append("date_time", this.form.date_time);
  this.form.images.forEach(img => formData.append("images[]", img));
  this.removedImages.forEach(img => formData.append("removed_images[]", img));
  const savedImages = this.previews.filter(p => p.existing).map(p => p.path);
  savedImages.forEach(img => formData.append("saved_images[]", img));

  axios.post(`/api/products/${this.product.id}?_method=PUT`, formData, {
    headers: { "Content-Type": "multipart/form-data" },
  })
  .then(() => {
    alert("Product updated successfully!");
    this.step = 1;
  })
  .catch(error => {
    if (error.response && error.response.data.errors) {
      this.errors = error.response.data.errors;
    } else {
      console.error(error);
    }
  });
}


  },
};
</script>


