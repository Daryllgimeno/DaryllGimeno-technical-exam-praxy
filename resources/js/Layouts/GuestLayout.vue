<script setup>
import { Link } from '@inertiajs/vue3';


const backgroundColor = 'rgb(240, 248, 255)';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center justify-center pt-6 sm:pt-0"
        :style="{ backgroundColor: backgroundColor }"
    >
        <div
            class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>
