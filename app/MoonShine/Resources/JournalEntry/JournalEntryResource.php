<?php

namespace App\MoonShine\Resources\JournalEntry;

use App\Models\JournalEntry;
use App\Models\Account;
use App\Models\Transaction;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;
use MoonShine\Laravel\Resources\ModelResource;

class JournalEntryResource extends ModelResource
{
    protected string $model = JournalEntry::class;
    protected string $title = 'Проводки';

    public function indexFields(): array
    {
        return [
            ID::make()->sortable(),
            Select::make('Транзакция', 'transaction_id')
                ->options(Transaction::pluck('description', 'id')->toArray()),
            Select::make('Счет', 'account_id')
                ->options(Account::pluck('name', 'id')->toArray()),
            Text::make('Сумма', 'amount'),
            Select::make('Тип', 'type')
                ->options(['debit' => 'Дебет', 'credit' => 'Кредит']),
        ];
    }

    public function formFields(): array
    {
        return [
            Select::make('Транзакция', 'transaction_id')
                ->options(Transaction::pluck('description', 'id')->toArray())
                ->required(),
            Select::make('Счет', 'account_id')
                ->options(Account::pluck('name', 'id')->toArray())
                ->required(),
            Text::make('Сумма', 'amount')->required(),
            Select::make('Тип', 'type')
                ->options(['debit' => 'Дебет', 'credit' => 'Кредит'])
                ->required(),
        ];
    }

    public function rules(): array
    {
        return [
            'transaction_id' => ['required', 'exists:transactions,id'],
            'account_id' => ['required', 'exists:accounts,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'type' => ['required', 'in:debit,credit'],
        ];
    }
}