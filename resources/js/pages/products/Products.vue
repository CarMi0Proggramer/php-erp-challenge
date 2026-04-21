<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Plus, SearchIcon } from 'lucide-vue-next';
import { reactive } from 'vue';
import AppPagination from '@/components/AppPagination.vue';
import InputNumber from '@/components/InputNumber.vue';
import { ProductsTable } from '@/components/products';
import { Button } from '@/components/ui/button';
import {
    InputGroup,
    InputGroupAddon,
    InputGroupInput,
} from '@/components/ui/input-group';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { ProductSize } from '@/lib/enums/product-size';
import { debounce } from '@/lib/utils';
import { products as productsRoute } from '@/routes';
import type { PaginationInfo, Product } from '@/types';

type Props = {
    products: Product[];
    pagination: PaginationInfo;
    filters: {
        searchTerm?: string;
        priceFrom?: number;
        priceTo?: number;
        stockFrom?: number;
        stockTo?: number;
        sizes?: ProductSize[];
        sortBy?: string;
        sortDirection?: string;
    };
};

const props = defineProps<Props>();
defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Produtos',
                href: productsRoute(),
            },
        ],
    },
});

const filters = reactive({
    ...props.filters,
    page: props.pagination.currentPage,
});

function cleanFilters() {
    delete filters.priceFrom;
    delete filters.priceTo;
    delete filters.stockFrom;
    delete filters.stockTo;
    delete filters.sizes;

    applyFilters();
}

function applyFilters() {
    const cleanedFilters = Object.fromEntries(
        Object.entries(filters).filter(([, value]) => !!value),
    );

    router.get(productsRoute(), cleanedFilters, {
        preserveState: true,
        replace: true,
    });
}

const updateSearchTerm = debounce((searchTerm: string) => {
    filters.searchTerm = searchTerm;
    applyFilters();
}, 300);
</script>
<template>
    <Head title="Products" />
    <div class="flex flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="grid grid-flow-row gap-4 xl:grid-flow-col">
            <div class="flex flex-col gap-4 xl:max-w-4xl xl:flex-row">
                <InputGroup>
                    <InputGroupInput
                        placeholder="Pesquisar 'nome' ou 'sku'"
                        :default-value="filters.searchTerm"
                        @input="updateSearchTerm($event.target.value)"
                    />
                    <InputGroupAddon><SearchIcon /></InputGroupAddon>
                </InputGroup>
                <div class="flex gap-4">
                    <Select
                        v-model="filters.sortBy"
                        @update:model-value="applyFilters"
                    >
                        <SelectTrigger class="w-full xl:w-40">
                            <SelectValue placeholder="Ordenar por" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="name">Nome</SelectItem>
                            <SelectItem value="sku">SKU</SelectItem>
                            <SelectItem value="price">Preço</SelectItem>
                            <SelectItem value="stock">Estoque</SelectItem>
                        </SelectContent>
                    </Select>
                    <Select
                        v-model="filters.sortDirection"
                        @update:model-value="applyFilters"
                    >
                        <SelectTrigger class="w-full xl:w-34">
                            <SelectValue placeholder="Direção" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="asc">Ascendente</SelectItem>
                            <SelectItem value="desc">Descendente</SelectItem>
                        </SelectContent>
                    </Select>
                </div>
                <Popover>
                    <PopoverTrigger as-child>
                        <Button variant="outline" class="w-full xl:max-w-40"
                            >Filtros</Button
                        >
                    </PopoverTrigger>
                    <PopoverContent class="flex w-80 flex-col gap-4">
                        <div class="flex flex-col gap-2">
                            <span class="text-sm font-medium">Tamanhos</span>
                            <Select v-model="filters.sizes" multiple>
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
                        </div>
                        <div class="flex flex-col gap-2">
                            <span class="text-sm font-medium">Preço</span>
                            <div class="flex gap-2">
                                <InputNumber
                                    v-model="filters.priceFrom"
                                    placeholder="Min"
                                />
                                <InputNumber
                                    v-model="filters.priceTo"
                                    placeholder="Max"
                                />
                            </div>
                        </div>
                        <div class="flex flex-col gap-2">
                            <span class="text-sm font-medium">Estoque</span>
                            <div class="flex gap-2">
                                <InputNumber
                                    v-model="filters.stockFrom"
                                    placeholder="Min"
                                />
                                <InputNumber
                                    v-model="filters.stockTo"
                                    placeholder="Max"
                                />
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <Button
                                @click="cleanFilters"
                                variant="outline"
                                class="flex-1"
                            >
                                Limpar Filtros
                            </Button>
                            <Button @click="applyFilters" class="flex-1"
                                >Aplicar Filtros</Button
                            >
                        </div>
                    </PopoverContent>
                </Popover>
            </div>
            <Link class="ms-auto">
                <Button class="w-40"><Plus /> Criar Produto</Button>
            </Link>
        </div>
        <ProductsTable :products="products" />
        <AppPagination
            className="my-4 justify-center md:justify-end"
            :pagination="pagination"
            @update:page="filters.page = $event"
        />
    </div>
</template>
