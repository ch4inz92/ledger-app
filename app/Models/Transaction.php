<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
     use HasFactory;

    protected $fillable = ['date', 'description', 'posted'];

    public function journalEntries()
    {
        return $this->hasMany(JournalEntry::class);
    }

    protected static function booted(): void
    {
        static::saving(function ($transaction) {
            if ($transaction->posted) {
                $entries = $transaction->journalEntries;
                
                // Проверяем, что есть хотя бы одна проводка
                if ($entries->isEmpty()) {
                    throw new \Exception('Нельзя провести транзакцию без проводок');
                }

                $debit = $entries->where('type', 'debit')->sum('amount');
                $credit = $entries->where('type', 'credit')->sum('amount');

                if (abs($debit - $credit) > 0.0001) {
                    throw new \Exception(
                        'Нельзя провести транзакцию: сумма дебета (' . $debit . 
                        ') не равна сумме кредита (' . $credit . ')'
                    );
                }
            }
        });

        static::updating(function ($transaction) {
            // Если транзакция уже проведена, запрещаем любые изменения
            if ($transaction->posted && $transaction->isDirty()) {
                $dirty = $transaction->getDirty();
                // Разрешаем только изменение поля posted (с false на true), но оно уже было проверено в saving
                if (count($dirty) === 1 && array_key_exists('posted', $dirty)) {
                    return;
                }
                throw new \Exception('Нельзя редактировать проведённую транзакцию');
            }
        });

        static::deleting(function ($transaction) {
            if ($transaction->posted) {
                throw new \Exception('Нельзя удалять проведённую транзакцию');
            }
        });
    }
}
