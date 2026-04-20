<script setup lang="ts">
import 'vue-sonner/style.css';
import { Head, router, usePage } from '@inertiajs/vue3';
import { Plus, SearchIcon } from 'lucide-vue-next';
import { reactive, ref, watch, watchEffect } from 'vue';
import { toast } from 'vue-sonner';
import { Button } from '@/components/ui/button';
import {
    InputGroup,
    InputGroupAddon,
    InputGroupInput,
} from '@/components/ui/input-group';
import {
    Pagination,
    PaginationContent,
    PaginationNext,
    PaginationPrevious,
    PaginationEllipsis,
    PaginationItem,
} from '@/components/ui/pagination';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Toaster } from '@/components/ui/sonner';
import { UsersTable, UserFormModal, UserDeleteModal } from '@/components/users';
import { Role } from '@/lib/enums/role';
import { debounce } from '@/lib/utils';
import { users as usersRoute } from '@/routes';
import type { PaginationInfo, User } from '@/types';

type Props = {
    users: User[];
    pagination: PaginationInfo;
    filters?: {
        sortBy?: string;
        sortDirection?: string;
        role?: Role;
        searchTerm?: string;
    };
};

const props = defineProps<Props>();
defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Usuários',
                href: usersRoute(),
            },
        ],
    },
});

const page = usePage();
const isUserFormModalOpen = ref<boolean>(false);
const isUserDeleteModalOpen = ref<boolean>(false);
const selectedUser = ref<User>();
const filters = reactive({
    sortBy: props.filters?.sortBy,
    sortDirection: props.filters?.sortDirection,
    role: props.filters?.role,
    searchTerm: props.filters?.searchTerm,
    page: props.pagination.currentPage,
});

function handleCreateUser() {
    selectedUser.value = undefined;
    isUserFormModalOpen.value = true;
}

function handleEditUser(user: User) {
    selectedUser.value = user;
    isUserFormModalOpen.value = true;
}

function handleDeleteUser(user: User) {
    selectedUser.value = user;
    isUserDeleteModalOpen.value = true;
}

function applyFilters() {
    const cleanedFilters = Object.fromEntries(
        Object.entries(filters).filter(([, value]) => !!value),
    );

    router.get(usersRoute(), cleanedFilters, {
        preserveState: true,
        replace: true,
    });
}

const updateSearchTerm = debounce((searchTerm: string) => {
    filters.page = 1;
    filters.searchTerm = searchTerm;
}, 300);

watchEffect(() => {
    if (page.props.flash.message) {
        toast.success(page.props.flash.message, { position: 'top-center' });
    }
});

watch(filters, applyFilters);
</script>
<template>
    <div>
        <Head title="Users" />
        <div class="flex flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid grid-flow-row gap-4 xl:grid-flow-col">
                <div class="flex flex-col gap-4 xl:max-w-4xl xl:flex-row">
                    <InputGroup>
                        <InputGroupInput
                            placeholder="Pesquisar 'nome' ou 'email'"
                            :default-value="filters.searchTerm"
                            @input="updateSearchTerm($event.target.value)"
                        />
                        <InputGroupAddon><SearchIcon /></InputGroupAddon>
                    </InputGroup>
                    <div class="flex gap-4">
                        <Select v-model="filters.sortBy">
                            <SelectTrigger class="w-full xl:w-40">
                                <SelectValue placeholder="Ordenar por" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="name">Nome</SelectItem>
                                <SelectItem value="email">Email</SelectItem>
                                <SelectItem value="role">Papel</SelectItem>
                            </SelectContent>
                        </Select>
                        <Select v-model="filters.sortDirection">
                            <SelectTrigger class="w-full xl:w-34">
                                <SelectValue placeholder="Direção" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="asc">Ascendente</SelectItem>
                                <SelectItem value="desc"
                                    >Descendente</SelectItem
                                >
                            </SelectContent>
                        </Select>
                    </div>
                    <Select v-model="filters.role">
                        <SelectTrigger class="w-full xl:max-w-40">
                            <SelectValue placeholder="Papel" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem :value="null">Todos</SelectItem>
                            <SelectItem :value="Role.OPERATOR"
                                >Operador</SelectItem
                            >
                            <SelectItem :value="Role.SELLER"
                                >Vendedor</SelectItem
                            >
                            <SelectItem :value="Role.ACCOUNTANT"
                                >Contador</SelectItem
                            >
                        </SelectContent>
                    </Select>
                </div>
                <Button @click="handleCreateUser" class="ms-auto w-40"
                    ><Plus /> Criar Usuário</Button
                >
            </div>
            <UsersTable
                :users="users"
                @edit="handleEditUser"
                @delete="handleDeleteUser"
            />
            <Pagination
                class="my-4 justify-center md:justify-end"
                v-slot="{ page }"
                :page="pagination.currentPage"
                :items-per-page="pagination.perPage"
                :total="pagination.total"
                :default-page="1"
                @update:page="filters.page = $event"
            >
                <PaginationContent
                    v-slot="{ items }"
                    class="flex flex-wrap gap-2"
                >
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
        </div>
        <UserFormModal
            v-model:isOpen="isUserFormModalOpen"
            :user="selectedUser"
        />
        <UserDeleteModal
            v-if="selectedUser"
            v-model:isOpen="isUserDeleteModalOpen"
            :user="selectedUser"
        />
    </div>
    <Toaster />
</template>
