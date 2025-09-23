import { Wallet } from '@/types/wallet';
import { wallet } from '@/stores/home/states';

export const setWallet = (newWallet: Wallet) => {
    wallet.id = newWallet.id;
    wallet.currency = newWallet.currency;
    wallet.balance = newWallet.balance;
}

