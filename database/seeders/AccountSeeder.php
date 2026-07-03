<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Account;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $accounts = [
        ['name' => 'Касса', 'code' => '01-01', 'type' => 'asset'],
        ['name' => 'Расчетный счет', 'code' => '01-02', 'type' => 'asset'],
        ['name' => 'Доходы от реализации', 'code' => '02-01', 'type' => 'revenue'],
        ['name' => 'Расходы на материалы', 'code' => '03-01', 'type' => 'expense'],
    ];

    foreach ($accounts as $account) {
        Account::create($account);
    }
}
}
