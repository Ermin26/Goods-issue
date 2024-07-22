<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable implements AuthenticatableContract
{
    protected $table = 'employee';
    protected $fillable = ['user_name','name', 'last_name', 'email', 'password', 'status', 'working_status'];
}