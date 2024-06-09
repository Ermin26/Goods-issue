<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingHolidays extends Model
{
    protected $table = 'pendingholidays';
    protected $fillable = ['from', 'to','status', 'days', 'aplyyDate', 'vacation_id'];
}