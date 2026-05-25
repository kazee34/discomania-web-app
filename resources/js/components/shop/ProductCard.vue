<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { useCart } from '@/composables/useCart';

interface Product {
    id: number;
    slug: string;
    artist: string;
    albumTitle: string;
    price: number;
    genre: string;
    releaseYear: number;
    coverImageUrl: string | null;
    stockQuantity: number;
}

const props = defineProps<{ product: Product }>();

const { cart, loading, addItem } = useCart();
const added = ref(false);

const cartQuantity = computed(
    () => cart.value?.items.find((i) => i.productId === props.product.id)?.quantity ?? 0,
);

const remainingStock = computed(
    () => Math.max(0, props.product.stockQuantity - cartQuantity.value),
);

const canAdd = computed(() => remainingStock.value > 0);

async function handleAddToCart() {
    if (!canAdd.value) return;
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
                alt=""
                class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                loading="lazy"
                decoding="async"
            />
            <div v-else class="flex h-full items-center justify-center bg-muted" />
        </Link>

        <!-- Info -->
        <div class="flex flex-1 flex-col gap-2.5 p-4">
            <Link :href="`/shop/${product.slug}`">
                <p class="truncate font-semibold leading-tight hover:underline">{{ product.albumTitle }}</p>
                <p class="truncate text-sm text-muted-foreground">{{ product.artist }}</p>
            </Link>
            <div class="flex items-center justify-between gap-1">
                <Badge variant="secondary" class="truncate text-xs">{{ product.genre }}</Badge>
                <span class="text-xs text-muted-foreground">{{ product.releaseYear }}</span>
            </div>
            <div class="mt-auto flex items-center justify-between pt-1">
                <span class="text-lg font-bold">{{ product.price.toFixed(2) }} €</span>
                <Button
                    size="sm"
                    :variant="added ? 'default' : 'outline'"
                    class="min-w-20 text-xs transition-all"
                    :disabled="loading || !canAdd"
                    @click="handleAddToCart"
                >
                    <span v-if="product.stockQuantity === 0">Sin stock</span>
                    <span v-else-if="!canAdd">Máx. alcanzado</span>
                    <span v-else-if="added">Añadido</span>
                    <span v-else>Añadir</span>
                </Button>
            </div>
        </div>
    </div>
</template>
