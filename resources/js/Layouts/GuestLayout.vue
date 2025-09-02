<script setup>

const backgroundColor = 'rgb(217, 252, 249)';
</script>

<template>
  <div
    class="flex min-h-screen flex-col items-center justify-center p-6"
    :style="{ backgroundColor: backgroundColor }"
  >
    
    <div
      class="mt-6 w-full overflow-hidden px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg"
      :style="{ backgroundColor: 'rgba(255, 255, 255, 0.85)' }"
    >
      <slot />
    </div>
  </div>
</template>
