<script setup lang="ts">
import { useHttp } from '@inertiajs/vue3';
import { Upload, Trash2, Star } from 'lucide-vue-next';
import { ref } from 'vue';
import ProductImageController from '@/actions/App/Http/Controllers/Products/ProductImageController';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import type { Product, ProductImage } from '@/types';

type Props = {
    product: Product;
};

const props = defineProps<Props>();
const http = useHttp<{ image: File | null }>({ image: null });
const error = ref<string | null>(null);
const images = ref<ProductImage[]>(props.product.images ?? []);
const uploading = ref<boolean>(false);
const loading = ref<Record<string, boolean>>({});

function handleUploadImage(event: Event) {
    const input = event.target as HTMLInputElement;

    if (!input.files || input.files.length === 0) {
        return;
    }

    http.image = input.files[0];
    error.value = null;
    uploading.value = true;

    const url = ProductImageController.store.url(props.product);

    http.post(url, {
        onSuccess: (image) => {
            images.value.push(image as ProductImage);
            input.value = '';
            http.image = null;
        },
        onError: (errors) => (error.value = errors.image),
        onFinish: () => (uploading.value = false),
    });
}

function handleSetPrimaryImage(image: ProductImage) {
    error.value = null;
    loading.value[image.id] = true;

    const url = ProductImageController.markAsPrimary.url({
        product: props.product,
        image,
    });

    http.post(url, {
        onSuccess: () => {
            images.value = images.value.map((img) => ({
                ...img,
                is_primary: img.id === image.id,
            }));
        },
        onError: (errors) => (error.value = errors.message),
        onFinish: () => (loading.value[image.id] = false),
    });
}

function handleDeleteImage(image: ProductImage) {
    error.value = null;
    loading.value[image.id] = true;

    const url = ProductImageController.destroy.url({
        product: props.product,
        image,
    });

    http.delete(url, {
        onSuccess: () => {
            images.value = images.value.filter(({ id }) => id !== image.id);
        },
        onError: (errors) => (error.value = errors.message),
        onFinish: () => (loading.value[image.id] = false),
    });
}
</script>
<template>
    <div class="flex flex-col gap-4">
        <Label>Galeria</Label>
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4">
            <div
                v-for="image in images"
                :key="image.id"
                class="group relative aspect-square overflow-hidden rounded-xl border bg-muted"
            >
                <img
                    :src="image.url"
                    class="size-full object-cover transition-transform duration-200 group-hover:scale-105"
                />

                <div
                    v-if="image.is_primary"
                    class="absolute top-2 left-2 rounded-md bg-primary px-2 py-1 text-xs text-primary-foreground"
                >
                    Principal
                </div>
                <div
                    v-if="loading[image.id] !== true"
                    class="absolute inset-0 flex items-center justify-center gap-2 bg-black/70 opacity-0 transition-opacity group-hover:opacity-100"
                >
                    <Button
                        @click="handleSetPrimaryImage(image)"
                        type="button"
                        size="icon"
                        variant="secondary"
                    >
                        <Star
                            v-if="image.is_primary"
                            class="size-4"
                            fill="currentColor"
                            stroke-width="0"
                        />
                        <Star v-else class="size-4" />
                    </Button>
                    <Button
                        @click="handleDeleteImage(image)"
                        type="button"
                        size="icon"
                        variant="destructive"
                    >
                        <Trash2 class="size-4" />
                    </Button>
                </div>
                <div
                    v-else
                    class="absolute inset-0 flex items-center justify-center bg-black/70"
                >
                    <Spinner class="size-6" />
                </div>
            </div>
            <Label
                :class="[
                    'flex aspect-square items-center justify-center rounded-xl border border-dashed transition',
                    uploading
                        ? 'cursor-not-allowed opacity-50'
                        : 'cursor-pointer hover:bg-muted',
                ]"
            >
                <Spinner v-if="uploading" class="size-6" />
                <Upload v-else class="size-6" />

                <input
                    type="file"
                    accept="image/*"
                    class="hidden"
                    :disabled="uploading"
                    @change="handleUploadImage"
                />
            </Label>
        </div>
    </div>
</template>
