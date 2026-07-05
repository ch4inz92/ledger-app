<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\Transaction;
use App\Models\JournalEntry;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    public function store(StoreTransactionRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $transaction = Transaction::create([
            'date' => $validated['date'],
            'description' => $validated['description'] ?? null,
            'posted' => $validated['posted'] ?? false,
        ]);

        foreach ($validated['entries'] as $entry) {
            JournalEntry::create([
                'transaction_id' => $transaction->id,
                'account_id' => $entry['account_id'],
                'amount' => $entry['amount'],
                'type' => $entry['type'],
            ]);
        }

        return response()->json($transaction->load('journalEntries'), 201);
    }
}