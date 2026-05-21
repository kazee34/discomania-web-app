<script setup lang="ts">
import type { CartItem } from '@/composables/useCart';
import { useCart } from '@/composables/useCart';
import { Button } from '@/components/ui/button';

defineProps<{ item: CartItem }>();

const { updateQuantity, removeItem } = useCart();
</script>

<template>
    <div class="flex items-center gap-4 py-4 border-b last:border-0">
        <!-- Portada -->
        <div class="h-16 w-16 shrink-0 overflow-hidden rounded-lg bg-muted">
            <img
                v-if="item.productCoverImageUrl"
                :src="item.productCoverImageUrl"
                :alt="item.productAlbumTitle ?? ''"
                class="h-full w-full object-cover"
            />
            <div v-else class="h-full w-full bg-muted" />
        </div>

        <!-- Info -->
        <div class="flex-1 min-w-0">
            <p class="truncate text-sm font-semibold">{{ item.productAlbumTitle ?? `Producto #${item.productId}` }}</p>
            <p class="truncate text-xs text-muted-foreground">{{ item.productArtist }}</p>
            <p class="text-xs text-muted-foreground">{{ item.price.toFixed(2) }} € / ud.</p>
        </div>

        <!-- Controles cantidad -->
        <div class="flex items-center gap-1">
            <Button
                size="icon"
                variant="outline"
                class="h-7 w-7 text-base"
                :disabled="item.quantity <= 1"
                @click="updateQuantity(item.id, item.quantity - 1)"
            >−</Button>
            <span class="w-6 text-center text-sm font-medium">{{ item.quantity }}</span>
            <Button
                size="icon"
                variant="outline"
                class="h-7 w-7 text-base"
                @click="updateQuantity(item.id, item.quantity + 1)"
            >+</Button>
        </div>

        <!-- Subtotal + eliminar -->
        <div class="flex flex-col items-end gap-1 shrink-0">
            <span class="text-sm font-bold">{{ item.subtotal.toFixed(2) }} €</span>
            <button
                class="text-xs text-muted-foreground hover:text-destructive transition-colors"
                @click="removeItem(item.id)"
            >
                Eliminar
            </button>
        </div>
    </div>
</template>