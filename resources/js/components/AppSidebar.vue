<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { Banknote, BookOpen, Database, Folder, HeartIcon, LayoutGrid, Table } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title:'Meja Billiard',
        href:'/dashboard/billiards/tables',
        icon: Table
    },
    {
        title: 'Transaksi',
        href: '/dashboard/transactions',
        icon: Banknote
    },
    {
        title:'Data Master',
        href:'#',
        icon:Database,
        children:[
          
            {
                title:'Meja',
                href:'/dashboard/billiards'
            },
            {
                title:'Produk',
                href:'/dashboard/products'
            },
              {
                title: 'Users',
                href:'/dashboard/users'
            }
        ]
    }
];

const footerNavItems: NavItem[] = [
    {
        title: 'Support',
        href: 'https://saweria.com/javaradigital',
        icon: HeartIcon,
    },
    {
        title: 'Homepage',
        href: 'https://javaradigital.com',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
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
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
