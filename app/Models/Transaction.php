<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['date', 'description', 'posted'];

    public function journalEntries()
    {
        return $this->hasMany(JournalEntry::class);
    }
}
