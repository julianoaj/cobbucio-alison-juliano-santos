<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { LayoutGrid, WalletIcon } from 'lucide-vue-next';

const mainNavItems: NavItem[] = [
    {
        name: 'dashboard',
        title: 'Dashboard',
        href: route('dashboard'),
        icon: LayoutGrid
    },
    {
        name: 'wallet',
        title: 'Carteira',
        href: '',
        icon: WalletIcon,
        subItems: [
            {
                name: 'wallet',
                title: 'Inicio',
                href: route('wallet.show'),
            },
            {
                name: 'walletTransfer',
                title: 'Transações',
                href: '',
            },
        ],
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child />
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
