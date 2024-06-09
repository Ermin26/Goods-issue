<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoursPending extends Model
{
    protected $table = 'hourspending';
    protected $fillable = ['date', 'days', 'hours', 'status', 'employee'];
}