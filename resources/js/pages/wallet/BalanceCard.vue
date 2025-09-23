<script setup lang="ts">
import { useWallet } from '@/composables/useWallet';
import { useHomeStore } from '@/stores/home/useHomeStore';
import { storeToRefs } from 'pinia';

const {formatAmount} = useWallet()

const store = useHomeStore();

const { wallet } = storeToRefs(store)

</script>

<template>
    <div class="flex h-full w-full items-center justify-center p-6">
        <div class="flex w-full max-w-sm flex-col items-center justify-center rounded-lg">
            <div class="text-sm text-muted-foreground">Saldo disponível</div>
            <div class="mt-2 text-4xl font-semibold tracking-tight">
                {{ formatAmount(Number(wallet.balance as unknown as number), (wallet as any).currency) }}
            </div>
        </div>
    </div>
</template>

<style scoped>
</style>
