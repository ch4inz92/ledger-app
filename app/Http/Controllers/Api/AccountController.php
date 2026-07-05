<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\JsonResponse;

class AccountController extends Controller
{
    public function balance(Account $account): JsonResponse
    {
        $debit = $account->journalEntries()->where('type', 'debit')->sum('amount');
        $credit = $account->journalEntries()->where('type', 'credit')->sum('amount');
        $balance = $debit - $credit;

        return response()->json([
            'account_id' => $account->id,
            'balance' => $balance,
        ]);
    }
}