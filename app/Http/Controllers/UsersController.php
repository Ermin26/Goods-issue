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
use App\Middleware\CheckRole;


class UsersController extends Controller{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function checkEmails(){
        $emails = Employee::pluck('email')->toArray();
        return view('users.add', compact('emails'));
    }
    public function checkUsers(){
        $users = User::pluck('name')->toArray();
        return view('users.register', compact('users'));
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

            return redirect()->route('users.register')->with('success', 'Uspječno dodan uporabnik!');
        }else{
            return redirect()->back()->with('error', 'Nimate dovoljena za dodavanje uporabnika!');

        }
    }

    public function findAllUsers(Request $request){
        $users = User::all();
        $employees = Employee::all();
        if($users){
            return view('users.allUsers', ['users' => $users, 'employees' => $employees]);
        }else{
            return view('home')->with('error', "User not found!");
        }
    }

    public function findUser($id){
        if(Auth::user()->role === 'admin'){
            $user = User::findOrFail($id);
            if($user){
                return view('users.editUser', ['user' => $user]);
            }else{
                return redirect()->route('users.users')->width('error', "Uporabnik ne obstaja!");
                }
        }else{
            return redirect()->back()->width('error', "Nimate dovoljena za posodobitev podatkov uporabnika!");
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

    public function addEmployee(Request $request){
        if(Auth::user()->role === 'admin' || Auth::user()->role === 'moderator'){
            $request->validate([
                'name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|max:255|unique:employee',
                'password' => 'required|string|max:255|',
                'status' => 'required|string|max:255',
                'working_status' => 'required|string',
            ]);
            Employee::create([
                'name' => $request->name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'working_status'=> $request->working_status,
                'password' => Hash::make($request->password),
            ]);
            return redirect()->route('users.add')->with('success', 'Uspječno dodan delavec!');
        }else{
            return redirect()->back()->with('error', 'Nimate dovoljena za dodavanje uporabnika!');
        }
    }

    public function findEmployee($id){
        if(Auth::user()->role === 'admin' || Auth::user()->role === 'moderator'){
            $employee = Employee::findOrFail($id);
            if($employee){
                return view('users.editEmployee', ['employee' => $employee]);
            }else{
                return redirect()->route('users.users')->width('error', "Delavec ne obstaja!");
                }
        }else{
            return redirect()->back()->width('error', "Nimate dovoljena za posodobitev podatkov uporabnika!");
        }
    }

    public function updateEmployee(Request $request, $id){
        if(Auth::user()->role === 'admin' || Auth::user()->role === 'moderator'){
            $employee = Employee::findOrFail($id);
            ##dd("This is id ". $employee);
                #dd($request->all());
                try {
                    $request->validate([
                        'name' => 'required|string|max:255',
                        'last_name' => 'required|string|max:255',
                        'email' => 'required|string|max:255',
                        'password' => 'required|string|max:255',
                        'status' => 'required|string',
                        'working_status' => 'required|string',
                    ]);
                } catch (\Illuminate\Validation\ValidationException $e) {
                    dd('Validation errors:', $e->errors());
                }
                $status = $request->input('status') === 'active' ? 1 : 0;
                $employee->update([
                    'name' => $request->input('name'),
                    'last_name' => $request->input('last_name'),
                    'email' => $request->input('email'),
                    'working_status'=> $request->input('working_status'),
                    'status' => $status,
                    'password' => Hash::make($request->input('password')),
                ]);
                

                return redirect()->route('users')->with('success', 'Uspešno posodbljeni podatki!');
        }else{
            #return redirect()->back()->with('error', 'Nimate dovoljena za spreminjanje podatkov!');
            return redirect()->route('home')->with('error', 'Nimate dovoljena za spreminjanje podatkov!');
        }
    }

    public function deleteEmployee($id){
        if(Auth::user()->role === 'admin'){
            $employee = Employee::findOrFail($id);
            if($employee){
                $employee->delete();
                return redirect()->route('users')->with('success', 'Uspešno izbrisan delavec.');
            }else{
                return redirect()->route('users')->with('error', 'Delavec ne obstaja!');
            }
        }

    }

}