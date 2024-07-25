<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'product_id', 'quantity', 'date_investment'
    ];
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
