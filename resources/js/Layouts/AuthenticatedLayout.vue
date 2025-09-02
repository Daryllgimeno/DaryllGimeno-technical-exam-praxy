<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import { Home, Package, Play, User, Settings, LogOut, Plus } from '@iconoir/vue'

const form = useForm({})

const logout = () => {
  form.post(route('logout'))
}
</script>

<template>
  <div class="min-h-screen bg-gray-100 flex">
    <!-- Sidebar -->
  <aside class="flex flex-col justify-between h-screen w-64 bg-slate-800 text-white">



      <div>
        <div class="p-4 text-2xl font-bold text-center border-b border-gray-700">
          Hello, {{ $page.props.auth.user?.name || 'Guest' }}
        </div>

        <nav class="px-4 py-6 space-y-4">
          <!-- Dashboard -->
          <Link href="/dashboard" class="flex items-center px-3 py-2 rounded hover:bg-gray-700 transition">
            <Home class="w-7 h-7 mr-3" />
            <span class="text-lg">Dashboard</span>
          </Link>

          <!-- Product List -->
          <Link href="/products" class="flex items-center px-3 py-2 rounded hover:bg-gray-700 transition">
            <Package class="w-7 h-7 mr-3" />
            <span class="text-lg">Products</span>
          </Link>

          <!-- Create Product -->
          <Link href="/products/create" class="flex items-center px-3 py-2 rounded hover:bg-gray-700 transition">
  <Plus class="w-7 h-7 mr-3" />
  <span class="text-lg">Create Product</span>
</Link>

          <!-- Videos -->
          <Link href="/videos" class="flex items-center px-3 py-2 rounded hover:bg-gray-700 transition">
            <Play class="w-7 h-7 mr-3" />
            <span class="text-lg">Videos</span>
          </Link>

        
       
        </nav>
      </div>

      <!-- Logout Button -->
      <div class="p-4 border-t border-gray-700">
        <button
          @click="logout"
          class="w-full flex items-center px-3 py-2 gap-3 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-lg"
          :disabled="form.processing"
        >
          <LogOut class="w-7 h-7 mr-2" />
          Logout
        </button>
      </div>
    </aside>

    <!-- Main content -->
    <div class="flex-1 flex flex-col">
     

      <!-- Page Content -->
      <main class="flex-1 p-6 bg-gray-100">
        <slot v-if="$slots.default" />
        <div v-else class="text-gray-500 text-center py-10">No content available</div>
      </main>
    </div>
  </div>
</template>
