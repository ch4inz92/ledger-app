<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function transactions()
    {
        $transactions = Transaction::with('journalEntries')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=windows-1251',
            'Content-Disposition' => 'attachment; filename="transactions.csv"',
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');

            // sep=; для Excel
            fputcsv($file, ['sep=;'], ';');

            // Заголовки
            $header = ['ID', 'Дата', 'Описание', 'Проведена', 'Сумма дебета', 'Сумма кредита'];
            $header = array_map(fn($h) => iconv('UTF-8', 'Windows-1251//IGNORE', $h), $header);
            fputcsv($file, $header, ';');

            foreach ($transactions as $t) {
                $row = [
                    $t->id,
                    $t->date,
                    $t->description,
                    $t->posted ? 'Да' : 'Нет',
                    $t->journalEntries->where('type', 'debit')->sum('amount'),
                    $t->journalEntries->where('type', 'credit')->sum('amount'),
                ];
                $row = array_map(fn($v) => iconv('UTF-8', 'Windows-1251//IGNORE', (string)$v), $row);
                fputcsv($file, $row, ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}