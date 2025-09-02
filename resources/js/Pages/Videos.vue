<script setup>
import { ref } from 'vue';
import VideoPlayer from '@/Components/VideoPlayer.vue';

const currentVideo = ref('/video/video.mp4');
</script>

<template>
  <div class="max-w-2xl mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4 text-center">Video Player</h1>

 
    <VideoPlayer :src="currentVideo" />
  </div>
</template>
