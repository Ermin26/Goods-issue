<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    protected $table = 'notifications';
    protected $fillable = ['employee_id', 'vacation_id', 'days', 'status', 'users_name'];
}