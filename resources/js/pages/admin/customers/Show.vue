<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import type { BreadcrumbItem } from '@/types';

interface Address {
    street: string;
    street_number: string;
    apartment: string | null;
    city: string;
    postal_code: string;
    state_province: string;
    country: string;
    iso_country_code: string;
}

interface Customer {
    id: number;
    firstName: string;
    lastName: string;
    phone: string;
    dniNif: string;
    birthDate: string | null;
    totalOrders: number;
    isActive: boolean;
    createdAt: string | null;
    address: Address;
}

const props = defineProps<{ customer: Customer }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin Panel', href: '/admin/products' },
    { title: 'Clientes', href: '/admin/customers' },
    { title: `${props.customer.firstName} ${props.customer.lastName}` },
];

function deactivate() {
    if (!confirm('¿Desactivar este cliente?')) return;
    useForm({}).patch(`/admin/customers/${props.customer.id}/deactivate`);
}
</script>

<template>
    <Head :title="`${customer.firstName} ${customer.lastName} — Admin`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-6 max-w-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">{{ customer.firstName }} {{ customer.lastName }}</h1>
                    <p class="text-sm text-muted-foreground mt-0.5">Cliente #{{ customer.id }}</p>
                </div>
                <div class="flex gap-2">
                    <Button as-child variant="outline" size="sm">
                        <Link href="/admin/customers">Volver</Link>
                    </Button>
                    <Button
                        v-if="customer.isActive"
                        size="sm"
                        variant="destructive"
                        @click="deactivate"
                    >
                        Desactivar
                    </Button>
                </div>
            </div>

            <div v-if="$page.props.flash?.success" class="rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ $page.props.flash.success }}
            </div>

            <div class="rounded-xl border bg-card divide-y">
                <div class="grid grid-cols-2 gap-4 p-4">
                    <div>
                        <p class="text-xs text-muted-foreground">Estado</p>
                        <span
                            class="mt-1 inline-block rounded-full px-2.5 py-0.5 text-xs font-medium"
                            :class="customer.isActive ? 'bg-green-100 text-green-800' : 'bg-muted text-muted-foreground'"
                        >
                            {{ customer.isActive ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs text-muted-foreground">Pedidos totales</p>
                        <p class="font-medium mt-1">{{ customer.totalOrders }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 p-4">
                    <div>
                        <p class="text-xs text-muted-foreground">DNI/NIF</p>
                        <p class="font-medium mt-1">{{ customer.dniNif }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-muted-foreground">Teléfono</p>
                        <p class="font-medium mt-1">{{ customer.phone }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 p-4">
                    <div>
                        <p class="text-xs text-muted-foreground">Fecha de nacimiento</p>
                        <p class="font-medium mt-1">{{ customer.birthDate ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-muted-foreground">Alta</p>
                        <p class="font-medium mt-1">{{ customer.createdAt ?? '—' }}</p>
                    </div>
                </div>

                <div class="p-4">
                    <p class="text-xs text-muted-foreground mb-2">Dirección de envío</p>
                    <p class="font-medium">{{ customer.address.street }}, {{ customer.address.street_number }}<span v-if="customer.address.apartment">, {{ customer.address.apartment }}</span></p>
                    <p class="text-sm text-muted-foreground">{{ customer.address.postal_code }} {{ customer.address.city }}, {{ customer.address.state_province }}</p>
                    <p class="text-sm text-muted-foreground">{{ customer.address.country }}</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
