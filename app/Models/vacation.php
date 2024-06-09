<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vacation extends Model
{
    protected $table = 'vacation';
    protected $fillable = ['user', 'lastYear', 'holidays', 'usedHolidays', 'overtime'];
}