<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ArrowLeft, Package } from 'lucide-vue-next';
import AppPagination from '@/components/AppPagination.vue';
import {
    StockAdjustmentForm,
    StockMovementsTable,
} from '@/components/products/stock';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { formatDate } from '@/lib/utils';
import { products } from '@/routes';
import type { PaginationInfo, Product, StockMovement } from '@/types';
import { stock } from '../../routes/products';

type Props = {
    product: Product;
    stockMovements: StockMovement[];
    pagination: PaginationInfo;
};

const props = defineProps<Props>();
defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Produtos', href: products() },
            { title: 'Estoque' },
        ],
    },
});

console.log(props.stockMovements);

function goToPage(page: number) {
    router.get(
        stock({ product: props.product }),
        { page },
        {
            preserveScroll: true,
            preserveState: true,
        },
    );
}
</script>
<template>
    <Head title="Product Stock Management" />
    <div class="flex flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="flex items-center gap-4">
            <Link :href="products()">
                <Button variant="outline" size="icon">
                    <ArrowLeft />
                </Button>
            </Link>
            <h1 class="text-3xl font-medium">Estoque do Produto</h1>
        </div>
        <div class="flex flex-col gap-6">
            <Card>
                <CardContent
                    class="flex flex-col gap-6 p-6 lg:flex-row lg:items-center lg:justify-between"
                >
                    <div class="flex items-center gap-6">
                        <div
                            :class="[
                                'aspect-square rounded-lg bg-muted',
                                product.primary_image ? 'size-30' : 'size-14',
                            ]"
                        >
                            <img
                                class="size-full rounded-lg object-cover"
                                v-if="product.primary_image"
                                :src="product.primary_image.url"
                                :alt="product.name"
                            />
                        </div>
                        <div class="flex flex-col">
                            <div class="mb-2 flex items-center gap-2">
                                <span class="text-2xl font-medium">{{
                                    product.name
                                }}</span>
                                <span
                                    class="rounded-md bg-muted p-1 text-xs text-muted-foreground"
                                    >{{ product.sku }}</span
                                >
                            </div>
                            <span class="text-sm text-muted-foreground"
                                >Preço:
                                <span class="text-primary">
                                    R$ {{ product.price }}
                                </span>
                            </span>
                            <span
                                v-if="product.sizes"
                                class="text-sm text-muted-foreground"
                                >Tamanhos:
                                <span class="text-primary">
                                    {{ product.sizes.join(', ') }}
                                </span>
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center gap-6 lg:flex-row-reverse">
                        <div class="rounded-full bg-muted p-4">
                            <Package class="size-9" />
                        </div>
                        <div class="lg:text-right">
                            <span class="text-sm text-muted-foreground"
                                >Estoque atual</span
                            >
                            <div
                                class="text-2xl font-semibold text-brand-green"
                            >
                                {{ product.stock }} unidades
                            </div>
                            <span class="text-xs text-muted-foreground">
                                Última atualização:
                                <span class="text-primary">
                                    {{ formatDate(product.updated_at) }}
                                </span>
                            </span>
                        </div>
                    </div>
                </CardContent>
            </Card>
            <div class="grid grid-cols-1 items-start gap-6 xl:grid-cols-2">
                <Card>
                    <CardHeader>
                        <span class="font-medium">Histórico de Movimentos</span>
                    </CardHeader>
                    <CardContent>
                        <StockMovementsTable
                            :stock-movements="stockMovements"
                        />
                        <AppPagination
                            className="my-4 justify-center md:justify-end"
                            :pagination="pagination"
                            @update:page="goToPage"
                        />
                    </CardContent>
                </Card>
                <StockAdjustmentForm :product="product" />
            </div>
        </div>
    </div>
</template>
