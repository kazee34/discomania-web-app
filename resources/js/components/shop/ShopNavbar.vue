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
</script>

<template>
    <header class="sticky top-0 z-50 bg-linear-to-r from-violet-900 to-black">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6">
            <Link href="/" class="text-xl font-bold tracking-tight text-white">
                Discomania
            </Link>

            <nav class="flex items-center gap-4">
                <Link
                    href="/cart"
                    class="relative flex items-center gap-1 text-white/70 transition-colors hover:text-white"
                >
                    <ShoppingCart class="h-5 w-5" />
                    <span
                        v-if="itemCount > 0"
                        class="absolute -right-3 -top-2 flex h-5 w-5 items-center justify-center rounded-full bg-white text-[10px] font-bold text-violet-900"
                    >
                        {{ itemCount }}
                    </span>
                </Link>

                <template v-if="auth.user">
                    <Link
                        v-if="$page.props.isAdmin"
                        href="/admin/products"
                        class="text-sm text-white/70 transition-colors hover:text-white"
                    >
                        Admin Panel
                    </Link>

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
                        class="text-sm text-white/70 transition-colors hover:text-white"
                    >
                        Iniciar sesión
                    </Link>
                    <Button
                        as-child
                        size="sm"
                        class="bg-white text-violet-900 hover:bg-white/90"
                    >
                        <Link :href="register()">Registrarse</Link>
                    </Button>
                </template>
            </nav>
        </div>
    </header>
</template>
