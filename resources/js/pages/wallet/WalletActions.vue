<script setup lang="ts">
import { defineEmits } from 'vue';
import { Banknote, ArrowRightLeft } from 'lucide-vue-next';

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
            <button
                type="button"
                class="flex rounded-md bg-secondary px-4 py-2 text-sm font-medium text-secondary-foreground shadow hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-secondary/50"
                @click="onClick('withdrawal')"
            >
                <Banknote class="w-4 h-4 mr-2 mt-[1.5px]" /> Depositar
            </button>
            <button
                type="button"
                class="flex rounded-md bg-secondary px-4 py-2 text-sm font-medium text-secondary-foreground shadow hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-secondary/50"
                @click="onClick('withdrawal')"
            >
                <ArrowRightLeft class="w-4 h-4 mr-2 mt-[1.5px]" /> Transferir
            </button>
        </div>
    </div>
</template>

<style scoped>
</style>
