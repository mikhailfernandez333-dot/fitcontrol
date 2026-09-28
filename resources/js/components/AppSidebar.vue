<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import {
    BarChart3,
    CheckCircle2,
    CreditCard,
    Dumbbell,
    LayoutDashboard,
    Users,
    Wallet,
} from '@lucide/vue'

import AppLogo from '@/components/AppLogo.vue'
import NavMain from '@/components/NavMain.vue'
import NavUser from '@/components/NavUser.vue'

import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar'

import type { NavItem } from '@/types'

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutDashboard,
    },
    {
        title: 'Clientes',
        href: '/clientes',
        icon: Users,
    },
    {
        title: 'Membresías',
        href: '/membresias',
        icon: CheckCircle2,
    },
    {
        title: 'Planes',
        href: '/planes',
        icon: Dumbbell,
    },
    {
        title: 'Pagos',
        href: '/pagos',
        icon: Wallet,
    },
    {
        title: 'Asistencias',
        href: '/asistencias',
        icon: CreditCard,
    },
    {
        title: 'Reportes',
        href: '/reportes',
        icon: BarChart3,
    },
]

const footerNavItems: NavItem[] = []
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <!-- Logo -->
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link href="/dashboard">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <!-- Menú principal -->
        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <!-- Usuario -->
        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
</template>