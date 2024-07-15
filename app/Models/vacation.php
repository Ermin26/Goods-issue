<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vacation extends Model
{
    protected $table = 'vacation';
    protected $fillable = ['user', 'last_year', 'holidays', 'used_holidays', 'overtime','employee_id'];
}