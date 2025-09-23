<script setup lang="ts">
import { computed, ref } from 'vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow, TableEmpty } from '@/components/ui/table';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
    DropdownMenuGroup,
    DropdownMenuShortcut,
} from '@/components/ui/dropdown-menu';
import { Ellipsis, LucideUndo } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import AlertConfirm from '@/components/confirmation/AlertConfirm.vue';
import { useWallet } from '@/composables/useWallet';
import { useHomeStore } from '@/stores/home/useHomeStore';
import { storeToRefs } from 'pinia';
import { TransactionItem } from '@/types/wallet';
import { usePage } from '@inertiajs/vue3';
import moment from 'moment';

defineProps<{
    loading?: boolean;
}>();


const store = useHomeStore();

const { transactions } = storeToRefs(store);

const {formatAmount, toNumber} = useWallet()

const hasItems = computed(() => transactions.value && transactions.value.length > 0);

const confirmOpen = ref(false);
const selectedId = ref<number | null>(null);

const openConfirmFor = (id: number): void => {
    selectedId.value = id;
    confirmOpen.value = true;
};

const handleConfirm = (): void => {
    if (selectedId.value !== null) {
        store.requestUpdateTransaction(selectedId.value, 'refund');
    }

    selectedId.value = null;
    confirmOpen.value = false;
};

const transcriptType = (transaction: TransactionItem): string => {
    if (transaction.status === 'refunded') {
        return 'Transferência (Reembolso)';
    }

    if (transferReceived(transaction)) {
        return 'Recebido';
    }

    switch (transaction.type) {
        case 'deposit':
            return 'Depósito';
        case 'withdraw':
            return 'Saque';
        case 'transfer':
            return 'Transferência';
        case 'revert':
            return 'Reversão';
        default:
            return transaction.type;
    }
};

const transferReceived = (transaction: TransactionItem): boolean => {
    return transaction.to_user_id !== null && usePage().props.auth.user.id === transaction.to_user_id;
};

const formatDate = (value: string | number | Date | null | undefined): string => {
    if (!value) {
        return '-';
    }

    const m = moment(value);
    if (!m.isValid()) {
        return '-';
    }

    return m.format('DD/MM/YYYY HH:mm');
};

const isWithinMinutes = (value: string | number | Date | null | undefined, minutes = 10): boolean => {
    if (!value) return false;
    const m = moment(value);
    if (!m.isValid()) return false;
    const diffMinutes = moment().diff(m, 'minutes');
    return diffMinutes <= minutes;
};
</script>

<template>
    <div class="p-4">
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>Tipo</TableHead>
                    <TableHead>Valor</TableHead>
                    <TableHead>Para/De</TableHead>
                    <TableHead>Criado em</TableHead>
                    <TableHead class="w-[1%] text-right">Ações</TableHead>
                </TableRow>
            </TableHeader>

            <TableBody v-if="hasItems">
                <TableRow v-for="row in transactions" :key="row.id">
                    <TableCell class="capitalize">{{ transcriptType(row) }}</TableCell>
                    <TableCell>{{ formatAmount(toNumber(row.amount)) }}</TableCell>
                    <TableCell>{{ row.to_user ? row.to_user.name : '-' }}</TableCell>
                    <TableCell>{{ formatDate(row.created_at) }}</TableCell>
                    <TableCell class="text-right">
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <Button variant="ghost">
                                    <ellipsis />
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent class="w-46" :side-offset="4" align="end">
                                <DropdownMenuGroup>
                                    <DropdownMenuItem
                                        v-if="row.type === 'transfer'
                                        && isWithinMinutes(row.created_at, 300)
                                        && row.status !== 'refunded'
                                        && !transferReceived(row)"
                                        @click="openConfirmFor(row.id)"
                                    >
                                        Reverter
                                        <DropdownMenuShortcut>
                                            <LucideUndo />
                                        </DropdownMenuShortcut>
                                    </DropdownMenuItem>
                                    <DropdownMenuItem v-else>
                                        Nenhuma ação
                                    </DropdownMenuItem>
                                </DropdownMenuGroup>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </TableCell>
                </TableRow>
            </TableBody>

            <TableEmpty v-else>
                <div v-if="loading" class="text-muted-foreground">Carregando transações…</div>
                <div v-else class="text-muted-foreground">Nenhuma transação encontrada.</div>
            </TableEmpty>
        </Table>

        <AlertConfirm
            :open="confirmOpen"
            @update:open="(val) => (confirmOpen = val)"
            dialogTitle="Confirmar reversão"
            dialogDescription="Tem certeza que deseja reverter esta transação?"
            btnLabel="Reverter"
            @confirm="handleConfirm"
        />
    </div>
</template>
