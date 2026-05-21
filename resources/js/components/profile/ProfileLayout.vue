<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import ShopNavbar from '@/components/shop/ShopNavbar.vue';

const nav = [
    { label: 'Datos personales', href: '/profile' },
    { label: 'Mis pedidos', href: '/profile/orders' },
];
</script>

<template>
    <div class="min-h-screen bg-background">
        <ShopNavbar />

        <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6">
            <div class="grid gap-8 md:grid-cols-4">
                <!-- Sidebar -->
                <aside class="md:col-span-1">
                    <nav class="flex flex-col gap-1">
                        <Link
                            v-for="item in nav"
                            :key="item.href"
                            :href="item.href"
                            class="rounded-lg px-3 py-2 text-sm font-medium transition-colors hover:bg-muted"
                            :class="{
                                'bg-muted text-foreground': $page.url.startsWith(item.href),
                                'text-muted-foreground': !$page.url.startsWith(item.href),
                            }"
                        >
                            {{ item.label }}
                        </Link>
                    </nav>
                </aside>

                <!-- Content -->
                <main class="md:col-span-3">
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>