<script setup lang="ts">
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useCart } from '@/composables/useCart';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';

interface Product {
    id: number;
    slug: string;
    artist: string;
    albumTitle: string;
    price: number;
    genre: string;
    releaseYear: number;
    coverImageUrl: string;
}

const props = defineProps<{ product: Product }>();

const { loading, addItem } = useCart();
const added = ref(false);

async function handleAddToCart() {
    await addItem(props.product.id, props.product.price);
    added.value = true;
    setTimeout(() => (added.value = false), 1500);
}
</script>

<template>
    <div class="group flex flex-col overflow-hidden rounded-xl border bg-card shadow-sm transition-shadow hover:shadow-md">
        <!-- Portada -->
        <Link :href="`/shop/${product.slug}`" class="relative aspect-square overflow-hidden bg-muted block">
            <img
                v-if="product.coverImageUrl"
                :src="product.coverImageUrl"
                :alt="`${product.artist} - ${product.albumTitle}`"
                class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
            />
            <div v-else class="flex h-full items-center justify-center bg-muted" />
        </Link>

        <!-- Info -->
        <div class="flex flex-1 flex-col gap-2 p-3">
            <Link :href="`/shop/${product.slug}`">
                <p class="truncate text-sm font-semibold leading-tight hover:underline">{{ product.albumTitle }}</p>
                <p class="truncate text-xs text-muted-foreground">{{ product.artist }}</p>
            </Link>
            <div class="flex items-center justify-between gap-1">
                <Badge variant="secondary" class="truncate text-xs">{{ product.genre }}</Badge>
                <span class="text-xs text-muted-foreground">{{ product.releaseYear }}</span>
            </div>
            <div class="mt-auto flex items-center justify-between pt-1">
                <span class="text-base font-bold">{{ product.price.toFixed(2) }} €</span>
                <Button
                    size="sm"
                    :variant="added ? 'default' : 'outline'"
                    class="h-7 min-w-[80px] text-xs transition-all"
                    :disabled="loading"
                    @click="handleAddToCart"
                >
                    <span v-if="added">✓ Añadido</span>
                    <span v-else>+ Carrito</span>
                </Button>
            </div>
        </div>
    </div>
</template>