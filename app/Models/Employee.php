<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employee';
    protected $fillable = ['name', 'lastName', 'email', 'password', 'status', 'workingStatus'];
}