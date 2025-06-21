<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'type',
        'payment_method',
        'paid_amount',
        'total_amount',
        'change',
        'tx_status'
    ];
    public function session()
    {
        return $this->belongsTo(BilliardSession::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
}
