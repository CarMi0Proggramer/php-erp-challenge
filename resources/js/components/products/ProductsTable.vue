<script setup lang="ts">
import { Pencil, Trash2, Package } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import type { Product } from '@/types';

type Props = {
    products: Product[];
};

type Emits = {
    (event: 'edit', product: Product): void;
    (event: 'delete', product: Product): void;
    (event: 'manage-stock', product: Product): void;
};

defineProps<Props>();
defineEmits<Emits>();
</script>

<template>
    <Table class="text-sm">
        <TableHeader>
            <TableRow class="font-semibold">
                <TableHead>Nome</TableHead>
                <TableHead>SKU</TableHead>
                <TableHead>Preço</TableHead>
                <TableHead>Stock</TableHead>
                <TableHead>Tamanhos</TableHead>
                <TableHead class="text-right">Ações</TableHead>
            </TableRow>
        </TableHeader>

        <TableBody>
            <TableRow v-if="!products.length">
                <TableCell
                    colspan="6"
                    class="py-6 text-center text-muted-foreground"
                >
                    Nenhum produto encontrado
                </TableCell>
            </TableRow>

            <TableRow v-for="product in products" :key="product.id">
                <TableCell class="font-medium">{{ product.name }}</TableCell>
                <TableCell>{{ product.sku }}</TableCell>
                <TableCell>R$ {{ product.price }}</TableCell>
                <TableCell>{{ product.stock }}</TableCell>
                <TableCell>
                    {{ product.sizes ? product.sizes.join(', ') : 'N/A' }}
                </TableCell>
                <TableCell class="space-x-2 text-right">
                    <Button
                        size="icon"
                        variant="outline"
                        @click="$emit('manage-stock', product)"
                    >
                        <Package />
                    </Button>

                    <Button
                        size="icon"
                        variant="outline"
                        @click="$emit('edit', product)"
                    >
                        <Pencil />
                    </Button>

                    <Button
                        size="icon"
                        variant="destructive"
                        @click="$emit('delete', product)"
                    >
                        <Trash2 />
                    </Button>
                </TableCell>
            </TableRow>
        </TableBody>
    </Table>
</template>
