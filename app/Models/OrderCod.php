<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderCod extends Model
{
    use HasFactory;
    protected $table = 'order_cod';
    protected $primaryKey = 'id';
    protected $fillable = ['userid', 'c_amount', 'd_amount', 'total', 'datetime', 'status', 'adminid', 'refno', 'msg'];

    public function users()
    {
        return $this->hasOne(User::class, 'id', 'adminid');
    }

    public function branch()
    {
        return $this->hasOne(Branch::class, 'id', 'userid');
    }
}
