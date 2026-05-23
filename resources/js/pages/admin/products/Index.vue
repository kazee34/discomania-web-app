<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

interface Product {
    id: number;
    artist: string;
    albumTitle: string;
    price: number;
    stock: number;
    slug: string;
    genre: string | null;
    coverImageUrl: string | null;
    isActive: boolean;
}

defineProps<{ products: Product[] }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin Panel', href: { url: '/admin/products' } },
];

function deleteProduct(id: number) {
    if (!confirm('¿Eliminar este producto? Esta acción no se puede deshacer.')) return;
    useForm({}).delete(`/admin/products/${id}`, {
        preserveScroll: true,
    });
}

function toggleStatus(id: number, activate: boolean) {
    useForm({ activate }).patch(`/admin/products/${id}/status`, {
        preserveScroll: true,
    });
}
</script>

<template>
    <Head title="Admin Panel — Productos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Productos</h1>
                    <p class="text-sm text-muted-foreground mt-0.5">{{ products.length }} productos en total</p>
                </div>
                <Button as-child>
                    <Link href="/admin/products/create">Añadir producto</Link>
                </Button>
            </div>

            <!-- Flash -->
            <div v-if="$page.props.flash?.success" class="rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ $page.props.flash.success }}
            </div>

            <!-- Tabla -->
            <div class="rounded-xl border overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-muted/50 text-xs uppercase tracking-wide text-muted-foreground">
                        <tr>
                            <th class="px-4 py-3 text-left">Producto</th>
                            <th class="px-4 py-3 text-left hidden sm:table-cell">Género</th>
                            <th class="px-4 py-3 text-right">Precio</th>
                            <th class="px-4 py-3 text-right">Stock</th>
                            <th class="px-4 py-3 text-center">Estado</th>
                            <th class="px-4 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr v-for="product in products" :key="product.id" class="hover:bg-muted/30 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <img
                                        v-if="product.coverImageUrl"
                                        :src="product.coverImageUrl"
                                        :alt="product.albumTitle"
                                        class="h-10 w-10 rounded-md object-cover shrink-0"
                                    />
                                    <div v-else class="h-10 w-10 rounded-md bg-muted shrink-0" />
                                    <div class="min-w-0">
                                        <p class="font-medium truncate">{{ product.albumTitle }}</p>
                                        <p class="text-xs text-muted-foreground truncate">{{ product.artist }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 hidden sm:table-cell text-muted-foreground">{{ product.genre ?? '—' }}</td>
                            <td class="px-4 py-3 text-right font-medium">{{ product.price.toFixed(2) }} €</td>
                            <td class="px-4 py-3 text-right" :class="product.stock === 0 ? 'text-destructive' : ''">
                                {{ product.stock }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span
                                    class="rounded-full px-2.5 py-0.5 text-xs font-medium"
                                    :class="product.isActive
                                        ? 'bg-green-100 text-green-800'
                                        : 'bg-muted text-muted-foreground'"
                                >
                                    {{ product.isActive ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <Button as-child size="sm" variant="outline">
                                        <Link :href="`/admin/products/${product.id}/edit`">Editar</Link>
                                    </Button>
                                    <Button
                                        size="sm"
                                        :variant="product.isActive ? 'outline' : 'default'"
                                        @click="toggleStatus(product.id, !product.isActive)"
                                    >
                                        {{ product.isActive ? 'Desactivar' : 'Activar' }}
                                    </Button>
                                    <Button
                                        size="sm"
                                        variant="destructive"
                                        @click="deleteProduct(product.id)"
                                    >
                                        Eliminar
                                    </Button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>