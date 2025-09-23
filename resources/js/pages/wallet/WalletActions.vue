<script setup lang="ts">
import { defineEmits } from 'vue';
import { Banknote, ArrowRightLeft } from 'lucide-vue-next';
import DepositDialog from '@/components/wallet/DepositDialog.vue';
import TransactionDialog from '@/components/wallet/TransactionDialog.vue';

const emit = defineEmits<{
    (e: 'deposit'): void
    (e: 'withdrawal'): void
    (e: 'transfer'): void
}>();

const onClick = (action: 'deposit' | 'withdrawal' | 'transfer'): void => {
    emit(action);
}
</script>

<template>
    <div class="flex h-full w-full items-center justify-center p-6">
        <div class="flex w-full max-w-md items-center justify-center gap-3">
            <DepositDialog
                btn-label="Depositar"
                :btn-icon="Banknote"
            />
            <TransactionDialog
                btn-label="Transferir"
                :btn-icon="ArrowRightLeft"
            />
        </div>
    </div>
</template>

<style scoped>
</style>
