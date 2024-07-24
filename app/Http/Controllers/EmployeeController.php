<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Validation\ValidationException;
use MongoDB\Client as MongoClient;
use App\Models\Employee;
use App\Models\Vacation;
use App\Models\Holidays;
use App\Models\Bills;


class EmployeeController extends Controller{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function importEmployee(){
        $mongoClient = new MongoClient(env('MONGODB_HOST'));
        $database = $mongoClient->selectDatabase('test');
        $collection = $database->selectCollection('employers');
        $data = $collection->find([],[]);

        $employees = iterator_to_array($data);
        #dd($employees);
        try{
            foreach ($employees as $employee){
                Employee::create([
                    'user_name'=> $employee->username."12",
                    'name'=>$employee->username,
                    'last_name'=>$employee->lastname,
                    'email'=> isset($employee->email) ? $employee->email : null,
                    'password'=>$employee->username,
                    'status'=> $employee->status == 'active' ? 1 : 0,
                    'working_status'=> $employee->employmentStatus
                ]);
            }
            return redirect()->route('home')->with('success', "Uspešno uveženi podatki za dopuste.");
        }catch(ValidationException $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function checkEmails(){
        $emails = Employee::pluck('email')->toArray();
        $userName = Employee::pluck('user_name')->toArray();
        return view('users.add', compact('emails','userName'));
    }
    public function addEmployee(Request $request){
        if(Auth::user()->role !== 'visitor'){
            $request->validate([
                'user_name' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|max:255|unique:employee',
                'password' => 'required|string|max:255|',
                'status' => 'required|string|max:255',
                'working_status' => 'required|string',
            ]);
            Employee::create([
                'user_name' => $request->user_name,
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
        if(Auth::user()->role !== 'visitor'){
            $employee = Employee::findOrFail($id);
            if($employee){
                return view('users.editEmployee', ['employee' => $employee]);
            }else{
                return redirect()->route('users.users')->with('error', "Delavec ne obstaja!");
                }
        }else{
            return redirect()->back()->with('error', "Nimate dovoljena za posodobitev podatkov uporabnika!");
        }
    }

    public function updateEmployee(Request $request, $id){
        if(Auth::user()->role !== 'visitor'){
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
                    $status = $request->input('status') === 'active' ? 1 : 0;
                    $employee->update([
                    'user_name' => $request->input('user_name'),
                    'name' => $request->input('name'),
                    'last_name' => $request->input('last_name'),
                    'email' => $request->input('email'),
                    'working_status'=> $request->input('working_status'),
                    'status' => $status,
                    'password' => Hash::make($request->input('password')),
                ]);

                return redirect()->route('users')->with('success', 'Uspešno posodbljeni podatki!');
                } catch (\Illuminate\Validation\ValidationException $e) {
                    $errors = $e->validator->errors()->all();
                    return redirect()->back()->with('error', $errors);
                }

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

    public function employeeData(){
        $employee = Auth::guard('employee')->user();
        $userVacations = Vacation::where('employee_id', $employee->id)->get();
        $userHolidays = Holidays::where('employee_id', $employee->id)->where('status', 'Pending')->get();
        $unpayedBills = Bills::where('payed', 0)->where('buyer', $employee->name." ".$employee->last_name)->get();
    
        return view('employees.home',compact('userVacations', 'userHolidays','unpayedBills'));
    }

    public function vacation(){
        if(Auth::guard('employee')->user()){
            $employee = Auth::guard('employee')->user()->id;
            $vacation = Vacation::where('employee_id', $employee)->first();
            return view('employees.vacation', compact('vacation'));
        }else {
            return redirect()->rout('login')->with('error', "Prijavite se.");
        }
    }

    public function newHoliday(Request $request){
        if(Auth::guard('employee')->user()){
            try{
                $request->validate([
                    'from'=> 'required',
                    'to'=> 'required',
                    'days'=> 'integer|required',
                ]);

                Holidays::create([
                    'from'=>$request->from,
                    'to'=>$request->to,
                    'days'=>$request->days,
                    'employee_id'=>Auth::guard('employee')->user()->id,
                ]);

                return redirect()->route('employeeHome')->with('success', "Vloga za dopust uspešno oddana.");

            }catch(ValidationException $e){
                $errors = $e->validator->errors()->all();
                return redirect()->back()->with('error', $errors);
            }
        }else{
            return redirect()->rout('login')->with('error', "Prijavite se.");
        }
    }
}

?>