import { Reactive, reactive, ref } from 'vue';
import { Wallet } from '@/types/wallet';

export const page = ref('dashboard')

export const wallet: Reactive<Wallet> = reactive({
    id: 0,
    currency: '',
    balance: 0,
})
