<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstimatedService extends Model
{
    protected $table = 'estimated_services';
    protected $fillable = ['service_type', 'time', 'status'];
}