<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BilliardTable extends Model
{
    use HasFactory;
    public function sessions()
    {
        return $this->hasMany(BilliardSession::class);
    }
    public function session()
    {
        return $this->hasOne(BilliardSession::class)
                ->where('status', 'ongoing');
    }
}
