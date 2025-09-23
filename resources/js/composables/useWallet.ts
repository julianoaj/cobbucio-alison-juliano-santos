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

export const toNumber = (value: string | number): number => {
    if (typeof value === 'number') {
        return value;
    }
    const n = parseFloat(String(value));
    return Number.isFinite(n) ? n : 0;
};

export function useWallet() {
    return {
        formatAmount,
        toNumber
    };
}
