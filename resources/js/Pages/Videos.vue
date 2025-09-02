<script setup>
import { ref } from 'vue';
import VideoPlayer from '@/Components/VideoPlayer.vue';

const videos = [
    '/video/video.mp4',
];

const currentVideo = ref(videos[0]);

const changeVideo = (video) => {
    currentVideo.value = video;
};
</script>

<template>
  <div class="max-w-2xl mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4 text-center">Video Player</h1>

    <!-- Smaller Video Player -->
    <VideoPlayer :src="currentVideo" />

    <!-- Buttons -->
    <div class="mt-4 flex flex-wrap justify-center gap-3">
      <button
        v-for="video in videos"
        :key="video"
        @click="changeVideo(video)"
        class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600 transition"
      >
        Play {{ video.split('/').pop() }}
      </button>
    </div>
  </div>
</template>
