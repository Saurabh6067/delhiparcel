<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DlyBoy extends Model
{
    use HasFactory;
    protected $table = 'dlyboy';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'email', 'phone', 'address', 'pincode', 'password', 'orderRate', 'userid'];

}
