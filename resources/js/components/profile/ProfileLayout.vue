<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Lock, Package, Palette, ShieldCheck, UserCog, UserRound } from 'lucide-vue-next';
import ShopNavbar from '@/components/shop/ShopNavbar.vue';
import { getInitials } from '@/composables/useInitials';
import type { User } from '@/types';

const page = usePage();
const user = computed(() => page.props.auth?.user as User | null);

const nav = [
    { label: 'Mi perfil', href: '/profile', icon: UserRound },
    { label: 'Mis pedidos', href: '/profile/orders', icon: Package },
];

const settingsNav = [
    { label: 'Cuenta', href: '/settings/profile', icon: UserCog },
    { label: 'Contraseña', href: '/settings/password', icon: Lock },
    { label: 'Verificación en 2 pasos', href: '/settings/two-factor', icon: ShieldCheck },
    { label: 'Apariencia', href: '/settings/appearance', icon: Palette },
];
</script>

<template>
    <div class="min-h-screen bg-background">
        <ShopNavbar />

        <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6">
            <div class="grid gap-10 md:grid-cols-[240px_1fr]">
                <!-- Sidebar -->
                <aside class="flex flex-col gap-4">
                    <!-- User card -->
                    <div class="flex flex-col items-center gap-3 rounded-2xl bg-linear-to-br from-violet-900 to-black px-5 py-7 text-center text-white">
                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-white/20 text-xl font-bold ring-2 ring-white/30">
                            {{ getInitials(user?.name) }}
                        </div>
                        <div class="min-w-0 w-full">
                            <p class="font-semibold leading-tight truncate">{{ user?.name }}</p>
                            <p class="mt-0.5 text-xs text-white/60 truncate">{{ user?.email }}</p>
                        </div>
                    </div>

                    <!-- Nav -->
                    <nav class="flex flex-col gap-0.5">
                        <Link
                            v-for="item in nav"
                            :key="item.href"
                            :href="item.href"
                            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors"
                            :class="{
                                'bg-violet-50 text-violet-700 dark:bg-violet-900/20 dark:text-violet-300': $page.url === item.href,
                                'text-muted-foreground hover:bg-muted hover:text-foreground': $page.url !== item.href,
                            }"
                        >
                            <component :is="item.icon" class="h-4 w-4 shrink-0" />
                            {{ item.label }}
                        </Link>

                        <p class="mt-5 mb-1.5 px-3 text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                            Configuración
                        </p>

                        <Link
                            v-for="item in settingsNav"
                            :key="item.href"
                            :href="item.href"
                            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors"
                            :class="{
                                'bg-violet-50 text-violet-700 dark:bg-violet-900/20 dark:text-violet-300': $page.url.startsWith(item.href),
                                'text-muted-foreground hover:bg-muted hover:text-foreground': !$page.url.startsWith(item.href),
                            }"
                        >
                            <component :is="item.icon" class="h-4 w-4 shrink-0" />
                            {{ item.label }}
                        </Link>
                    </nav>
                </aside>

                <!-- Content -->
                <main>
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>
