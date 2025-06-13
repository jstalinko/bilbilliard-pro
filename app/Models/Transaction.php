<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public function session()
    {
        return $this->belongsTo(BilliardSession::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
}
