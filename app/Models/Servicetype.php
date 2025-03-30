<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicetype extends Model
{
    use HasFactory;
    protected $table = 'servicetypes';
    protected $primaryKey = 'id';
    protected $fillable = ['userId', 'services', 'servicesType', 'servicesId'];

    public function services()
    {
        return $this->hasOne(Service::class, 'id', 'servicesId');
    }
}
