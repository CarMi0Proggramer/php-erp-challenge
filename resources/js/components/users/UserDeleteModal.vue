<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import UsersController from '@/actions/App/Http/Controllers/UsersController';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Spinner } from '@/components/ui/spinner';
import type { User } from '@/types';

type Props = {
    user: User;
};

defineProps<Props>();

const isOpen = defineModel<boolean>('isOpen', { required: true });
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle>Remover Usuário</DialogTitle>
            </DialogHeader>

            <Form
                v-bind="UsersController.destroy.form(user.id)"
                method="delete"
                class="flex flex-col gap-6"
                v-slot="{ processing }"
                @success="isOpen = false"
            >
                <div class="text-sm text-muted-foreground">
                    Tem certeza que deseja remover o usuário
                    <span class="font-semibold text-foreground">
                        {{ user.name }} </span
                    >?
                    <br />
                    Esta ação não pode ser desfeita.
                </div>

                <div class="flex justify-end gap-2">
                    <Button
                        type="button"
                        variant="outline"
                        @click="isOpen = false"
                    >
                        Cancelar
                    </Button>

                    <Button
                        type="submit"
                        variant="destructive"
                        :disabled="processing"
                    >
                        <Spinner v-if="processing" />
                        Remover
                    </Button>
                </div>
            </Form>
        </DialogContent>
    </Dialog>
</template>
