<script setup>
import { Link, useForm } from '@inertiajs/vue3'

const form = useForm({})

const logout = () => {
  form.post(route('logout'))
}
</script>

<template>
  <div class="min-h-screen bg-gray-100 flex">
    <!-- Sidebar -->
    <aside class="flex flex-col justify-between h-screen w-60 bg-gray-800 text-white">
      <div>
        <div class="p-4 text-2xl font-bold text-center border-b border-gray-700">
   
            Hello, {{ $page.props.auth.user?.name || 'Guest' }}
        
        </div>
        <nav class="px-4 py-6 space-y-2">
          <Link href="/dashboard" class="block px-3 py-2 rounded hover:bg-gray-700">Dashboard</Link>
          <Link href="/products" class="block px-3 py-2 rounded hover:bg-gray-700">Products</Link>
          <Link href="/videeos" class="block px-3 py-2 rounded hover:bg-gray-700">Videos</Link>
          <Link href="/users" class="block px-3 py-2 rounded hover:bg-gray-700">Users</Link>
          <Link href="/settings" class="block px-3 py-2 rounded hover:bg-gray-700">Settings</Link>
        </nav>
      </div>


    <div class="p-4 border-t border-gray-700">
  <button
    @click="logout"
    class="w-full flex items-center gap-2 px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition"
    :disabled="form.processing"
  >
   
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
      viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round"
        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 
           01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 
           2 0 012 2v1" />
    </svg>
    Logout
  </button>
</div>

    </aside>

    <!-- Main content -->
    <div class="flex-1 flex flex-col">
      <!-- Top Navbar -->
      <header class="bg-white shadow flex items-center justify-between px-6 py-4">
        <h1 class="text-lg font-semibold">Product Management System</h1>
        <div class="flex items-center space-x-4">
     
        </div>
      </header>

      <!-- Page Content -->
      <main class="flex-1 p-6 bg-gray-100">
      
        <slot v-if="$slots.default" />
        <div v-else class="text-gray-500 text-center py-10">No content available</div>
      </main>
    </div>
  </div>
</template>
