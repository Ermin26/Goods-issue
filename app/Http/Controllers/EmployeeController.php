<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
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
        $emails = Employee::whereNotNull('email')->where('email', '!=', '')->pluck('email')->toArray();
        $userName = Employee::pluck('user_name')->toArray();
        $notifications = Holidays::where('status', 'Pending')->get();
        
        return view('users.add', compact('emails','userName','notifications'));
    }
    public function addEmployee(Request $request){
        if(Auth::user()->role !== 'visitor'){
            try{
                $request->validate([
                    'user_name' => 'required|string|max:255',
                    'name' => 'required|string|max:255',
                    'last_name' => 'required|string|max:255',
                    'email' => 'required|string|max:255|unique:employee',
                    'password' => 'required|string|max:255|confirmed',
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
                return redirect()->route('users')->with('success', 'Uspešno dodan delavec!');
            }catch(ValidationException $e){
                $error = $e->validator->errors()->all();
                $errors = implode('<br>', $error);
                return redirect()->back()->with('error', $errors);
            }
            
        }else{
            return redirect()->back()->with('error', 'Nimate dovoljena za dodavanje uporabnika!');
        }
    }

    public function findEmployee($id){
        if(Auth::user()->role !== 'visitor'){
            $employee = Employee::findOrFail($id);
            $notifications = Holidays::where('status', 'Pending')->get();
            if($employee){
                return view('users.editEmployee', compact('employee', 'notifications'));
            }else{
                return redirect()->route('users.users')->with('error', "Delavec ne obstaja!");
                }
        }else{
            return redirect()->back()->with('error', "Nimate dovoljena za posodobitev podatkov uporabnika!");
        }
    }

    public function updateEmployee(Request $request, $id){
        if(Auth::user()->role !== 'visitor'){
            try {
                $employee = Employee::findOrFail($id);
                $request->validate([
                    'name' => 'required|string|max:255',
                    'last_name' => 'required|string|max:255',
                    'email' => 'nullable|string|max:255',
                    'user_name' => 'required|string|max:255',
                    'password' => 'nullable|string|confirmed',
                    'status' => 'required|string',
                    'working_status' => 'required|string',
                ]);
                $status = $request->input('status') === 'active' ? 1 : 0;
                $newPassword = $employee->password;
                if(!empty($request->input('password'))){
                    $newPassword = Hash::make($request->input('password'));
                }
                $employee->update([
                    'user_name' => $request->input('user_name'),
                    'name' => $request->input('name'),
                    'last_name' => $request->input('last_name'),
                    'email' => $request->input('email'),
                    'working_status'=> $request->input('working_status'),
                    'status' => $status,
                    'password' => $newPassword,
                ]);

                return redirect()->route('users')->with('success', 'Uspešno posodbljeni podatki!');
            } catch (ValidationException $e) {
                $error = $e->validator->errors()->all();
                $errors = implode('<br>', $error);
                return redirect()->back()->with('error', $errors);
            }

        }else{
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
        try{
            if(Auth::guard('employee')->user()){
                $employee = Auth::guard('employee')->user();
                #dd($employee);
                $userVacations = Vacation::where('employee_id', $employee->id)->get();
                $userHolidays = Holidays::where('employee_id', $employee->id)->where('status', 'Pending')->get();
                $unpayedBills = Bills::where('payed', 0)->where('buyer', $employee->name." ".$employee->last_name)->get();
            
                return view('employees.home',compact('userVacations', 'userHolidays','unpayedBills', 'employee'));
            }else{
                return redirect()->route('login')->with('error', "Prijavite se za nadaljevanje.");
            }
        }catch(ValidationException $e){
            $errors = $e->validator->errors()->all();
            return redirect()->route('login')->with('error', "Prijavite se za nadaljevanje.");
        }
    }

    public function vacation(){
        if(Auth::guard('employee')->user()){
            $employee = Auth::guard('employee')->user();
            if($employee->working_status != "študent"){
                $vacation = Vacation::where('employee_id', $employee->id)->first();
                $years = Holidays::selectRaw('YEAR(holidays.from) as year')
                        ->distinct()
                        ->orderBy('year', 'ASC')
                        ->pluck('year');
                return view('employees.vacation', compact('vacation', 'employee', 'years'));
            }else{
                return redirect()->back();
            }
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

                $from = $request->input('from');
                $to= $request->input('to');

                Holidays::create([
                    'from'=>$from,
                    'to'=>$to,
                    'days'=>$request->input('days'),
                    'employee_id'=>Auth::guard('employee')->user()->id,
                ]);

                Mail::raw("Delavec ".Auth::guard('employee')->user()->name. " " . Auth::guard('employee')->user()->last_name. " je oddal/a vlogo za dopust od ".$from ." - ".$to. ". https://providiomb.42web.io/vacation" ,function($message){
                    $message->to("mb.providio@gmail.com")
                            ->subject("Dopust")
                            ->cc("rataj.tvprodaja@gmail.com");
                });

                return redirect()->route('employeeHome')->with('success', "Vloga za dopust uspešno oddana.");

            }catch(ValidationException $e){
                $errors = $e->validator->errors()->all();
                return redirect()->back()->with('error', $errors);
            }
        }else{
            return redirect()->rout('login')->with('error', "Prijavite se.");
        }
    }

    public function getProfile(){
        if(Auth::guard('employee')->user()){
            $employee = Auth::guard('employee')->user();
            $myProfile = Employee::where('id', $employee->id)->first();
            $myVacations = Vacation::where('employee_id', $employee->id)->first();
            $myHolidays = Holidays::where('employee_id', $employee->id)->first();
            return view('employees.profile', compact('myVacations', 'myHolidays', 'myProfile'));
        }else{
            return redirect()->route('login')->with('error', "Prijavite se.");
        }
    }

    public function updateProfile(Request $request, $id){
        if(Auth::guard('employee')->user()){
            try{
                $request->validate([
                    'username'=> 'string|required',
                    'email'=> 'required',
                    'password'=> 'nullable|required|string|confirmed'
                ]);
                $password = $request->input('password');
                $employeeData = Employee::findOrFail($id);
                $employeeData->update([
                    'user_name'=> $request->input('username'),
                    'email'=> $request->input('email'),
                    'password'=> Hash::make($password),
                ]);

                $employeeData->save();
                return redirect()->route('profile')->with('success',"Podatki uspešno posodobljeni");
            }catch(ValidationException $e){
                $errors = $e->validator->errors()->all();
                $error = implode("<br/>",$errors);
                return redirect()->back()->with('error', $error);
            }
        }else{
            return redirect()->route('login')->with('error', "Prijavite se.");
        }
    }

    public function editHoliday($id){
        if(Auth::guard('employee')->user()){
            $holiday = Holidays::findOrFail($id);
            return view('employees.vacationUpdate', compact('holiday'));
        }else{
            return redirect()->route('login')->with('error', "Prijavite se.");
        }
    }

    public function updateHoliday(Request $request,$id){
        if(Auth::guard('employee')->user()){
            try{
                $request->validate([
                    'from'=> 'required',
                    'to'=> 'required',
                    'days'=> 'required',
                ]);

                $holiday = Holidays::findOrFail($id);

                $holiday->update([
                    'from'=> $request->input('from'),
                    'to'=> $request->input('to'),
                    'days'=> $request->input('days'),
                ]);

                $holiday->save();

                Mail::raw("Delavec ".Auth::guard('employee')->user()->name. " " . Auth::guard('employee')->user()->last_name. " je posodobil/a vlogo za dopust." ,function($message){
                    $message->to("mb.providio@gmail.com")
                            ->subject("Posodobitev dopusta")
                            ->cc("rataj.tvprodaja@gmail.com");
                });
                return redirect()->route('employeeHome')->with('success', "Uspešno posodobljena vloga za dopust.");
            }catch(ValidationException $e){
                $error = $e->validator->errors()->all();
                return redirect()->back()->with('error', "Error: ", $error);
            }
        }else{
            return redirect()->route('login')->with('error', "Prijavite se.");
        }
    }

    public function deleteHoliday($id){
        if(Auth::guard('employee')->user()){
            try{
                $holiday = Holidays::findOrFail($id);
                $holiday->delete();
                Mail::raw("Delavec ".Auth::guard('employee')->user()->name. " " . Auth::guard('employee')->user()->last_name. " je preklical/a vlogo za dopust." ,function($message){
                    $message->to("mb.providio@gmail.com")
                            ->subject("Preklic dopusta")
                            ->cc("rataj.tvprodaja@gmail.com");
                });
                return redirect()->route('employeeHome')->with('success', 'Uspešno izbrisana vloga za dopust.');
            }catch(ValidationException $e){
                $error = $e->validator->errors()->all();
                return redirect()->back()->with('error', "Error: ", $error);
            }
        }else{
            return redirect()->route('login')->with('error', "Prijavite se.");
        }
    }

    public function myHolidays(Request $request, $id){
        if(Auth::guard(('employee'))->user()){
            try{

                $month = $request->input('month');
                $year = $request->input('year');
                $status = $request->input('status');
                
                switch(true){
                    case !is_null($month) && is_null($year) && is_null($status):
                        $data = Holidays::whereMonth('from', $month)->where('employee_id', $id)->get();
                        
                        return response()->json([
                            'holidays' => $data,
                        ]);
                        break;
                    case !is_null($year) && is_null($status) && is_null($month):
                        if(strlen($year) == 4){
                            $data = Holidays::whereYear('from', $year)->where('employee_id', $id)->get();
                            return response()->json([
                                'holidays' => $data,
                            ]);
                        }else{
                            return response()->json([
                                'holidays' => "",
                            ]);
                        }
                        break;
                    case !is_null($status) && is_null($month) && is_null($year):
                        $data = Holidays::where('status', $status)->where('employee_id', $id)->get();
                        return response()->json([
                            'holidays' => $data,
                        ]);
                        break;
                    case !is_null($month) && !is_null($year) && is_null($status);
                        if(strlen($year) == 4){
                            $data = Holidays::whereMonth('from', $month)->whereYear('from', $year)->where('employee_id', $id)->get();
                            return response()->json([
                                'holidays' => $data,
                            ]);
                        }else{
                            $data = Holidays::whereMonth('from', $month)->where('employee_id', $id)->get();
                            return response()->json([
                                'holidays' => $data,
                        ]);
                        }
                        break;

                    case !is_null($month) && is_null($year) && !is_null($status);
                        $data = Holidays::whereMonth('from', $month)->where('status', $status)->where('employee_id', $id)->get();
                        return response()->json([
                            'holidays' => $data,
                        ]);
                        break;
                    case is_null($month) && !is_null($year) && !is_null($status);
                        $data = Holidays::whereYear('from', $year)->where('status', $status)->where('employee_id', $id)->get();
                        return response()->json([
                            'holidays' => $data,
                        ]);
                        break;
                    case !is_null($month) && !is_null($year) && !is_null($status);
                        $data = Holidays::whereMonth('from', $month)->whereYear('from', $year)->where('status', $status)->where('employee_id', $id)->get();
                        return response()->json([
                            'holidays' => $data,
                        ]);
                        break;

                    default:
                        $data = Holidays::where('employee_id', $id)->get();
                        return response()->json([
                            'holidays' => $data,
                        ]);
                        break;
                }

            }catch(ValidationException $e){
                $error = $e->validator->errors()->all();
                return response()->json(['error', $error]);
            }
        }else{
            return redirect()->back()->with('error', "Prijavite se.");
        }
    }

    public function studentSendEmail(Request $request, $id){
        if(Auth::guard('employee')->user()){
            try{
                $request->validate([
                    'msgInfo' => 'required',
                    'msg' => 'required'
                ]);
                $employee = Employee::where('id', $id)->first();
                $msgInfo = $request->input('msgInfo');
                $message = $request->input('msg');
                $msg = nl2br($message);


                Mail::send([],[],function($message) use($msgInfo,$msg,$employee){
                    $message->to('mb.providio@gmail.com')
                            ->subject($msgInfo)
                            ->setBody(
                                $msg."<br><br> " .$employee->name.".",
                                'text/html'
                            );
                });

                return redirect()->route('employeeHome')->with('success',"Poslano");
            }catch(ValidationException $e){
                $errors = $e->validator->errors()->all();
                $error = implode("<br>", $errors);
                return redirect()->back()->with('error',"Error: ". $error);
            }
        }else{
            return redirect()->route('login')->with('error', "Prijavite se.");
        }
    }
}

?>
