<script setup lang="ts">
import { Button } from '@/components/ui/button';
import type { CartItem } from '@/composables/useCart';
import { useCart } from '@/composables/useCart';

defineProps<{ item: CartItem }>();

const { updateQuantity, removeItem } = useCart();
</script>

<template>
    <div class="flex items-center gap-5 py-6 border-b last:border-0">
        <!-- Portada -->
        <div class="h-24 w-24 shrink-0 overflow-hidden rounded-xl bg-muted">
            <img
                v-if="item.productCoverImageUrl"
                :src="item.productCoverImageUrl"
                :alt="item.productAlbumTitle ?? ''"
                class="h-full w-full object-cover"
                loading="lazy"
                decoding="async"
            />
            <div v-else class="h-full w-full bg-muted" />
        </div>

        <!-- Info -->
        <div class="flex-1 min-w-0">
            <p class="truncate text-base font-semibold">{{ item.productAlbumTitle ?? `Producto #${item.productId}` }}</p>
            <p class="truncate text-sm text-muted-foreground">{{ item.productArtist }}</p>
            <p class="text-sm text-muted-foreground">{{ item.price.toFixed(2) }} € / ud.</p>
        </div>

        <!-- Controles cantidad -->
        <div class="flex items-center gap-2">
            <Button
                size="icon"
                variant="outline"
                class="h-9 w-9 text-base"
                :disabled="item.quantity <= 1"
                @click="updateQuantity(item.id, item.quantity - 1)"
            >−</Button>
            <span class="w-8 text-center text-base font-medium">{{ item.quantity }}</span>
            <Button
                size="icon"
                variant="outline"
                class="h-9 w-9 text-base"
                @click="updateQuantity(item.id, item.quantity + 1)"
            >+</Button>
        </div>

        <!-- Subtotal + eliminar -->
        <div class="flex flex-col items-end gap-1 shrink-0">
            <span class="text-base font-bold">{{ item.subtotal.toFixed(2) }} €</span>
            <button
                class="text-sm text-muted-foreground hover:text-destructive transition-colors"
                @click="removeItem(item.id)"
            >
                Eliminar
            </button>
        </div>
    </div>
</template>