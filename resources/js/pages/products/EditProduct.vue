<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import { ProductForm } from '@/components/products';
import { Button } from '@/components/ui/button';
import { products } from '@/routes';
import type { Product } from '@/types';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Produtos',
                href: products(),
            },
        ],
    },
});

type Props = {
    product: Product;
};

defineProps<Props>();
</script>
<template>
    <Head title="Edit Product" />

    <div class="flex flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="flex items-center gap-4">
            <Link :href="products()">
                <Button variant="outline" size="icon">
                    <ArrowLeft />
                </Button>
            </Link>
            <h1 class="text-3xl font-medium">Editar Produto</h1>
        </div>
        <ProductForm :product="product" />
    </div>
</template>
