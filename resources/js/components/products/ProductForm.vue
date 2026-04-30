<script setup lang="ts">
import { Form, useHttp } from '@inertiajs/vue3';
import { computed, provide, ref } from 'vue';
import ProductDescriptionImageController from '@/actions/App/Http/Controllers/Products/ProductDescriptionImageController';
import ProductsController from '@/actions/App/Http/Controllers/Products/ProductsController';
import InputError from '@/components/InputError.vue';
import InputNumber from '@/components/InputNumber.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Spinner } from '@/components/ui/spinner';
import { ImageUploaderKey, WysiwygEditor } from '@/components/wysiwyg';
import type { ImageUploader } from '@/components/wysiwyg';
import { ProductSize } from '@/lib/enums/product-size';
import type { Product } from '@/types';
import ProductImageGallery from './ProductImageGallery.vue';

type Props = {
    product?: Product;
};

const props = defineProps<Props>();
const http = useHttp<{ image: File | null }>({ image: null });

const description = ref<string>(props.product?.description ?? '');
const sizes = ref<ProductSize[]>(props.product?.sizes ?? []);
const form = computed(() => {
    return props.product
        ? ProductsController.update.form(props.product.id)
        : ProductsController.store.form();
});

const editorImageUploader: ImageUploader = {
    delete: async (id) => {
        if (!props.product) {
            return;
        }

        const url = ProductDescriptionImageController.destroy({
            product: props.product,
            description_image: id,
        }).url;

        http.delete(url);
    },
    upload: (file) => {
        if (!props.product) {
            return Promise.reject('O produto ainda não foi criado');
        }

        http.image = file;
        const url = ProductDescriptionImageController.store(props.product).url;

        return new Promise((resolve, reject) => {
            http.post(url, {
                onSuccess: (response) => {
                    const data = response as { id: string; url: string };
                    resolve(data);
                },
                onError: (errors) => reject(errors.image),
            });
        });
    },
};

provide(ImageUploaderKey, editorImageUploader);
</script>
<template>
    <Form
        class="my-4 flex flex-col gap-6"
        reset-on-success
        v-bind="form"
        v-slot="{ errors, processing }"
    >
        <div class="flex flex-col gap-6 2xl:flex-row 2xl:items-stretch">
            <div class="flex flex-1 flex-col gap-6">
                <div class="flex flex-col gap-6 sm:flex-row">
                    <div class="grid w-full gap-2">
                        <Label for="name">Nome</Label>
                        <Input
                            id="name"
                            name="name"
                            placeholder="Nome"
                            :tabindex="1"
                            :default-value="product?.name"
                        />
                        <InputError :message="errors.name" />
                    </div>
                    <div class="grid w-full gap-2">
                        <Label for="sizes">Tamanhos</Label>
                        <Select
                            id="sizes"
                            name="sizes"
                            multiple
                            :tabindex="4"
                            v-model="sizes"
                        >
                            <SelectTrigger class="w-full">
                                <SelectValue placeholder="Todos" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem :value="ProductSize.S"
                                    >P</SelectItem
                                >
                                <SelectItem :value="ProductSize.M"
                                    >M</SelectItem
                                >
                                <SelectItem :value="ProductSize.L"
                                    >G</SelectItem
                                >
                                <SelectItem :value="ProductSize.XL"
                                    >GG</SelectItem
                                >
                            </SelectContent>
                        </Select>
                        <input
                            v-for="size in sizes"
                            type="hidden"
                            name="sizes[]"
                            :key="size"
                            :value="size"
                        />
                        <InputError :message="errors.sizes" />
                    </div>
                </div>
                <div class="flex flex-col gap-6 sm:flex-row">
                    <div
                        :class="[
                            'grid w-full gap-2',
                            product ? 'w-full' : 'sm:w-1/2 sm:pe-3',
                        ]"
                    >
                        <Label for="price">Preço</Label>
                        <InputNumber
                            id="price"
                            name="price"
                            placeholder="Preço"
                            :tabindex="2"
                            :default-value="product?.price"
                            :step="0.01"
                            :format-options="{ minimumFractionDigits: 2 }"
                        />
                        <InputError :message="errors.price" />
                    </div>

                    <div v-if="!!product" class="grid w-full gap-2">
                        <Label for="sku">SKU</Label>
                        <Input
                            id="sku"
                            name="sku"
                            placeholder="SKU"
                            :tabindex="3"
                            :default-value="product?.sku"
                        />
                        <InputError :message="errors.sku" />
                    </div>
                </div>
                <div v-if="!!product">
                    <ProductImageGallery :product="product" />
                </div>
            </div>
            <div v-if="!!product" class="flex-1">
                <Input type="hidden" name="description" :value="description" />
                <WysiwygEditor v-model="description" />
                <InputError :message="errors.description" />
            </div>
        </div>
        <Button
            class="w-full sm:ml-auto sm:w-40"
            type="submit"
            :disabled="processing"
        >
            <Spinner v-if="processing" />
            Salvar
        </Button>
    </Form>
</template>
