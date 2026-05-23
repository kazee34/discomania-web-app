<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ShoppingCart } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { useCart } from '@/composables/useCart';
import { login, register } from '@/routes';

const { itemCount } = useCart();
</script>

<template>
    <header class="sticky top-0 z-50 border-b bg-background/95 backdrop-blur">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6">
            <span class="text-xl font-bold tracking-tight">Discomania</span>
            <nav class="flex items-center gap-4">
                <Link href="/cart" class="relative flex items-center gap-1 text-sm text-muted-foreground hover:text-foreground">
                    <ShoppingCart class="h-5 w-5" />
                    <span
                        v-if="itemCount > 0"
                        class="absolute -top-2 -right-3 flex h-5 w-5 items-center justify-center rounded-full bg-primary text-[10px] font-bold text-primary-foreground"
                    >
                        {{ itemCount }}
                    </span>
                </Link>

                <template v-if="$page.props.auth.user">
                    <Link
                        v-if="$page.props.isAdmin"
                        href="/admin/products"
                        class="text-sm text-muted-foreground hover:text-foreground"
                    >
                        Admin Panel
                    </Link>
                    <Link href="/profile" class="text-sm text-muted-foreground hover:text-foreground">
                        Mi perfil
                    </Link>
                    <Link href="/dashboard" class="text-sm text-muted-foreground hover:text-foreground">
                        Dashboard
                    </Link>
                </template>
                <template v-else>
                    <Link :href="login()" class="text-sm text-muted-foreground hover:text-foreground">
                        Iniciar sesión
                    </Link>
                    <Button as-child size="sm">
                        <Link :href="register()">Registrarse</Link>
                    </Button>
                </template>

            </nav>
        </div>
    </header>
</template>