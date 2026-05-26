<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { ShoppingCart } from 'lucide-vue-next';
import { computed } from 'vue';
import UserMenuContent from '@/components/UserMenuContent.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { useCart } from '@/composables/useCart';
import { getInitials } from '@/composables/useInitials';
import { login, register } from '@/routes';

const { itemCount } = useCart();
const page = usePage();
const auth = computed(() => page.props.auth);

const currentPath = computed(() => {
    const url = page.url;
    return url.split('?')[0];
});

function isActive(path: string, exact = false): boolean {
    if (exact) return currentPath.value === path;
    return currentPath.value === path || currentPath.value.startsWith(path + '/');
}
</script>

<template>
    <header class="sticky top-0 z-50 bg-linear-to-r from-violet-900 to-black">
        <div class="flex h-20 items-center justify-between px-4 sm:px-6">
            <!-- Left: brand + nav links -->
            <div class="flex items-center gap-6">
                <Link href="/" class="text-2xl font-bold tracking-tight text-white">
                    Discomania
                </Link>

                <Link
                    v-if="!isActive('/', true)"
                    href="/"
                    class="hidden text-sm text-white/70 transition-colors hover:text-white sm:block"
                >
                    Inicio
                </Link>
                <Link
                    v-if="!isActive('/shop')"
                    href="/shop"
                    class="hidden text-sm text-white/70 transition-colors hover:text-white sm:block"
                >
                    Tienda
                </Link>
                <Link
                    v-if="!isActive('/acerca')"
                    href="/acerca"
                    class="hidden text-sm text-white/70 transition-colors hover:text-white sm:block"
                >
                    Acerca de nosotros
                </Link>
                <Link
                    v-if="$page.props.isAdmin && !isActive('/dashboard') && !isActive('/admin')"
                    href="/dashboard"
                    class="hidden text-sm text-white/70 transition-colors hover:text-white sm:block"
                >
                    Admin Panel
                </Link>
            </div>

            <!-- Right: cart + user -->
            <nav class="flex items-center gap-4">
                <Link
                    href="/cart"
                    class="relative flex items-center gap-1 text-white/70 transition-colors hover:text-white"
                >
                    <ShoppingCart class="h-6 w-6" />
                    <span
                        v-if="itemCount > 0"
                        class="absolute -right-3 -top-2 flex h-5 w-5 items-center justify-center rounded-full bg-white text-[10px] font-bold text-violet-900"
                    >
                        {{ itemCount }}
                    </span>
                </Link>

                <template v-if="auth.user">
                    <DropdownMenu>
                        <DropdownMenuTrigger :as-child="true">
                            <Button
                                variant="ghost"
                                size="icon"
                                class="relative size-10 w-auto rounded-full p-1 hover:bg-white/10 focus-within:ring-2 focus-within:ring-white/50"
                            >
                                <Avatar class="size-8 overflow-hidden rounded-full">
                                    <AvatarImage
                                        v-if="auth.user.avatar"
                                        :src="auth.user.avatar"
                                        :alt="auth.user.name"
                                    />
                                    <AvatarFallback class="rounded-full bg-violet-700 font-semibold text-white">
                                        {{ getInitials(auth.user?.name) }}
                                    </AvatarFallback>
                                </Avatar>
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end" class="w-56">
                            <UserMenuContent :user="auth.user" />
                        </DropdownMenuContent>
                    </DropdownMenu>
                </template>

                <template v-else>
                    <Link
                        :href="login()"
                        class="text-base text-white/70 transition-colors hover:text-white"
                    >
                        Iniciar sesión
                    </Link>
                    <Button
                        as-child
                        size="default"
                        class="bg-white text-violet-900 hover:bg-white/90 font-semibold"
                    >
                        <Link :href="register()">Registrarse</Link>
                    </Button>
                </template>
            </nav>
        </div>
    </header>
</template>
