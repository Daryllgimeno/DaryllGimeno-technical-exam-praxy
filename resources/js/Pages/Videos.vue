<script setup>
import { ref } from 'vue';
import VideoPlayer from '@/Components/VideoPlayer.vue';


const videos = [
    '/video/sample1.mp4',
    '/video/sample2.mp4'
];


const currentVideo = ref(videos[0]);

const changeVideo = (video) => {
    currentVideo.value = video;
};
</script>

<template>
  <div>
    <h1 class="text-2xl font-bold mb-4">Video Player</h1>

    <VideoPlayer :src="currentVideo" />

    <div class="mt-4 flex gap-2">
      <button
        v-for="video in videos"
        :key="video"
        @click="changeVideo(video)"
        class="px-3 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition"
      >
        Play {{ video.split('/').pop() }}
      </button>
    </div>
  </div>
</template>
