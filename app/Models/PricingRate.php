<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PricingRate extends Model
{
    
    protected $fillable = [
        'name',
        'price_per_hour',
        'start_at',
        'end_at'
    ];
}
