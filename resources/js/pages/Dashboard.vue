<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';

interface Stats {
    totalCustomers: number;
    activeCustomers: number;
    totalOrders: number;
    totalProducts: number;
    activeProducts: number;
    revenue: number;
}

defineProps<{ stats: Stats | null }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
];

const quickLinks = [
    { href: '/',       title: 'Inicio' },
    { href: '/about',  title: 'Acerca de nosotros' },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-8 p-6">
            <div>
                <h1 class="text-2xl font-bold">
                    Bienvenido, {{ $page.props.auth.user?.name ?? 'usuario' }}
                </h1>
            </div>

            <!-- Estadísticas admin -->
            <template v-if="stats">
                <div>
                    <h2 class="mb-4 text-lg font-semibold">Resumen general</h2>
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <div class="rounded-xl border bg-card p-5 flex flex-col gap-1">
                            <p class="text-xs text-muted-foreground uppercase tracking-wide">Clientes</p>
                            <p class="text-3xl font-bold">{{ stats.totalCustomers }}</p>
                            <p class="text-xs text-muted-foreground">{{ stats.activeCustomers }} activos</p>
                        </div>
                        <div class="rounded-xl border bg-card p-5 flex flex-col gap-1">
                            <p class="text-xs text-muted-foreground uppercase tracking-wide">Pedidos</p>
                            <p class="text-3xl font-bold">{{ stats.totalOrders }}</p>
                        </div>
                        <div class="rounded-xl border bg-card p-5 flex flex-col gap-1">
                            <p class="text-xs text-muted-foreground uppercase tracking-wide">Ingresos</p>
                            <p class="text-3xl font-bold">{{ stats.revenue.toFixed(2) }} €</p>
                            <p class="text-xs text-muted-foreground">Pedidos no cancelados</p>
                        </div>
                        <div class="rounded-xl border bg-card p-5 flex flex-col gap-1">
                            <p class="text-xs text-muted-foreground uppercase tracking-wide">Productos</p>
                            <p class="text-3xl font-bold">{{ stats.totalProducts }}</p>
                            <p class="text-xs text-muted-foreground">{{ stats.activeProducts }} activos</p>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Accesos rápidos -->
            <div>
                <h2 class="mb-4 text-lg font-semibold">Accesos rápidos</h2>
                <div class="grid gap-4 sm:grid-cols-2">
                    <Link
                        v-for="card in quickLinks"
                        :key="card.href"
                        :href="card.href"
                        class="rounded-xl border bg-card p-5 flex flex-col hover:bg-muted/50 transition-colors"
                    >
                        <p class="font-semibold">{{ card.title }}</p>
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
