export type Wallet = {
    id: number;
    currency: string;
    balance: number;
}

export type TransactionItem = {
    id: number
    type: string
    amount: number | string
    to_user_id: number | null
    created_at: string
    updated_at: string
}

export type ModelTransactionValues = {
    balance: number
    email?: string
}
