<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodAmount extends Model
{
    public $timestamps = true;
    use HasFactory;
    protected $table = 'cod_amount';
    protected $fillable = ['record_id','amount', 'delivery_boy_id', 'user_id', 'transfer_to_branch', 'remarks', 'affected_orders', 'type', 'datetime','status'];

    public function users()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function branch()
    {
        return $this->hasOne(Branch::class, 'id', 'user_id');
    }
    
   

}
