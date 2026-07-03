<?php

namespace Database\Factories;

use App\Models\JournalEntry;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class JournalEntryFactory extends Factory
{
    protected $model = JournalEntry::class;

    public function definition(): array
    {
        return [
            'transaction_id' => Transaction::factory(),
            'account_id' => Account::factory(),
            'amount' => $this->faker->randomFloat(2, 1, 1000),
            'type' => $this->faker->randomElement(['debit', 'credit']),
        ];
    }
}