<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import {
    // ClipboardList,
    // Landmark,
    // ShoppingBasket,
    LayoutGrid,
    Users,
} from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogo from '@/components/AppLogo.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { Role } from '@/lib/enums/role';
import { dashboard } from '@/routes';
import {
    users,
    // finances,
    // orders,
    // products
} from '@/routes/dashboard';
import type { NavItem, NavItemWithRole } from '@/types';

const page = usePage();
const user = computed(() => page.props.auth.user);

const navItemsWithRole: NavItemWithRole[] = [
    {
        title: 'Painel',
        href: dashboard(),
        icon: LayoutGrid,
    },
    // {
    //     title: 'Pedidos',
    //     href: orders(),
    //     icon: ClipboardList,
    //     roles: [Role.ADMIN, Role.SELLER],
    // },
    // {
    //     title: 'Produtos',
    //     href: products(),
    //     icon: ShoppingBasket,
    //     roles: [Role.ADMIN, Role.OPERATOR],
    // },
    // {
    //     title: 'Finanças',
    //     href: finances(),
    //     icon: Landmark,
    //     roles: [Role.ADMIN, Role.ACCOUNTANT],
    // },
    {
        title: 'Usuários',
        href: users(),
        icon: Users,
        roles: [Role.ADMIN],
    },
];

const mainNavItems = computed(() => {
    const userRole = user.value.role;

    return navItemsWithRole
        .filter(({ roles }) => {
            return !roles ? true : roles.includes(userRole);
        })
        .map(
            // eslint-disable-next-line @typescript-eslint/no-unused-vars
            ({ roles, ...navItem }) => navItem as NavItem,
        );
});
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
