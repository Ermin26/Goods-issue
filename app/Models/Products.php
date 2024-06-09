<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $fillable = ['name', 'qty', 'price', 'firstOfWeek', 'total', 'bills_id'];
}