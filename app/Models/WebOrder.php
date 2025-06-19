<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebOrder extends Model
{
    use HasFactory;
    protected $table = 'web_orders';
    protected $fillable = ['order_id', 'service_type', 'service_id', 'pickupAddress', 'deliveryAddress', 'sender_name', 'sender_number', 'sender_email', 'sender_address', 'senderPinCode', 'receiver_name', 'receiver_number', 'receiver_email', 'receiver_address', 'receiverPinCode', 'payment_methods', 'codAmount', 'insurance', 'price', 'order_status', 'status_message', 'parcel_type', 'assign_to', 'datetime'];

    public function service()
    {
        return $this->hasOne(Service::class, 'id', 'service_id');
    }
    
    public function dlyBoy()
    {
        return $this->hasOne(DlyBoy::class, 'id', 'assign_to');
    }
}
