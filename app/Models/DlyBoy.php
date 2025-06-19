<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DlyBoy extends Model
{
    public $timestamps = true;
    use HasFactory;
    protected $table = 'dlyboy';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'email', 'phone', 'address', 'pincode', 'password', 'orderRate', 'userid'];
    
    public function deliverywallet()
    {
        return $this->hasOne(CodWallet::class, 'delivery_boy_id', 'id');
    }
    
    // Define the relationship with orders
    public function orders()
    {
        return $this->hasMany(Order::class, 'assign_to', 'id');
    }
    
}
