<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'nit', 'phone', 'name_client', 'date_facture', 'tot', 'status'
    ];
}
