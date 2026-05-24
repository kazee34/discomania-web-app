<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import ProfileLayout from '@/components/profile/ProfileLayout.vue';
</script>

<template>
    <Head title="Apariencia" />

    <ProfileLayout>
        <div class="flex flex-col gap-8">
            <div>
                <h1 class="text-2xl font-bold">Apariencia</h1>
                <p class="mt-1 text-sm text-muted-foreground">
                    Personaliza el aspecto visual de la aplicación.
                </p>
            </div>

            <section class="rounded-xl border bg-card p-5">
                <AppearanceTabs />
            </section>
        </div>
    </ProfileLayout>
</template>
