<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    public $timestamps = true;
    use HasFactory;
    protected $table = 'branchs';
    protected $primaryKey = 'id';
    protected $fillable = ['fullname', 'email', 'fulladdress', 'itemcount', 'phoneno', 'category', 'gst_panno', 'gst_panno_img', 'pincode', 'type', 'type_logo', 'password'];

    public function cat() {
        return $this->hasOne(Category::class, 'id', 'category');
    }
    
    // Total Coud Amount
        public function branch_total_cod() {
        return $this->hasOne(BranchtotalCod::class,'branch_id','id');
    }
    public function orders()
    {
        return $this->hasMany(Order::class, 'branch_id');
    }

    public function getTotalCodAmountAttribute()
    {
        return $this->orders()->sum('amount');
    }
}

