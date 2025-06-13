<script setup lang="ts">
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem ,  SidebarMenuSub,
    SidebarMenuSubItem,
    SidebarMenuSubButton} from '@/components/ui/sidebar';
import { type NavItem } from '@/types';

import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible'
import { Link, usePage } from '@inertiajs/vue3'
import { ChevronRight } from 'lucide-vue-next'


const isParentActive = (item: NavItem, currentUrl: string): boolean => {
    return item.children?.some((child: NavItem) => child.href === currentUrl) ?? false
}
defineProps<{
    items: NavItem[];
}>();

const page = usePage();
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Platform</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem v-for="item in items" :key="item.title">
                <!-- Menu tanpa submenu -->
                <template v-if="!item.children">
                    <SidebarMenuButton 
                        as-child 
                        :is-active="item.href === page.url" 
                        :tooltip="item.title"
                    >
                        <Link :href="item.href">
                            <component :is="item.icon" />
                            <span>{{ item.title }}</span>
                        </Link>
                    </SidebarMenuButton>
                </template>

                <!-- Menu dengan submenu (collapsible) -->
                <template v-else>
                    <Collapsible 
                        :default-open="isParentActive(item , page.url)"
                        class="group/collapsible"
                    >
                        <SidebarMenuButton as-child tooltip="">
                            <CollapsibleTrigger class="flex w-full items-center">
                                <component :is="item.icon" />
                                <span>{{ item.title }}</span>
                                <ChevronRight class="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90" />
                            </CollapsibleTrigger>
                        </SidebarMenuButton>
                        
                        <CollapsibleContent>
                            <SidebarMenuSub>
                                <SidebarMenuSubItem v-for="subItem in item.children" :key="subItem.title">
                                    <SidebarMenuSubButton 
                                        as-child
                                        :is-active="subItem.href === page.url"
                                    >
                                        <Link :href="subItem.href">
                                            <component v-if="subItem.icon" :is="subItem.icon" />
                                            <span>{{ subItem.title }}</span>
                                        </Link>
                                    </SidebarMenuSubButton>
                                </SidebarMenuSubItem>
                            </SidebarMenuSub>
                                        </CollapsibleContent>
                    </Collapsible>
                </template>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>