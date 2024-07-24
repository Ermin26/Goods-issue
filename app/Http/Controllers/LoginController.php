<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Vacation;
use App\Models\Holidays;

class LoginController extends Controller{

    public function loginUser(Request $request){
        $request->validate([
            'name' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $user = User::where('name', $request->name)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::guard('web')->login($user);
            $request->session()->regenerate();

            return redirect()->route('home')->with('success', "Dobro došli ".auth()->user()->name);
        }else{
            $employee = Employee::where('user_name', $request->name)->first();
            if($employee && Hash::check($request->password, $employee->password) && $employee->status == 1){
                Auth::guard('employee')->login($employee);
                #dd(Auth::guard('employee'));
                $request->session()->regenerate();
                return redirect()->route('employeeHome')->with('success', "Welcome");
            }
        }

        return back()->with('error', "Napačni podatki za prijavo");
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', "Nasvidanje.");
    }
}