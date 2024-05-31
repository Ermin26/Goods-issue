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

        return redirect()->route('register')->with('success', 'User added successfully');
    }

    public function loginUser(Request $request){
        $request->validate([
            'name' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $user = User::where('name', $request->name)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('home')->with('success', "Dobro došli ".auth()->user()->name);
        }

        return back()->with('error', "Napačni podatki za prijavo");
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', "Nasvidanje.");
    }

    public function ifLoged(Request $request){
        if (Auth::check()) {
            switch (Route::currentRouteName()) {
                case 'home':
                    return view('index');
                    break;
                case 'all':
                    return view('bills.selled');
                    break;
                case 'costs':
                    return view('bills.costs');
                    break;
                case 'search':
                    return view('users.search');
                    break;
                case 'vacation':
                    return view('holidays.holidays');
                    break;
                case 'users':
                    $users = User::all();
                    return view('users.Allusers', ['users' => $users]);
                    break;
                case 'users/add':
                    return view('users.add');
                    break;
                case 'users/register':
                    return view('users.register');
                    break;
                case 'login':
                    return view('index');
                    break;
                // Dodajte druge primere glede na potrebno
                default:
                    return view('login.login');
            }
        }else{
            return redirect()->route('login')->with('error', 'Prijavite se.');
        }
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