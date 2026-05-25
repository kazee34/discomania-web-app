<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
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

const props = defineProps<{ products: Product[] }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin Panel', href: { url: '/admin/products' } },
];

const search       = ref('');
const filterGenre  = ref('all');
const filterStatus = ref<'all' | 'active' | 'inactive'>('all');
const filterStock  = ref<'all' | 'out'>('all');

const genres = computed(() => {
    const all = props.products.map((p) => p.genre).filter(Boolean) as string[];
    return [...new Set(all)].sort();
});

const filtered = computed(() => {
    const q = search.value.toLowerCase();
    return props.products.filter((p) => {
        const matchesSearch =
            !q ||
            p.albumTitle.toLowerCase().includes(q) ||
            p.artist.toLowerCase().includes(q);
        const matchesGenre  = filterGenre.value === 'all' || p.genre === filterGenre.value;
        const matchesStatus =
            filterStatus.value === 'all' ||
            (filterStatus.value === 'active' && p.isActive) ||
            (filterStatus.value === 'inactive' && !p.isActive);
        const matchesStock = filterStock.value === 'all' || (filterStock.value === 'out' && p.stock === 0);
        return matchesSearch && matchesGenre && matchesStatus && matchesStock;
    });
});

function deleteProduct(id: number) {
    if (!confirm('¿Eliminar este producto? Esta acción no se puede deshacer.')) return;
    useForm({}).delete(`/admin/products/${id}`, { preserveScroll: true });
}

function toggleStatus(id: number, activate: boolean) {
    useForm({ activate }).patch(`/admin/products/${id}/status`, { preserveScroll: true });
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
                    <p class="text-sm text-muted-foreground mt-0.5">{{ filtered.length }} de {{ products.length }} productos</p>
                </div>
                <Button as-child>
                    <Link href="/admin/products/create">Añadir producto</Link>
                </Button>
            </div>

            <!-- Flash -->
            <div v-if="$page.props.flash?.success" class="rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ $page.props.flash.success }}
            </div>

            <!-- Filtros -->
            <div class="flex flex-wrap gap-3">
                <Input v-model="search" placeholder="Buscar por álbum o artista..." class="max-w-xs" />
                <select
                    v-model="filterGenre"
                    class="flex h-9 rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm"
                >
                    <option value="all">Todos (género)</option>
                    <option v-for="g in genres" :key="g" :value="g">{{ g }}</option>
                </select>
                <select
                    v-model="filterStatus"
                    class="flex h-9 rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm"
                >
                    <option value="all">Todos (estado)</option>
                    <option value="active">Activos</option>
                    <option value="inactive">Inactivos</option>
                </select>
                <select
                    v-model="filterStock"
                    class="flex h-9 rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm"
                >
                    <option value="all">Cualquier stock</option>
                    <option value="out">Sin stock</option>
                </select>
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
                        <tr v-if="filtered.length === 0">
                            <td colspan="6" class="px-4 py-8 text-center text-muted-foreground">
                                No se encontraron productos con esos filtros.
                            </td>
                        </tr>
                        <tr v-for="product in filtered" :key="product.id" class="hover:bg-muted/30 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <img
                                        v-if="product.coverImageUrl"
                                        :src="product.coverImageUrl"
                                        alt=""
                                        class="h-10 w-10 rounded-md object-cover shrink-0"
                                        loading="lazy"
                                        decoding="async"
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