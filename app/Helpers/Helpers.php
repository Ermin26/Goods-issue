<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class Helpers
{
    public static function checkDatabaseConnection()
    {
        try {
            DB::connection()->getDatabaseName();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}