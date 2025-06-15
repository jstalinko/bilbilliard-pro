<template>
  <span class="font-mono font-bold text-slate-600">
    {{ duration }}
  </span>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted, watch } from 'vue';

const props = defineProps<{
  startTime: string
}>();

const duration = ref('');

const calculateDuration = (startTime: string): string => {
  const start = new Date(startTime);
  const now = new Date();

  const diffMs = now.getTime() - start.getTime();
  const totalSeconds = Math.floor(diffMs / 1000);

  const hours = Math.floor(totalSeconds / 3600).toString().padStart(2, '0');
  const minutes = Math.floor((totalSeconds % 3600) / 60).toString().padStart(2, '0');
  const seconds = (totalSeconds % 60).toString().padStart(2, '0');

  return `${hours}:${minutes}:${seconds}`;
};


let interval: number;

const update = () => {
  duration.value = calculateDuration(props.startTime);
};

onMounted(() => {
  update();
  interval = setInterval(update, 1000);
});

onUnmounted(() => {
  clearInterval(interval);
});

watch(() => props.startTime, update);
</script>
