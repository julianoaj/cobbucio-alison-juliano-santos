<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import { Wallet } from '@/types/wallet';
import BalanceCard from './BalanceCard.vue';
import WalletActions from './WalletActions.vue';
import { onMounted, ref } from 'vue';
import { useHomeStore } from '@/stores/home/useHomeStore';
import axios from 'axios';
import TransactionTable from '@/components/wallet/TransactionTable.vue';
import { storeToRefs } from 'pinia';

interface Props {
    wallet: Wallet;
}

const store = useHomeStore();

const props = defineProps<Props>();

const { transactions } = storeToRefs(store);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Carteira',
        href: route('dashboard'),
    },
];

const loading = ref<boolean>(false);

onMounted(() => {
    store.setWallet(props.wallet);

    loading.value = true;
    axios
        .get(route('api.transaction.index'))
        .then((response) => {
            transactions.value = response.data;
        })
        .finally(() => {
            loading.value = false;
        });
});
</script>

<template>
    <Head title="Carteira" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-2">
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <BalanceCard :wallet="wallet" />
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <WalletActions />
                </div>
            </div>
            <div class="relative flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                <TransactionTable :loading="loading" />
            </div>
        </div>
    </AppLayout>
</template>

<style scoped></style>
