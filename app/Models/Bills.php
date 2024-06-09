<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bills extends Model
{
    protected $table = 'bills';
    protected $fillable = ['published', 'buyer', 'soldDate', 'kt', 'year', 'month', 'numPerYear', 'numPerMonth', 'payDate', 'payed'];
}