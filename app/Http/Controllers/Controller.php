<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Helpers\Helpers;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\User;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function registerUser(Request $request){
        // Validacija podatkov
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string',
        ]);
        
        // Ustvari novega uporabnika (predpostavka: imaš model User)
        if ($validator->fails()) {
            return redirect()->route('home')->withErrors($validator)->withInput();
        } else {
            // Ustvari novega uporabnika (predpostavka: imaš model User)
            $user = new User([
                'name' => $request->input('name'),
                'password' => bcrypt($request->input('password')),
                'role' => $request->input('role'),
            ]);
            Log::info('Registration attempt: ' . $user->name);
            $user->save();
            
            // Preusmeri uporabnika
            return redirect()->route('register')->with('success', 'Registration successful!');
        }
    }

    public function yourMethod()
    {
        if (Helpers::checkDatabaseConnection()) {
            return "Povezava do baze podatkov je uspešna";
        } else {
            return "Povezava do baze podatkov ni uspešna";
        }
    }
}