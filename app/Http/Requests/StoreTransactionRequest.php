<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', 'in:deposit,withdrawal,transfer'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'to_user_id' => ['required_if:type,transfer', 'exists:users,id']
        ];
    }

    public function messages(): array
    {
        return [
            'type.in' => 'O tipo de transação deve ser depósito, retirada ou transferência.',
            'amount.min' => 'Por favor insira um valor maior que 0.',
            'to_user_id.exists' => 'O usuário destinatário não existe.',
        ];
    }
}
