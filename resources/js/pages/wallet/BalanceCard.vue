<script setup lang="ts">
import type { Wallet } from '@/types/wallet';
import { useWallet } from '@/composables/useWallet';

const {formatAmount} = useWallet()

interface Props {
    wallet: Wallet
}

const props = defineProps<Props>();

</script>

<template>
    <div class="flex h-full w-full items-center justify-center p-6">
        <div class="flex w-full max-w-sm flex-col items-center justify-center rounded-lg">
            <div class="text-sm text-muted-foreground">Saldo disponível</div>
            <div class="mt-2 text-4xl font-semibold tracking-tight">
                {{ formatAmount(Number(props.wallet.balance as unknown as number), (props.wallet as any).currency) }}
            </div>
        </div>
    </div>
</template>

<style scoped>
</style>
