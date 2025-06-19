<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodSellerAmount extends Model
{
    public $timestamps = true;
    // use HasFactory;
    protected $fillable = [
                'userid',
                'c_amount',
                'd_amount',
                'total',
                'datetime',
                'status',
                'adminid',
                'refno',
                'msg'
            ];
}
