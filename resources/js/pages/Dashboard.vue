<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
];

const cards = [
    { href: '/shop',           title: 'Tienda',       description: 'Explora nuestro catálogo de discos.' },
    { href: '/profile',        title: 'Mi perfil',    description: 'Gestiona tus datos personales y dirección.' },
    { href: '/profile/orders', title: 'Mis pedidos',  description: 'Consulta el historial y estado de tus compras.' },
    { href: '/cart',           title: 'Carrito',      description: 'Revisa los artículos que tienes pendientes.' },
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
                <p class="mt-1 text-sm text-muted-foreground">¿Qué quieres hacer hoy?</p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <Link
                    v-for="card in cards"
                    :key="card.href"
                    :href="card.href"
                    class="rounded-xl border bg-card p-5 flex flex-col gap-2 hover:bg-muted/50 transition-colors"
                >
                    <p class="font-semibold">{{ card.title }}</p>
                    <p class="text-xs text-muted-foreground">{{ card.description }}</p>
                </Link>
            </div>
        </div>
    </AppLayout>
</template>