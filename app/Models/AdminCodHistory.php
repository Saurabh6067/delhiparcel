<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminCodHistory extends Model
{
    public $timestamps = true;
    use HasFactory;

    protected $table = 'admin_cod_history';

    protected $fillable = [
        'branch_id',
        'branch_cod_history_id',  // addon 
        'amount',
        'type',
        'status',
        'datetime',
        'remarks'
    ];

    /**
     * Get the branch that owns this record.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}