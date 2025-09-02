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

// Watch for changes in src prop
watch(() => props.src, (newSrc) => {
    if (player.value) {
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
  <div>
    <video ref="videoRef" class="video-js vjs-big-play-centered"></video>
  </div>
</template>

<style scoped>
.video-js {
  width: 100%;
  height: auto;
}
</style>
