<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    protected $table = 'branchs';
    protected $primaryKey = 'id';
    protected $fillable = ['fullname', 'email', 'fulladdress', 'itemcount', 'phoneno', 'category', 'gst_panno', 'gst_panno_img', 'pincode', 'type', 'type_logo', 'password'];

    public function cat() {
        return $this->hasOne(Category::class, 'id', 'category');
    }
}
