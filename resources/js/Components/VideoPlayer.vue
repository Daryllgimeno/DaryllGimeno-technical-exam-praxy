<script setup>
import { ref, watch, onMounted, onBeforeUnmount } from 'vue';
import videojs from 'video.js';
import 'video.js/dist/video-js.css';

const props = defineProps({
    src: {
        type: String,
        required: true
    }
});

let player = ref(null);
const videoRef = ref(null);

onMounted(() => {
    player.value = videojs(videoRef.value, {
        controls: true,
        autoplay: false,
        preload: 'auto',
        fluid: true
    });

    player.value.src(props.src);
});

watch(() => props.src, (newSrc) => {
    if (player.value) {
        player.value.src(newSrc);
        player.value.play();
    }
});

onBeforeUnmount(() => {
    if (player.value) player.value.dispose();
});
</script>

<template>
  <div class="rounded-lg overflow-hidden shadow-lg max-w-full">
    <video
      ref="videoRef"
      class="video-js vjs-big-play-centered rounded-lg"
    ></video>
  </div>
</template>

<style scoped>
.video-js {
  width: 100%;
  height: auto;
  max-height: 400px; 
}
</style>
