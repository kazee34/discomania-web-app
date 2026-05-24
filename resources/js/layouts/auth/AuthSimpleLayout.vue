<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
    maxWidth?: string;
}>();
</script>

<template>
    <div class="relative flex min-h-svh flex-col items-center justify-center overflow-hidden bg-black p-6 md:p-10">
        <!-- Decorative blobs -->
        <div class="pointer-events-none absolute -top-32 -left-32 h-96 w-96 rounded-full bg-violet-700/20 blur-3xl" />
        <div class="pointer-events-none absolute -bottom-24 right-0 h-80 w-80 rounded-full bg-violet-500/15 blur-3xl" />
        <div class="pointer-events-none absolute top-1/2 left-1/2 h-64 w-64 -translate-x-1/2 -translate-y-1/2 rounded-full bg-violet-800/10 blur-2xl" />

        <div :class="['relative w-full', maxWidth ?? 'max-w-sm']">
            <!-- Logo -->
            <div class="mb-8 flex justify-center">
                <Link :href="home()" class="text-2xl font-bold tracking-tight text-white">
                    Discomania
                </Link>
            </div>

            <!-- Card -->
            <div class="rounded-2xl border border-white/10 bg-card p-8 shadow-2xl">
                <div class="flex flex-col gap-6">
                    <div class="space-y-1">
                        <h1 class="text-xl font-semibold">{{ title }}</h1>
                        <p class="text-sm text-muted-foreground">{{ description }}</p>
                    </div>
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
