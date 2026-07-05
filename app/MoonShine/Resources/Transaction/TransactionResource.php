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
use MoonShine\Laravel\Actions\ExportAction;
use MoonShine\UI\Components\ActionButton;
use App\MoonShine\Actions\ExportTransactionsAction;

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
        ];
    }

    public function detailFields(): array
    {
        return [
            ID::make(),
            Date::make('Дата', 'date'),
            Text::make('Описание', 'description'),
            Checkbox::make('Проведена', 'posted'),
            HasMany::make('Проводки', 'journalEntries')
                ->fields([
                    Select::make('Счет', 'account_id')
                        ->options(Account::pluck('name', 'id')->toArray()),
                    Text::make('Сумма', 'amount'),
                    Select::make('Тип', 'type')
                        ->options(['debit' => 'Дебет', 'credit' => 'Кредит']),
                ]),
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

    public function indexButtons(): array
    {
        return [
            ActionButton::make('Экспорт в CSV', route('export.transactions'))
                ->icon('heroicons.outline.download')
                ->primary(),
        ];
    }

    public function indexActions(): array
    {
        return [
            ExportTransactionsAction::make('Экспорт в CSV')
                ->icon('heroicons.outline.download')
                ->primary()
                ->bulk(),
        ];
    }
}