<script setup lang="ts">
import { SliderRange, SliderRoot, SliderThumb, SliderTrack } from 'reka-ui';
import { cn } from '@/lib/utils';

const props = defineProps<{
    class?: string;
    min?: number;
    max?: number;
    step?: number;
}>();

const modelValue = defineModel<number[]>({ default: () => [0, 100] });
</script>

<template>
    <SliderRoot
        v-model="modelValue"
        :min="min ?? 0"
        :max="max ?? 100"
        :step="step ?? 1"
        :class="cn('relative flex w-full touch-none select-none items-center', props.class)"
    >
        <SliderTrack class="relative h-1.5 w-full grow overflow-hidden rounded-full bg-muted">
            <SliderRange class="absolute h-full bg-violet-600" />
        </SliderTrack>
        <SliderThumb
            v-for="(_, i) in modelValue"
            :key="i"
            class="block h-4 w-4 rounded-full border border-violet-600 bg-background shadow transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 cursor-pointer"
        />
    </SliderRoot>
</template>
