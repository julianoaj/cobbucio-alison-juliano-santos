<script setup lang="ts">
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
} from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { Collapsible, CollapsibleTrigger, CollapsibleContent } from '@/components/ui/collapsible';
import { ChevronRight } from 'lucide-vue-next';

defineProps<{
    items: NavItem[];
}>();

const hasSubItems = (item: NavItem) => item.subItems && item.subItems.length > 0;
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Plataforma</SidebarGroupLabel>
        <SidebarMenu>
            <Collapsible default-open v-for="item in items" :key="item.title" as-child class="group/collapsible">
                <SidebarMenuItem>
                    <CollapsibleTrigger asChild>
                        <SidebarMenuButton :tooltip="item.title"  :as-child="!hasSubItems(item)">
                            <Link v-if="!hasSubItems(item)" class="flex gap-2 items-center" :href="item.href">
                                <component class="text-2xl" :is="item.icon" v-if="item.icon" :size="18"/>
                                <span>{{ item.title }}</span>
                            </Link>
                            <div v-else class="flex gap-2 items-center">
                                <component class="text-2xl" :is="item.icon" v-if="item.icon" :size="18"/>
                                <span>{{ item.title }}</span>
                            </div>
                            <ChevronRight
                                v-if="hasSubItems(item)"
                                class="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90"
                            />
                        </SidebarMenuButton>
                    </CollapsibleTrigger>
                    <CollapsibleContent v-if="hasSubItems(item)">
                        <SidebarMenuSub>
                            <SidebarMenuSubItem v-for="subItem in item.subItems" :key="subItem.title">
                                <SidebarMenuSubButton as-child>
                                    <Link :href="subItem.href">
                                        {{ subItem.title }}
                                    </Link>
                                </SidebarMenuSubButton>
                            </SidebarMenuSubItem>
                        </SidebarMenuSub>
                    </CollapsibleContent>
                </SidebarMenuItem>
            </Collapsible>
        </SidebarMenu>
    </SidebarGroup>
</template>
