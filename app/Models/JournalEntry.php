<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    protected $fillable = ['transaction_id', 'account_id', 'amount', 'type'];

     protected static function booted(): void
    {
        // Запрет создания проводки для проведённой транзакции
        static::creating(function ($journalEntry) {
            if ($journalEntry->transaction && $journalEntry->transaction->posted) {
                throw new \Exception('Нельзя добавить проводку к проведённой транзакции');
            }
        });

        // Запрет удаления проводки проведённой транзакции
        static::deleting(function ($journalEntry) {
            if ($journalEntry->transaction && $journalEntry->transaction->posted) {
                throw new \Exception('Нельзя удалить проводку проведённой транзакции');
            }
        });

        // Запрет редактирования проводки проведённой транзакции
        static::updating(function ($journalEntry) {
            if ($journalEntry->transaction && $journalEntry->transaction->posted) {
                throw new \Exception('Нельзя редактировать проводку проведённой транзакции');
            }
        });
    }
    
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
