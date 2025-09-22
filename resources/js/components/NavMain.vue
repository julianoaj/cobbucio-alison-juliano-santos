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
import { useHomeStore } from '@/stores/home/useHomeStore';
import { storeToRefs } from 'pinia';

defineProps<{
    items: NavItem[];
}>();

const store = useHomeStore();

const {getActualUrl} = storeToRefs(store)

const hasSubItems = (item: NavItem) => item.subItems && item.subItems.length > 0;
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Plataforma</SidebarGroupLabel>
        <SidebarMenu>
            <Collapsible default-open v-for="item in items" :key="item.title" as-child class="group/collapsible">
                <SidebarMenuItem>
                    <CollapsibleTrigger asChild>
                        <SidebarMenuButton
                            :class="getActualUrl.includes(item.name) ? 'bg-primary/20' : ''"
                            :tooltip="item.title"
                            :as-child="!hasSubItems(item)"
                        >
                            <Link
                                v-if="!hasSubItems(item)"
                                :href="item.href"
                            >
                                <component :is="item.icon" v-if="item.icon" :size="18"/>
                                <span style="margin-left: 3px;">{{ item.title }}</span>
                            </Link>
                            <div
                                v-else
                                class="flex gap-2"
                            >
                                <component :is="item.icon" v-if="item.icon" :size="18"/>
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
