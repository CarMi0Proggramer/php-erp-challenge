<script setup lang="ts">
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { formatDate, toUserFriendlyStockMovement } from '@/lib/utils';
import type { StockMovement } from '@/types';

type Props = {
    stockMovements: StockMovement[];
};

defineProps<Props>();
</script>
<template>
    <Table>
        <TableHeader>
            <TableRow>
                <TableHead>Data</TableHead>
                <TableHead>Tipo</TableHead>
                <TableHead>Quantidade</TableHead>
                <TableHead>Estoque Antes</TableHead>
                <TableHead>Saldo Final</TableHead>
                <TableHead>Motivo</TableHead>
                <TableHead>Usuário</TableHead>
            </TableRow>
        </TableHeader>

        <TableBody>
            <TableRow v-if="!stockMovements.length">
                <TableCell
                    colspan="7"
                    class="py-6 text-center text-muted-foreground"
                >
                    Nenhum movimento encontrado
                </TableCell>
            </TableRow>

            <TableRow
                v-for="stockMovement in stockMovements"
                :key="stockMovement.id"
            >
                <TableCell>
                    {{ formatDate(stockMovement.created_at) }}
                </TableCell>

                <TableCell>
                    {{ toUserFriendlyStockMovement(stockMovement.type) }}
                </TableCell>

                <TableCell>
                    {{ stockMovement.quantity ?? '-' }}
                </TableCell>

                <TableCell>
                    {{ stockMovement.stock_before }}
                </TableCell>

                <TableCell>
                    {{ stockMovement.stock_balance }}
                </TableCell>

                <TableCell class="text-muted-foreground">
                    {{ stockMovement.reason ?? '-' }}
                </TableCell>

                <TableCell>
                    {{ stockMovement.user?.name ?? '—' }}
                </TableCell>
            </TableRow>
        </TableBody>
    </Table>
</template>
