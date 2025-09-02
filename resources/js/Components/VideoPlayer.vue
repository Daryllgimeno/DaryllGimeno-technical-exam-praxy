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
    preload: 'auto',
    fluid: true
  });

  if (props.src) player.value.src(props.src);
});

watch(() => props.src, (newSrc) => {
  if (player.value && newSrc) {
    player.value.src(newSrc);
  }
});

onBeforeUnmount(() => {
  if (player.value) {
    player.value.dispose();
  }
});
</script>

<template>
  <video ref="videoRef" class="video-js vjs-big-play-centered"></video>
</template>

<style scoped>
.video-js {
  width: 100%;
  height: auto;
}
</style>
