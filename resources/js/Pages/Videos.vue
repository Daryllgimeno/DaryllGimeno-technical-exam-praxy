<script setup>
import { ref } from 'vue';
import VideoPlayer from '@/Components/VideoPlayer.vue';

const video1 = '/video/video.mp4';
const video2 = '/video/video2.mp4';

const currentVideo = ref(video1);

const chooseVideo = () => {
  currentVideo.value = currentVideo.value === video1 ? video2 : video1;
};
</script>

<template>
<div class="max-w-2xl mx-auto p-6 text-center">

    <h1 class="text-2xl font-bold mb-4">Video Player</h1>

   
    <VideoPlayer :src="currentVideo" />

   
  <button
  @click="chooseVideo"
  class="mt-4 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition"
>
  Switch Video
</button>

  </div>
</template>
