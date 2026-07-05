<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
     use HasFactory;

    protected $fillable = ['name', 'code', 'type', 'is_active'];

    public function journalEntries()
    {
        return $this->hasMany(JournalEntry::class);
    }

    public function getBalanceAttribute(): float
    {
        $debit = $this->journalEntries()->where('type', 'debit')->sum('amount');
        $credit = $this->journalEntries()->where('type', 'credit')->sum('amount');
        return $debit - $credit;
    }
}
