<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bills extends Model
{
    protected $table = 'bills';
    protected $fillable = ['published', 'buyer', 'sold_date', 'kt', 'year', 'month', 'num_per_year', 'num_per_month', 'pay_date', 'payed', 'total'];
}