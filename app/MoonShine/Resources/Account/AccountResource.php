<?php

namespace App\MoonShine\Resources\Account;

use App\Models\Account;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Checkbox;
use MoonShine\UI\Fields\ID;
use MoonShine\Laravel\Resources\ModelResource;

class AccountResource extends ModelResource
{
    protected string $model = Account::class;
    protected string $title = 'Счета';

    // Поля для СПИСКА (index)
    public function indexFields(): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Название', 'name'),
            Text::make('Код', 'code'),
            Select::make('Тип', 'type')
                ->options([
                    'asset'     => 'Актив',
                    'liability' => 'Обязательство',
                    'equity'    => 'Капитал',
                    'revenue'   => 'Доход',
                    'expense'   => 'Расход',
                ]),
            Text::make('Баланс', 'balance'),
            Checkbox::make('Активен', 'is_active'),
        ];
    }

    // Поля для ФОРМЫ (создание/редактирование)
    public function formFields(): array
    {
        return [
            Text::make('Название', 'name')->required(),
            Text::make('Код', 'code')->required(), // <-- убрали ->unique()
            Select::make('Тип', 'type')
                ->options([
                    'asset'     => 'Актив',
                    'liability' => 'Обязательство',
                    'equity'    => 'Капитал',
                    'revenue'   => 'Доход',
                    'expense'   => 'Расход',
                ])->required(),
            Checkbox::make('Активен', 'is_active')->default(true),
        ];
    }

    // Поля для ДЕТАЛЬНОГО ПРОСМОТРА (detail)
    public function detailFields(): array
    {
        return [
            ID::make(),
            Text::make('Название', 'name'),
            Text::make('Код', 'code'),
            Select::make('Тип', 'type')
                ->options([
                    'asset'     => 'Актив',
                    'liability' => 'Обязательство',
                    'equity'    => 'Капитал',
                    'revenue'   => 'Доход',
                    'expense'   => 'Расход',
                ]),
            Checkbox::make('Активен', 'is_active'),
        ];
    }

    // Фильтры
    public function filters(): array
    {
        return [
            Text::make('Название', 'name'),
            Text::make('Код', 'code'),
            Select::make('Тип', 'type')
                ->options([
                    'asset'     => 'Актив',
                    'liability' => 'Обязательство',
                    'equity'    => 'Капитал',
                    'revenue'   => 'Доход',
                    'expense'   => 'Расход',
                ]),
        ];
    }

    public function actions(): array
    {
        return [];
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:accounts,code'], // уникальность здесь
            'type' => ['required', 'in:asset,liability,equity,revenue,expense'],
            'is_active' => ['boolean'],
        ];
    }
}