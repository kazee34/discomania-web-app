<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

interface Product {
    id: number;
    artist: string;
    albumTitle: string;
    price: number;
    stock: number;
    genre: string | null;
    releaseYear: number | null;
    country: string | null;
    label: string | null;
    description: string | null;
    coverImageUrl: string | null;
}

const props = defineProps<{ product?: Product }>();

const isEdit = !!props.product;

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin Panel', href: { url: '/admin/products' } },
    { title: isEdit ? 'Editar producto' : 'Nuevo producto', href: { url: '#' } },
];

const form = useForm({
    artist:          props.product?.artist ?? '',
    album_title:     props.product?.albumTitle ?? '',
    price:           props.product?.price ?? '',
    stock:           props.product?.stock ?? 0,
    genre:           props.product?.genre ?? '',
    release_year:    props.product?.releaseYear ?? '',
    country:         props.product?.country ?? '',
    label:           props.product?.label ?? '',
    description:     props.product?.description ?? '',
    cover_image_url: props.product?.coverImageUrl ?? '',
});

function submit() {
    form.clearErrors();

    const artist    = String(form.artist).trim();
    const title     = String(form.album_title).trim();
    const genre     = String(form.genre).trim();
    const country   = String(form.country).trim();
    const label     = String(form.label).trim();
    const priceNum  = Number(form.price);
    const stockNum  = Number(form.stock);
    const yearNum   = Number(form.release_year);

    let ok = true;

    if (!artist) {
        form.setError('artist', 'El artista es obligatorio.');
        ok = false;
    }
    if (!title) {
        form.setError('album_title', 'El título del álbum es obligatorio.');
        ok = false;
    }
    if (form.price === '' || isNaN(priceNum) || priceNum < 0) {
        form.setError('price', form.price === '' ? 'El precio es obligatorio.' : 'El precio no puede ser negativo.');
        ok = false;
    }
    if (isNaN(stockNum) || !Number.isInteger(stockNum) || stockNum < 0 || stockNum > 1000) {
        form.setError('stock',
            stockNum < 0 ? 'El stock no puede ser inferior a 0.' :
            stockNum > 1000 ? 'El stock no puede ser superior a 1000.' :
            'El stock debe ser un número entero.');
        ok = false;
    }
    if (!genre) {
        form.setError('genre', 'El género es obligatorio.');
        ok = false;
    }
    if (form.release_year === '' || isNaN(yearNum) || yearNum < 1900 || yearNum > 2100) {
        form.setError('release_year',
            form.release_year === '' ? 'El año de lanzamiento es obligatorio.' :
            'El año debe estar entre 1900 y 2100.');
        ok = false;
    }
    if (!country) {
        form.setError('country', 'El país es obligatorio.');
        ok = false;
    }
    if (!label) {
        form.setError('label', 'El sello discográfico es obligatorio.');
        ok = false;
    }

    if (!ok) return;

    if (isEdit) {
        form.put(`/admin/products/${props.product!.id}`);
    } else {
        form.post('/admin/products');
    }
}
</script>

<template>
    <Head :title="isEdit ? 'Editar producto — Admin' : 'Nuevo producto — Admin'" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="max-w-2xl p-6 flex flex-col gap-6">
            <div>
                <h1 class="text-2xl font-bold">{{ isEdit ? 'Editar producto' : 'Nuevo producto' }}</h1>
            </div>

            <form @submit.prevent="submit" class="flex flex-col gap-6">
                <!-- Obligatorios -->
                <section class="rounded-xl border bg-card p-5 flex flex-col gap-4">
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-muted-foreground">Datos principales</h2>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="flex flex-col gap-1.5 sm:col-span-2">
                            <Label for="artist">Artista *</Label>
                            <Input id="artist" v-model="form.artist" />
                            <p v-if="form.errors.artist" class="text-xs text-destructive">{{ form.errors.artist }}</p>
                        </div>
                        <div class="flex flex-col gap-1.5 sm:col-span-2">
                            <Label for="album_title">Título del álbum *</Label>
                            <Input id="album_title" v-model="form.album_title" />
                            <p v-if="form.errors.album_title" class="text-xs text-destructive">{{ form.errors.album_title }}</p>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <Label for="price">Precio (€) *</Label>
                            <Input id="price" v-model="form.price" type="number" step="0.01" min="0" />
                            <p v-if="form.errors.price" class="text-xs text-destructive">{{ form.errors.price }}</p>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <Label for="stock">Stock *</Label>
                            <Input id="stock" v-model="form.stock" type="number" min="0" max="1000" />
                            <p v-if="form.errors.stock" class="text-xs text-destructive">{{ form.errors.stock }}</p>
                        </div>
                    </div>
                </section>

                <!-- Opcionales -->
                <section class="rounded-xl border bg-card p-5 flex flex-col gap-4">
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-muted-foreground">Detalles</h2>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="flex flex-col gap-1.5">
                            <Label for="genre">Género *</Label>
                            <Input id="genre" v-model="form.genre" />
                            <p v-if="form.errors.genre" class="text-xs text-destructive">{{ form.errors.genre }}</p>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <Label for="release_year">Año de lanzamiento *</Label>
                            <Input id="release_year" v-model="form.release_year" type="number" min="1900" max="2100" />
                            <p v-if="form.errors.release_year" class="text-xs text-destructive">{{ form.errors.release_year }}</p>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <Label for="country">País *</Label>
                            <Input id="country" v-model="form.country" />
                            <p v-if="form.errors.country" class="text-xs text-destructive">{{ form.errors.country }}</p>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <Label for="label">Sello discográfico *</Label>
                            <Input id="label" v-model="form.label" />
                            <p v-if="form.errors.label" class="text-xs text-destructive">{{ form.errors.label }}</p>
                        </div>

                        <!-- Portada -->
                        <div class="flex flex-col gap-1.5 sm:col-span-2">
                            <Label for="cover_image_url">URL de la portada</Label>
                            <Input
                                id="cover_image_url"
                                v-model="form.cover_image_url"
                                type="url"
                                placeholder="https://coverartarchive.org/..."
                            />
                            <p v-if="form.errors.cover_image_url" class="text-xs text-destructive">{{ form.errors.cover_image_url }}</p>
                            <div v-if="form.cover_image_url" class="mt-1 w-32 h-32 rounded-lg overflow-hidden border bg-muted shrink-0">
                                <img :src="form.cover_image_url" alt="" class="w-full h-full object-cover" />
                            </div>
                        </div>

                        <div class="flex flex-col gap-1.5 sm:col-span-2">
                            <Label for="description">Descripción</Label>
                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="4"
                                class="w-full rounded-md border bg-background px-3 py-2 text-sm shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
                            />
                            <p v-if="form.errors.description" class="text-xs text-destructive">{{ form.errors.description }}</p>
                        </div>
                    </div>
                </section>

                <div class="flex items-center gap-3">
                    <Button type="submit" :disabled="form.processing">
                        {{ form.processing ? 'Guardando...' : (isEdit ? 'Guardar cambios' : 'Crear producto') }}
                    </Button>
                    <Button as-child variant="outline">
                        <Link href="/admin/products">Cancelar</Link>
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
