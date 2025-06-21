<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{

    protected $fillable = [
        'billiard_session_id',
        'transaction_id',
        'product_id',
        'quantity',
        'price',
        'subtotal'
    ];
    public function session()
    {
        return $this->belongsTo(BilliardSession::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
