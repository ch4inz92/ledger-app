<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\JournalEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;

class TransactionApiTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    }

    public function test_can_create_transaction_with_valid_entries()
    {
        $account1 = Account::factory()->create();
        $account2 = Account::factory()->create();

        $response = $this->postJson('/api/transactions', [
            'date' => '2026-07-04',
            'description' => 'API test',
            'entries' => [
                ['account_id' => $account1->id, 'amount' => 100, 'type' => 'debit'],
                ['account_id' => $account2->id, 'amount' => 100, 'type' => 'credit'],
            ],
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['id', 'date', 'description', 'journal_entries']);
    }

    public function test_cannot_create_transaction_with_unequal_sum()
    {
        $account1 = Account::factory()->create();
        $account2 = Account::factory()->create();

        $response = $this->postJson('/api/transactions', [
            'date' => '2026-07-04',
            'description' => 'API test',
            'entries' => [
                ['account_id' => $account1->id, 'amount' => 100, 'type' => 'debit'],
                ['account_id' => $account2->id, 'amount' => 50, 'type' => 'credit'],
            ],
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['entries']);
    }

    public function test_can_get_account_balance()
    {
        $account = Account::factory()->create();
        Transaction::factory()->create()->journalEntries()->create([
            'account_id' => $account->id,
            'amount' => 100,
            'type' => 'debit',
        ]);

        $response = $this->getJson("/api/accounts/{$account->id}/balance");
        $response->assertStatus(200)
                 ->assertJson(['balance' => 100]);
    }
}