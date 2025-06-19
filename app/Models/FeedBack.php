<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedBack extends Model
{
    public $timestamps = true;
    use HasFactory;
    protected $table = 'feed_backs';
    protected $fillable = ['name', 'email', 'phone', 'message', 'status', 'datetime'];
}
