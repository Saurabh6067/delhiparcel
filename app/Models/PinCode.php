<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinCode extends Model
{
    use HasFactory;
    protected $table = 'pincodes';
    protected $primaryKey = 'id';
    protected $fillable = ['pincodes'];
}
