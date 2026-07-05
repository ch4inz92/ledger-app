<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\JournalEntry;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    // счета
    $cash = Account::where('code', '01-01')->first();
    $revenue = Account::where('code', '02-01')->first();

    // ТрАнЗаКцИя 1
    $transaction = Transaction::create([
        'date' => now()->subDays(5),
        'description' => 'Оплата за услуги',
        'posted' => true,
    ]);

    JournalEntry::create([
        'transaction_id' => $transaction->id,
        'account_id' => $cash->id,
        'amount' => 1000.00,
        'type' => 'debit',
    ]);

    JournalEntry::create([
        'transaction_id' => $transaction->id,
        'account_id' => $revenue->id,
        'amount' => 1000.00,
        'type' => 'credit',
    ]);
}
}

//УРА КОНЕЦ