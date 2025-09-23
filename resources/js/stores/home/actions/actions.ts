import { ModelTransactionValues, TransactionItem, Wallet } from '@/types/wallet';
import { transactions, wallet } from '@/stores/home/states';
import axios from 'axios';
import { toast } from 'vue-sonner';

export const setWallet = (newWallet: Wallet) => {
    wallet.id = newWallet.id;
    wallet.currency = newWallet.currency;
    wallet.balance = newWallet.balance;
}

export const requestCreateTransaction = async (form: ModelTransactionValues, typeTransaction: string): Promise<void> => {
    await axios
        .post(route('transaction.store'), {
            type: typeTransaction,
            amount: form.balance,
            email: form?.email ?? '',
        })
        .then((response) => {
            transactions.value.unshift(response.data as TransactionItem);

            wallet.balance = (Number(wallet.balance) + Number(form.balance)).toFixed(2) as unknown as number;

            const messageToast: string = typeTransaction === 'transfer'
                ? 'Valor transferido com sucesso!'
                : 'Valor depositado com sucesso!';

            toast(messageToast, {
                description: '',
                action: {
                    label: 'Ok',
                },
            });
        })
        .catch((error) => {
            toast(error.response.data.message as string, {
                style: { background: '#f87171'},
                description: 'Verifique os dados e tente novamente.',
                action: {
                    label: 'Ok',
                },
            });
        })
}

