<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BilliardSession extends Model
{

    public function table()
    {
        return $this->belongsTo(BilliardTable::class, 'billiard_table_id');
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
}
