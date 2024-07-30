<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use App\Models\Holidays;


class UsersController extends Controller{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function checkUsers(){
        $users = User::pluck('name')->toArray();
        $notifications = Holidays::where('status', 'Pending')->get();
        return view('users.register', compact('users', 'notifications'));
    }

    public function storeUser(Request $request){
        if(Auth::user()->role === 'admin'){
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

            return redirect()->route('users')->with('success', 'Uspječno dodan uporabnik!');
        }else{
            return redirect()->back()->with('error', 'Nimate dovoljena za dodavanje uporabnika!');

        }
    }

    public function findAllUsers(Request $request){
        $users = User::all();
        $employees = Employee::orderBy('status', 'DESC')->get();
        $notifications = Holidays::where('status', 'Pending')->get();
        if($users){
            return view('users.allUsers', ['users' => $users, 'employees' => $employees, 'notifications' => $notifications]);
        }else{
            return view('home')->with('error', "User not found!");
        }
    }

    public function findUser($id){
        if(Auth::user()->role === 'admin'){
            $user = User::findOrFail($id);
            $notifications = Holidays::where('status', 'Pending')->get();
        if($user){
                return view('users.editUser', ['user' => $user, 'notifications' => $notifications]);
            }else{
                return redirect()->route('users.users')->with('error', "Uporabnik ne obstaja!");
                }
        }else{
            return redirect()->back()->with('error', "Nimate dovoljena za posodobitev podatkov uporabnika!");
        }
    }

    public function updateUser(Request $request, $id){
        if(Auth::user()->role === 'admin'){
            $request->validate([
                'name' => 'required|string|max:255',
                'role' => 'required|string|max:255'
            ]);
            $user = User::findOrFail($id);
            if($user->name !== 'Ermin'){
                $user->update([
                    'name' => $request->input('name'),
                    'role' => $request->input('role'),
                ]);
                return redirect()->route('users')->with('success', "Uspešno posodobljeno.");
            }else{
                return redirect()->back()->with('error', "Uporabnika Ermin ni mogoče izbrisati ali spreminjati.");
            }
        }else{
            return redirect()->back()->with('error', "Nimate dovoljenja za posodobitev podaktov uporabnika!");
        }
    }
    public function deleteUser($id){
        if(Auth::user()->role === 'admin'){
            $user = User::findOrFail($id);
            if($user->name !== 'Ermin'){
                $user->delete();
                return redirect()->route('users')->with('success', 'Uspešno izbrisan uporabnik.');
            }else{
                return redirect()->back()->with('error', "Uporabnika Ermin ni mogoče izbrisati ali spreminjati.");
                }
        }else{
            return redirect()->back()->with('error', "Nimate dovoljenja za brisanje uporabnikov!");

        }
    }



}