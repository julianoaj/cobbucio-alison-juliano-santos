import { Reactive, reactive, ref } from 'vue';
import { TransactionItem, Wallet } from '@/types/wallet';

export const page = ref('dashboard')

export const wallet: Reactive<Wallet> = reactive({
    id: 0,
    currency: '',
    balance: 0,
})

export const transactions = ref<TransactionItem[]>([]);

export const loadingRequests = reactive({
    createTransaction: false,
    updateTransaction: false,
})
