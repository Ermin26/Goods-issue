<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingHolidays extends Model
{
    protected $table = 'holidays';
    protected $fillable = ['from', 'to','status', 'days', 'aplyy_date', 'employee_id'];
}