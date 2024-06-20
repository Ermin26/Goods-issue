<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Costs extends Model
{
    protected $table = 'costs';
    protected $fillable = ['date', 'products', 'price', 'booked_date', 'users_name'];
}