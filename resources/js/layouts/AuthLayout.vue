<script setup lang="ts">
import AuthLayout from '@/layouts/auth/AuthSimpleLayout.vue';

defineProps<{
    title?: string;
    description?: string;
    maxWidth?: string;
}>();
</script>

<template>
    <AuthLayout :title="title" :description="description" :max-width="maxWidth">
        <slot />
    </AuthLayout>
</template>
