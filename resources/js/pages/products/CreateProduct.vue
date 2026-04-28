<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import { ProductForm } from '@/components/products';
import { Button } from '@/components/ui/button';
import { products } from '@/routes';

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
</script>
<template>
    <Head title="Create Product" />

    <div class="flex flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="flex items-center gap-4">
            <Link :href="products()">
                <Button variant="outline" size="icon">
                    <ArrowLeft />
                </Button>
            </Link>
            <h1 class="text-3xl font-medium">Novo Produto</h1>
        </div>
        <ProductForm />
    </div>
</template>
