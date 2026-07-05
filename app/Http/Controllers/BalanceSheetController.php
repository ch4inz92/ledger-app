<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BalanceSheetController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $accounts = Account::all();
        $report = [];

        foreach ($accounts as $account) {
            $openingBalance = DB::table('journal_entries')
                ->join('transactions', 'journal_entries.transaction_id', '=', 'transactions.id')
                ->where('journal_entries.account_id', $account->id)
                ->where('transactions.date', '<', $startDate)
                ->selectRaw("SUM(CASE WHEN journal_entries.type = 'debit' THEN journal_entries.amount ELSE -journal_entries.amount END) as balance")
                ->value('balance') ?? 0;

            $debitTurnover = DB::table('journal_entries')
                ->join('transactions', 'journal_entries.transaction_id', '=', 'transactions.id')
                ->where('journal_entries.account_id', $account->id)
                ->whereBetween('transactions.date', [$startDate, $endDate])
                ->where('journal_entries.type', 'debit')
                ->sum('journal_entries.amount');

            $creditTurnover = DB::table('journal_entries')
                ->join('transactions', 'journal_entries.transaction_id', '=', 'transactions.id')
                ->where('journal_entries.account_id', $account->id)
                ->whereBetween('transactions.date', [$startDate, $endDate])
                ->where('journal_entries.type', 'credit')
                ->sum('journal_entries.amount');

            $closingBalance = $openingBalance + $debitTurnover - $creditTurnover;

            $report[] = [
                'account' => $account->name . ' (' . $account->code . ')',
                'opening_debit' => in_array($account->type, ['asset', 'expense']) ? $openingBalance : 0,
                'opening_credit' => in_array($account->type, ['liability', 'equity', 'revenue']) ? $openingBalance : 0,
                'debit_turnover' => $debitTurnover,
                'credit_turnover' => $creditTurnover,
                'closing_debit' => in_array($account->type, ['asset', 'expense']) ? $closingBalance : 0,
                'closing_credit' => in_array($account->type, ['liability', 'equity', 'revenue']) ? $closingBalance : 0,
            ];
        }

        return view('balance-sheet', compact('startDate', 'endDate', 'report'));
    }
}