<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchCodHistory extends Model
{
    public $timestamps = true;
    use HasFactory;
    protected $table = 'branch_cod_history';
    protected $fillable = ['delivery_boy_id', 'amount', 'type', 'branch_id','datetime','status','remarks'];

}
