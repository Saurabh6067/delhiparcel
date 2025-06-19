<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchtotalCod extends Model
{
    public $timestamps = true;
    use HasFactory;
    protected $table = 'branch_total_cod';
    protected $fillable = ['delivery_boy_id', 'amount', 'branch_id'];

}
