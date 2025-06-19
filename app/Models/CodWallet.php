<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodWallet extends Model
{
    public $timestamps = true;
    use HasFactory;
    protected $table = 'cod_wallet';
    protected $fillable = ['delivery_boy_id', 'amount'];

}
