<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Helpers\Helpers;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function storeUser(Request $request){
        $request->validate([
            'name' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:4',
            'role' => 'required|string|max:255',
        ]);

        User::create([
            'name' => $request->name,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.register')->with('success', 'User added successfully');
    }



    public function destroy($id){
        if(Auth::check()){

            $user = User::findOrFail($id);
            $user->delete();
            return redirect()->route('users')->with('success', 'User deleted successfully');
        }else{
            return redirect()->route('login')->with('error', 'Prijavite se.');
        }
    }
}