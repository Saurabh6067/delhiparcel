<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    public $timestamps = true;
    use HasFactory;
    protected $table = 'enquirys';
    protected $primaryKey = 'id';
    protected $fillable = ['fullname', 'itemno', 'email', 'gst_panno', 'phoneno', 'category', 'fulladdress', 'message', 'gst_panno_img', 'pinCode', 'status'];

    public function cat()
    {
        return $this->hasOne(Category::class, 'id', 'category');
    }
}
