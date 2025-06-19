<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminTotalCod extends Model
{
    public $timestamps = true;
    use HasFactory;

    protected $table = 'admin_total_cod';

    protected $fillable = [
        'branch_id',
        'amount'
    ];

    /**
     * Get the branch that owns this record.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}