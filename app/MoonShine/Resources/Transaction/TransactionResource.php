<?php

namespace App\MoonShine\Resources\Transaction;

use App\Models\Transaction;
use App\Models\Account;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Checkbox;
use MoonShine\UI\Fields\Select;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Laravel\Fields\Relationships\HasMany;

class TransactionResource extends ModelResource
{
    protected string $model = Transaction::class;
    protected string $title = 'Транзакции';

    public function indexFields(): array
    {
        return [
            ID::make()->sortable(),
            Date::make('Дата', 'date')->sortable(),
            Text::make('Описание', 'description'),
            Checkbox::make('Проведена', 'posted'),
        ];
    }

    public function formFields(): array
    {
        return [
            Date::make('Дата', 'date')->required(),
            Text::make('Описание', 'description'),
            Checkbox::make('Проведена', 'posted')->default(false),
            HasMany::make('Проводки', 'journalEntries')
                ->fields([
                    Select::make('Счет', 'account_id')
                        ->options(Account::pluck('name', 'id')->toArray())
                        ->required(),
                    Text::make('Сумма', 'amount')->required(),
                    Select::make('Тип', 'type')
                        ->options(['debit' => 'Дебет', 'credit' => 'Кредит'])
                        ->required(),
                ]),
            // убрали все модификаторы
        ];
    }

    public function detailFields(): array
    {
        return [
            ID::make(),
            Date::make('Дата', 'date'),
            Text::make('Описание', 'description'),
            Checkbox::make('Проведена', 'posted'),
        ];
    }

    public function filters(): array
    {
        return [
            Date::make('Дата', 'date'),
            Text::make('Описание', 'description'),
        ];
    }

    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:255'],
            'posted' => ['boolean'],
        ];
    }
}