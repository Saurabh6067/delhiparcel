<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $timestamps = true;
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $fillable = ['pickupAddress', 'deliveryAddress', 'receiver_name', 'receiver_cnumber', 'receiver_email', 'receiver_add', 'receiver_pincode', 'sender_name', 'sender_number', 'sender_email', 'sender_address', 'sender_pincode', 'service_type', 'service_title', 'service_price', 'order_id', 'seller_id', 'price', 'payment_mode', 'codAmount', 'insurance', 'order_status', 'status_message', 'parcel_type', 'assign_to', 'assign_by', 'sender_order_pin', 'sender_order_pin_by', 'cod_submitted', 'sender_order_status', 'datetime', 'delivery_time'];

    public function order()
    {
        return $this->hasOne(Branch::class, 'id', 'seller_id');
    }
    // addon function for admin controller 
    public function assigb_by_order()
    {
       return $this->hasOne(Branch::class, 'id', 'assign_by');
    }

    public function dlyBoy()
    {
        return $this->hasOne(DlyBoy::class, 'id', 'assign_to');
    }

    public function dlyBoy1()
    {
        return $this->hasOne(DlyBoy::class, 'id', 'sender_order_pin_by');
    }

    public function deliveryBoy()
    {
        return $this->belongsTo(DlyBoy::class, 'assign_to', 'id');
    }
}