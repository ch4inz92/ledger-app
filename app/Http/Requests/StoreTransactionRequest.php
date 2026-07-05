<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // для API обычно true
    }

    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:255'],
            'posted' => ['nullable', 'boolean'],
            'entries' => ['required', 'array', 'min:2'],
            'entries.*.account_id' => ['required', 'exists:accounts,id'],
            'entries.*.amount' => ['required', 'numeric', 'min:0.01'],
            'entries.*.type' => ['required', Rule::in(['debit', 'credit'])],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $entries = $this->input('entries');
            $debit = collect($entries)->where('type', 'debit')->sum('amount');
            $credit = collect($entries)->where('type', 'credit')->sum('amount');

            if (abs($debit - $credit) > 0.0001) {
                $validator->errors()->add('entries', 'Сумма дебета должна равняться сумме кредита.');
            }
        });
    }
}