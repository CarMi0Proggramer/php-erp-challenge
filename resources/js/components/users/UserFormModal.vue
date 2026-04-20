<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { computed } from 'vue';
import UsersController from '@/actions/App/Http/Controllers/UsersController';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
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
import { Role } from '@/lib/enums/role';
import type { User } from '@/types';

type Props = {
    user?: User;
};

const props = defineProps<Props>();

const isOpen = defineModel<boolean>('isOpen', { required: true });
const form = computed(() => {
    return props.user
        ? UsersController.update.form(props.user.id)
        : UsersController.store.form();
});
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogContent class="sm:max-w-lg">
            <DialogHeader>
                <DialogTitle>{{
                    user ? 'Editar Usuário' : 'Criar Usuário'
                }}</DialogTitle>
            </DialogHeader>
            <Form
                class="flex flex-col gap-6"
                reset-on-success
                v-bind="form"
                v-slot="{ errors, processing }"
                @success="isOpen = false"
            >
                <div class="grid gap-2">
                    <Label for="name">Nome</Label>
                    <Input
                        id="name"
                        name="name"
                        :tabindex="1"
                        placeholder="Nome"
                        :default-value="user?.name"
                    />
                    <InputError :message="errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">Email</Label>
                    <Input
                        id="email"
                        name="email"
                        type="email"
                        :tabindex="2"
                        :default-value="user?.email"
                        placeholder="Email"
                    />
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="role">Papel</Label>
                    <Select
                        id="role"
                        name="role"
                        :tabindex="3"
                        :default-value="user?.role"
                    >
                        <SelectTrigger class="w-full">
                            <SelectValue placeholder="Selecione um papel" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem :value="Role.OPERATOR"
                                >Operador</SelectItem
                            >
                            <SelectItem :value="Role.SELLER"
                                >Vendedor</SelectItem
                            >
                            <SelectItem :value="Role.ACCOUNTANT"
                                >Contador</SelectItem
                            ></SelectContent
                        >
                    </Select>
                    <InputError :message="errors.role" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Senha</Label>
                    <PasswordInput
                        id="password"
                        name="password"
                        :tabindex="4"
                        autocomplete="new-password"
                        placeholder="Password"
                    />
                    <InputError :message="errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation"> Confirmar senha </Label>
                    <PasswordInput
                        id="password_confirmation"
                        name="password_confirmation"
                        autocomplete="new-password"
                        placeholder="Confirmar senha"
                        :tabindex="5"
                    />
                    <InputError :message="errors.password_confirmation" />
                </div>

                <Button
                    class="mt-4 w-full"
                    type="submit"
                    :disabled="processing"
                >
                    <Spinner v-if="processing" />
                    {{ user ? 'Atualizar Usuário' : 'Criar Usuário' }}
                </Button>
            </Form>
        </DialogContent>
    </Dialog>
</template>
