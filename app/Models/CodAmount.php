<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodAmount extends Model
{
    use HasFactory;
    protected $table = 'cod_amount';
    protected $fillable = ['amount', 'delivery_boy_id', 'user_id', 'datetime'];

    public function users()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    
    public function branch()
    {
        return $this->hasOne(Branch::class, 'id', 'user_id');
    }

}
