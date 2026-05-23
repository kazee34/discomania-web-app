<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import ShopNavbar from '@/components/shop/ShopNavbar.vue';
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
    country: string;
    releaseYear: number;
    coverImageUrl: string | null;
    description: string | null;
    label: string | null;
    stockQuantity: number;
}

const props = defineProps<{ product: Product }>();

const { cart, loading, addItem, fetchCart } = useCart();
const added = ref(false);

onMounted(() => fetchCart());

const cartQuantity = computed(
    () => cart.value?.items.find((i) => i.productId === props.product.id)?.quantity ?? 0,
);

const remainingStock = computed(
    () => Math.max(0, props.product.stockQuantity - cartQuantity.value),
);

const canAdd = computed(
    () => remainingStock.value > 0,
);

const stockLabel = computed(() => {
    const s = remainingStock.value;
    if (s === 0)  return { text: 'Fuera de stock',    class: 'text-destructive' };
    if (s <= 3)   return { text: 'Últimas unidades',  class: 'text-amber-500' };
    return              { text: 'En stock',            class: 'text-green-600' };
});

async function handleAddToCart() {
    if (!canAdd.value) return;
    await addItem(props.product.id, props.product.price);
    added.value = true;
    setTimeout(() => (added.value = false), 1500);
}
</script>

<template>
    <Head :title="`${product.artist} — ${product.albumTitle} | Discomania`" />

    <div class="min-h-screen bg-background">
        <ShopNavbar />

        <main class="mx-auto max-w-5xl px-4 py-10 sm:px-6">
            <Link
                href="/shop"
                class="mb-8 inline-block text-sm text-muted-foreground hover:text-foreground"
            >
                ← Volver al catálogo
            </Link>

            <div class="grid gap-10 md:grid-cols-2">
                <!-- Portada -->
                <div class="aspect-square overflow-hidden rounded-2xl border bg-muted">
                    <img
                        v-if="product.coverImageUrl"
                        :src="product.coverImageUrl"
                        :alt="`${product.artist} - ${product.albumTitle}`"
                        class="h-full w-full object-cover"
                    />
                    <div v-else class="h-full w-full bg-muted" />
                </div>

                <!-- Info -->
                <div class="flex flex-col gap-6">
                    <div>
                        <p class="mb-1 text-sm text-muted-foreground">{{ product.artist }}</p>
                        <h1 class="text-3xl font-bold leading-tight">{{ product.albumTitle }}</h1>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <Badge variant="secondary">{{ product.genre }}</Badge>
                        <Badge variant="outline">{{ product.releaseYear }}</Badge>
                        <Badge variant="outline">{{ product.country }}</Badge>
                        <Badge v-if="product.label" variant="outline">{{ product.label }}</Badge>
                    </div>

                    <p v-if="product.description" class="text-sm leading-relaxed text-muted-foreground">
                        {{ product.description }}
                    </p>

                    <div class="mt-auto rounded-xl border bg-card p-5 flex flex-col gap-4">
                        <div class="flex items-baseline justify-between">
                            <span class="text-3xl font-bold">{{ product.price.toFixed(2) }} €</span>
                            <span :class="['text-sm font-medium', stockLabel.class]">
                                {{ stockLabel.text }}
                            </span>
                        </div>

                        <Button
                            class="w-full"
                            size="lg"
                            :disabled="loading || !canAdd"
                            @click="handleAddToCart"
                        >
                            <span v-if="product.stockQuantity === 0">Fuera de stock</span>
                            <span v-else-if="!canAdd">Ya tienes el máximo disponible</span>
                            <span v-else-if="added">Añadido al carrito</span>
                            <span v-else>Añadir al carrito</span>
                        </Button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>
