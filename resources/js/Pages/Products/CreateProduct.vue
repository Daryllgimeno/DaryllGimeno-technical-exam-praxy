<template>
  <div class="p-6 max-w-3xl mx-auto">
    <h1 class="text-xl font-bold mb-4">Create Product</h1>

   
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
      </div>

      <button @click="nextStep" class="bg-blue-500 text-white px-4 py-2 rounded">
        Next
      </button>
    </div>


  </div>
</template>

<script>
export default {
  props: {
    categories: Array,   
  },
  data() {
    return {
      step: 1,
      form: {
        name: '',
        category: '',
        description: '',
        date_time: ''
      },
      errors: {}
    };
  },
  methods: {
    nextStep() {
      this.errors = {};
      if (!this.form.name) this.errors.name = "Name is required";
      if (!this.form.category) this.errors.category = "Category is required";

      if (Object.keys(this.errors).length === 0) {
        this.step = 2; 
      }
    }
  }
};

</script>
