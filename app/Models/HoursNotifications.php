<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoursNotifications extends Model
{
    protected $table = 'hoursnotifications';
    protected $fillable = ['employee_id', 'hoursePending_id', 'date', 'hours', 'status'];
}