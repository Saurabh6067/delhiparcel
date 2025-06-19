<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class COD extends Model
{
    public $timestamps = true;
    use HasFactory;
    public $table = 'cod';
    protected $fillable = [
        'order_id',
        'delivery_boy_id',
        'pyment_method',
        'datetime'
    ];
    
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function deliveryBoy()
    {
        return $this->hasOne(DlyBoy::class, 'id', 'delivery_boy_id');
    }
}
