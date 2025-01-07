<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holidays extends Model
{
    protected $table = 'holidays';
    protected $fillable = ['from_date', 'to','status', 'days', 'apply_date', 'employee_id','user_name'];
}