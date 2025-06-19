<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <div class="bg-slate-200 p-2 rounded">
        <img src="/logo.svg" class="w-full h-full"/>
    </div>
</template>
