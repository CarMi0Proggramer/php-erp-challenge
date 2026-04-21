<script setup lang="ts">
import {
    Pagination,
    PaginationContent,
    PaginationNext,
    PaginationPrevious,
    PaginationEllipsis,
    PaginationItem,
} from '@/components/ui/pagination';
import type { PaginationInfo } from '@/types';

type Props = {
    pagination: PaginationInfo;
    className?: string;
};

type Emits = {
    (event: 'update:page', page: number): void;
};

defineProps<Props>();
defineEmits<Emits>();
</script>
<template>
    <Pagination
        v-slot="{ page }"
        :class="className"
        :page="pagination.currentPage"
        :items-per-page="pagination.perPage"
        :total="pagination.total"
        :default-page="1"
        @update:page="$emit('update:page', $event)"
    >
        <PaginationContent v-slot="{ items }" class="flex flex-wrap gap-2">
            <PaginationPrevious />
            <template v-for="(item, index) in items" :key="index">
                <PaginationItem
                    v-if="item.type === 'page'"
                    :value="item.value"
                    :is-active="item.value === page"
                >
                    {{ item.value }}
                </PaginationItem>
            </template>
            <PaginationEllipsis v-if="items.length > 4" :index="4" />
            <PaginationNext />
        </PaginationContent>
    </Pagination>
</template>
