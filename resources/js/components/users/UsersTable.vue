<script setup lang="ts">
import { Pencil, Trash2 } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { toUserFriendlyRole } from '@/lib/utils';
import type { User } from '@/types';

type Props = {
    users: User[];
};

type Emits = {
    (event: 'edit', user: User): void;
    (event: 'delete', user: User): void;
};

defineProps<Props>();
defineEmits<Emits>();
</script>

<template>
    <Table class="text-sm">
        <TableHeader>
            <TableRow class="font-semibold">
                <TableHead>Nome</TableHead>
                <TableHead>Email</TableHead>
                <TableHead>Papel</TableHead>
                <TableHead class="text-right">Ações</TableHead>
            </TableRow>
        </TableHeader>

        <TableBody>
            <TableRow v-if="!users.length">
                <TableCell
                    colspan="4"
                    class="py-6 text-center text-muted-foreground"
                >
                    Nenhum usuário encontrado
                </TableCell>
            </TableRow>

            <TableRow v-for="user in users" :key="user.id">
                <TableCell class="font-medium">{{ user.name }}</TableCell>
                <TableCell>{{ user.email }}</TableCell>
                <TableCell>
                    <span class="rounded-md bg-muted px-2 py-1 text-xs">
                        {{ toUserFriendlyRole(user.role) }}
                    </span>
                </TableCell>
                <TableCell class="space-x-2 text-right">
                    <Button
                        size="icon"
                        variant="outline"
                        @click="$emit('edit', user)"
                    >
                        <Pencil />
                    </Button>

                    <Button
                        size="icon"
                        variant="destructive"
                        @click="$emit('delete', user)"
                    >
                        <Trash2 />
                    </Button>
                </TableCell>
            </TableRow>
        </TableBody>
    </Table>
</template>
