export const formatAmount = (amount: number, currency?: string) => {
    try {
        return new Intl.NumberFormat(undefined, {
            style: 'currency',
            currency: currency ?? 'BRL',
            maximumFractionDigits: 2,
        }).format(amount);
    } catch {
        return `${(amount ?? 0).toFixed(2)} ${currency ?? 'BRL'}`;
    }
}

export function useWallet() {
    return { formatAmount };
}
