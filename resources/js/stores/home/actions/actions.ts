import { ModelTransactionValues, TransactionItem, Wallet } from '@/types/wallet';
import { loadingRequests, transactions, wallet } from '@/stores/home/states';
import axios from 'axios';
import { toast } from 'vue-sonner';

export const setWallet = (newWallet: Wallet) => {
    wallet.id = newWallet.id;
    wallet.currency = newWallet.currency;
    wallet.balance = newWallet.balance;
}

export const requestCreateTransaction = async (form: ModelTransactionValues, typeTransaction: string): Promise<void> => {
    loadingRequests.createTransaction = true;

    await axios
        .post(route('transaction.store'), {
            type: typeTransaction,
            amount: form.balance,
            email: form?.email ?? '',
        })
        .then((response) => {
            transactions.value.unshift(response.data as TransactionItem);

            if (typeTransaction === 'deposit')
                wallet.balance = (Number(wallet.balance) + Number(form.balance)).toFixed(2) as unknown as number;
            else if (typeTransaction === 'transfer') {
                wallet.balance = (Number(wallet.balance) - Number(form.balance)).toFixed(2) as unknown as number;
            }

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
        .finally(() => loadingRequests.createTransaction = false);
}

export const requestUpdateTransaction = async (transactionId: number, typeTransaction: string): Promise<void> => {
    loadingRequests.updateTransaction = true;

    await axios.patch(route('transaction.update', {transaction: transactionId}), {
        status: typeTransaction
    })
    .then((response) => {
        const payload = response.data as TransactionItem;

        const indexTransaction = transactions.value.findIndex(item => item.id === transactionId);

        if (indexTransaction !== -1) {
            transactions.value[indexTransaction] = payload;

            wallet.balance = (Number(wallet.balance) + Number(payload.amount)).toFixed(2) as unknown as number;

            toast('Reembolso realizado com sucesso!', {
                description: '',
                action: {
                    label: 'Ok',
                },
            });
        }
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
    .finally(() => loadingRequests.updateTransaction = false);
}

