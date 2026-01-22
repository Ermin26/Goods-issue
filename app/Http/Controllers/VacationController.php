<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use MongoDB\Client as MongoClient;
use App\Models\Vacation;
use App\Models\Employee;
use App\Models\Holidays;
use Psy\CodeCleaner\FunctionContextPass;

class VacationController extends Controller{
    public function importVacations(){
        if(Auth::user()->role == 'admin'){
        $mongoClient = new MongoClient(env('MONGODB_HOST'));
        $database = $mongoClient->selectDatabase('test');
        $collection = $database->selectCollection('vacations');
        $data = $collection->find([],[]);

        $vacations = iterator_to_array($data);
        #dd($vacations);

        $employees = Employee::where('working_status', 'zaposlen/a')->get();
        #dd($vacations);
        
        try{
            foreach ($vacations as $vacation){
                foreach($employees as $employee){
                    $employe = $employee->name." ". $employee->last_name;
                    if($vacation->user == $employe){
                    #dd($employee->name);
                        Vacation::create([
                            'user'=>$vacation->user,
                            'last_year'=>$vacation->lastYearHolidays,
                            'holidays'=>$vacation->holidays,
                            'used_holidays'=>$vacation->usedHolidays,
                            'overtime'=>$vacation->overtime,
                            'employee_id' =>$employee->id
                        ]);
                    }
                }
            }
            $this->importHolidays($vacations);
            return redirect()->route('home')->with('success', "Uspešno uveženi podatki za dopuste.");
        }catch(ValidationException $e){
            $errors = $e->validator->errors()->all();
            return redirect()->back()->with('error', "Napaka pri posodabljanju podatkov. ".$errors);
        }
        }
    }

    private function importHolidays(){
        $mongoClient = new MongoClient(env('MONGODB_HOST'));
        $database = $mongoClient->selectDatabase('test');
        $collection = $database->selectCollection('vacations');
        $data = $collection->find([],[]);
        $vacations = Vacation::all();
        $holidays = iterator_to_array($data);
        foreach($holidays as $holiday){
            foreach($holiday['pendingHolidays'] as $data){
                foreach($vacations as $vacation){
                    if($vacation->user == $holiday->user){
                        $start = strtotime($data['startDate'][0]);
                        $end = strtotime($data['endDate'][0]);
                        $applyDate = preg_replace('/\s+/', '',$data['applyDate'][0]);
                        #dd($applyDate);
                        $apply = strtotime($applyDate);
                        $changeDate = date('Y-m-d', $start);
                        $changeDate2 = date('Y-m-d', $end);
                        $applyed = date('Y-m-d', $apply);
                        //dd("vacations",$vacation);
                        Holidays::create([
                            'from_date'=>$changeDate,
                            'to'=> $changeDate2,
                            'status'=>$data['status'][0],
                            'days'=>$data['days'][0],
                            'apply_date'=>$applyed,
                            'employee_id'=>$vacation->employee_id,
                            'user_name' => Auth::user()->name,
                        ]);
                    }
                }
            }

        }
    }

    public function vacationData(){
        $vacations = Vacation::all();
        $holidays = Holidays::all();
        $employees = Employee::where('status', 1)->where('working_status', 'zaposlen/a')->get();
        $pending_holidays = Holidays::where('status', 'pending')->get();
        $notifications = Holidays::where('status', 'Pending')->get();
        $years = Holidays::selectRaw('YEAR(holidays.from) as year')
        ->distinct()
        ->orderBy('year', 'ASC')
        ->pluck('year');

        return view('holidays.holidays', compact('vacations','holidays', 'employees', 'pending_holidays','notifications', 'years'));
    }

    public function updateVacations(Request $request){
        if(Auth::user()->role !== 'visitor'){
            try{
                $request->validate([
                    'user' => 'required',
                    'last_year' => 'integer|required',
                    'holidays' => 'integer|required',
                    'used_holidays' => 'integer|required',
                    'overtime' => 'integer|required',
                ]);
                $userId = $request->input('user');
                
                $user = Vacation::where('employee_id', $userId)->first();
                $employee = Employee::where('id', $userId)->first();
                if($user){
                    $user->last_year = $request->input('last_year');
                    $user->holidays = $request->input('holidays');
                    $user->used_holidays = $request->input('used_holidays');
                    $user->overtime = $request->input('overtime');
                    $user->save();
                }
                else{
                    Vacation::create([
                        'user'=>$employee->name ." ".$employee->last_name,
                        'last_year'=>$request->input('last_year'),
                        'holidays'=>$request->input('holidays'),
                        'used_holidays'=>$request->input('used_holidays'),
                        'overtime'=>$request->input('overtime'),
                        'employee_id' =>$userId
                    ]);
                }
                return redirect()->back()->with('success', "Uspešno posodobljeni podatki.");
            }catch(ValidationException $e){
                $errors = $e->validator->errors()->all();
                return redirect()->back()->with('error', "Napaka pri posodabljanju podatkov. ".$errors);
            }
        }else{
            return redirect()->back()->with('error', "Nimate dovoljena za spreminjanje podatkov.");
        }
    }

    public function approveHoliday($id){
        if(Auth::user()->role == 'admin'){
            $holiday = Holidays::where('id',$id)->where('status', "Pending")->first();
            if($holiday){
                $employee = Employee::where('id', $holiday->employee_id)->first();

                $holiday->update([
                    'status' => 'Approved',
                    'user_name'=> Auth::user()->name,
                ]);

                $holiday->save();

                $vacation = Vacation::where('employee_id', $employee->id)->first();

                $vacation->update([
                    'used_holidays'=> $vacation->used_holidays + $holiday->days,
                ]);
                $vacation->save();
                try{
                    $changeDate = strtotime($holiday->from);
                    $changeDate2 = strtotime($holiday->to);
                    $from = date('d.m.Y',$changeDate);
                    $to = date('d.m.Y',$changeDate2);
                    Mail::raw("Pozdravljeni ".$employee->name.", Vaš dopust ".$from." - ".$to." je odobren. Lep pozdrav, ".Auth::user()->name.".",function($message) use ($employee){
                        $message->to($employee->email)
                                ->subject("Dopust");
                    });
                    Mail::raw("Obvestilo: Dopust za zaposlenega ".$employee->name." je bil odobren. Od: ".$from." Do: ".$to.". Lep pozdrav, ".Auth::user()->name.".", function ($message) {
                        $message->to("mb2.providio@gmail.com")
                                ->subject("Obvestilo o dopustu")
                                ->cc("rataj.tvprodaja@gmail.com");
                    });
                }catch(ValidationException $e){
                    $errors = $e->validator->errors()->all();
                    return redirect()->route('vacation')->with('error ', $errors);
                };
                return redirect()->back()->with('success','Dopust uspešno odobren.');
            }else{
                return redirect()->back()->with('error', "Dopust je že odobren,");
            }
        }else{
            return redirect()->back()->with('error', "Dostop zavrnjen!");
        }
    }
    public function rejectHoliday($id){
        if(Auth::user()->role == 'admin'){
            $holiday = Holidays::where('id',$id)->where('status', "Pending")->first();
            if($holiday){
                $employee = Employee::where('id', $holiday->employee_id)->first();

                $holiday->update([
                    'status' => 'Rejected',
                    'user_name'=> Auth::user()->name,
                ]);

                $holiday->save();

                try{
                    $changeDate = strtotime($holiday->from);
                    $changeDate2 = strtotime($holiday->to);
                    $from = date('d.m.Y',$changeDate);
                    $to = date('d.m.Y',$changeDate2);
                    Mail::raw("Pozdravljeni ".$employee->name.", Vaš dopust ".$from." - ".$to." žal ni odobren. Lep pozdrav, ".Auth::user()->name.".",function($message) use ($employee){
                        $message->to($employee->email)
                                ->subject("Dopust");
                    });
                    Mail::raw("Obvestilo: Dopust za zaposlenega ".$employee->name." Od: ".$from." Do: ".$to." ni odobren. Lep pozdrav, ".Auth::user()->name.".", function ($message) {
                        $message->to("mb2.providio@gmail.com")
                                ->subject("Obvestilo o dopustu")
                                ->cc("rataj.tvprodaja@gmail.com");
                    });
                }catch(ValidationException $e){
                    $errors = $e->validator->errors()->all();
                    return redirect()->route('vacation')->with('error ', $errors);
                };
                return redirect()->back()->with('success','Dopust zavrnjen.');
            }else{
                return redirect()->back()->with('error', "Dopust je že zavrnjen,");
            }
        }else{
            return redirect()->back()->with('error', "Dostop zavrnjen!");
        }
    }

    public function userUsedHolidays(Request $request){
        try{
            $request->validate([
                'selectedYear' => 'nullable|integer',
                'selectedUser' => 'nullable|String',
            ]);

            $year = $request->input('selectedYear');
            $user = $request->input('selectedUser');
            $holidays = null;
            if($user && $year){
                $userId = Vacation::where('user', 'LIKE', "%{$user}%")->pluck('employee_id')->first();
                $holidays = Holidays::whereYear('holidays.from', $year)->where('holidays.employee_id','=', $userId)
                            ->join('vacation','holidays.employee_id', '=','vacation.employee_id')
                            ->select('holidays.*', 'vacation.user')
                            ->orderBy('holidays.to', 'DESC')
                            ->get()
                            ->groupBy('user')
                            ->map(function($group){
                                return [
                                    'user' => $group->first()->user,
                                    'holidays' => $group->map(function($holiday){
                                        return [
                                            'id' => $holiday->id,
                                            'from' => $holiday->from_date,
                                            'to' => $holiday->to,
                                            'days' => $holiday->days,
                                            'status' => $holiday->status,
                                        ];
                                    })->toArray(),
                                ];
                            })
                            ->values()
                            ->toArray();

            }else if($user && !$year){
                $userId = Vacation::where('user', 'LIKE', "%{$user}%")->pluck('employee_id')->first();
                $holidays = Holidays::where('holidays.employee_id','=', $userId)
                ->join('vacation','holidays.employee_id', '=','vacation.employee_id')
                ->select('holidays.*', 'vacation.user')
                ->orderBy('holidays.to', 'DESC')
                ->get()
                ->groupBy('user')
                ->map(function($group){
                    return [
                        'user' => $group->first()->user,
                        'holidays' => $group->map(function($holiday){
                            return [
                                'id' => $holiday->id,
                                'from' => $holiday->from_date,
                                'to' => $holiday->to,
                                'days' => $holiday->days,
                                'status' => $holiday->status,
                            ];
                        })->toArray(),
                    ];
                })
                ->values()
                ->toArray();
            }else if(!$user && $year){
                $holidays = Vacation::join('holidays','vacation.employee_id','=' ,'holidays.employee_id')
                            ->whereYear('holidays.from', $year)
                            ->select('vacation.user', 'holidays.*')
                            ->orderBy('holidays.to', 'DESC')
                            ->get()
                            ->groupBy('user')
                            ->map(Function($group){
                                return [
                                    'user' => $group->first()->user,
                                    'holidays' => $group->map(function($holiday){
                                        return [
                                            'id' => $holiday->id,
                                            'from' => $holiday->from_date,
                                            'to' => $holiday->to,
                                            'days' => $holiday->days,
                                            'status' => $holiday->status,
                                        ];
                                    })->toArray(),
                                ];
                            })
                            ->values()
                            ->toArray();
                
            }else{
                $holidays = Vacation::join('holidays','vacation.employee_id','=' ,'holidays.employee_id')
                            ->select('vacation.user', 'holidays.*')
                            ->orderBy('holidays.from', 'DESC')
                            ->get()
                            ->groupBy('user')
                            ->map(Function($group){
                                return [
                                    'user' => $group->first()->user,
                                    'holidays' => $group->map(function($holiday){
                                        return [
                                            'id' => $holiday->id,
                                            'from' => $holiday->from_date,
                                            'to' => $holiday->to,
                                            'days' => $holiday->days,
                                            'status' => $holiday->status,
                                        ];
                                    })->toArray(),
                                ];
                            })
                            ->values()
                            ->toArray();
            }

            return response()->json([
                'holidays' => $holidays
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function usedHolidayEdit(Request $request, $id){
        $holiday = Holidays::join('employee','holidays.employee_id','=','employee.id')
                            ->join('vacation', 'vacation.employee_id', '=', 'holidays.employee_id')
                            ->where('holidays.id', $id)
                            ->select('vacation.*', 'employee.*','holidays.*')
                            ->first();
                            $notifications = Holidays::where('status', 'Pending')->get();
        if($holiday){
            return view('holidays.editUsedHoliday', compact('holiday','notifications'));
        }else{
            return redirect()->back();
        }
    }

    public function updateUserHoliday(Request $request, $id){
        $holiday = Holidays::find($id);
        $vacation = Vacation::where('employee_id', '=', $holiday->employee_id)->first();
        if($this->checkRole()){
            try{
                $request->validate([
                    'from' =>'required|date',
                    'to' =>'required|date',
                    'days' =>'required|numeric',
                ]);
                if($holiday->status == 'Approved'){
                    $days = $request->input('days');
                    if($holiday->days > $days){
                        $updateUsedHolidays = $holiday->days - $days;
                        $newUsedHolidays = $vacation->used_holidays - $updateUsedHolidays;
                        $vacation->update([
                            'used_holidays' => $newUsedHolidays
                        ]);
                        $vacation->save();
                    }else{
                        $updateUsedHolidays = $days - $holiday->days;
                        $newUsedHolidays = $vacation->used_holidays + $updateUsedHolidays;
                        $vacation->update([
                            'used_holidays' => $newUsedHolidays
                        ]);
                        $vacation->save();
                    }
                    $holiday->update([
                        'from_date' => $request->input('from'),
                        'to' => $request->input('to'),
                        'days' => $request->input('days'),
                        'user_name'=> Auth::user()->name
                    ]);
                    $holiday->save();
                    return redirect()->route('vacation')->with('success', 'Uspešno posodobljeni podatki.');
                }else{
                    return redirect()->back()->with('error', 'Dopust ki ima status "Zavrnjeno" se ne more spreminjat!');
                }
            }catch(ValidationException $e){
                $errors = $e->validator->errors()->all();
                return redirect()->back()->with('error', implode(', ', $errors));
            }
        }else{
            return redirect()->back()->with('error', "Nimate dovoljena za posodobitev podatkov.");
        }
    }

    public function deleteUserHoliday(Request $request,$id){
        if($this->checkRole()){
            try{
                $holiday = Holidays::find($id);
                $vacation = Vacation::where('employee_id', '=', $holiday->employee_id)->first();
                if($holiday->status == "Approved"){
                    $vacation->update([
                        'used_holidays' => $vacation->used_holidays - $holiday->days,
                    ]);
                    $vacation->save();

                    $employee = Employee::where('id', '=', $holiday->employee_id)->first();
                    $changeDate = strtotime($holiday->from_date);
                    $changeDate2 = strtotime($holiday->to);
                    $from = date('d.m.Y',$changeDate);
                    $to = date('d.m.Y',$changeDate2);
                    Mail::raw("Obvestilo: Dopust za zaposlenega ".$employee->name." Od: ".$from." Do: ".$to." je bil PREKLICAN. Lep pozdrav, ".Auth::user()->name.".", function ($message) {
                        $message->to("mb2.providio@gmail.com")
                                ->subject("Obvestilo o dopustu")
                                ->cc("rataj.tvprodaja@gmail.com");
                    });
                }
                $holiday->delete();
                return redirect()->route('vacation')->with('success', 'Uspešno izbrisan dopust.');
            }catch(ValidationException $e){
                $errors = $e->validator->errors()->all();
                return redirect()->route('vacation')->with('error', implode(',', $errors));
            }
        }else{
            return redirect()->route('vacation')->with('error', 'Nimate dovoljenja za brisanje.');
        }
    }

    public function sendMsg(Request $request){
        try{
            $msgInfo = $request->input('msgInfo');
            $message = $request->input('msg');
            $msg = nl2br($message);
            $sendTo = $request->input('sendTo');
            if(in_array('all', $sendTo)){
                $employees = Employee::where('status', '=', '1')
                        ->whereNotNull('email')
                        ->where('email', '!=', '')
                        ->pluck('email')
                        ->toArray();

                foreach($employees as $email){
                    Mail::send([],[],function($message) use($msgInfo, $email,$msg){
                        $message->to($email)
                                ->subject($msgInfo)
                                ->setBody(
                                    $msg."<br><br> " . Auth::user()->name.".",
                                    'text/html'
                                );
                    });
                };
                return response()->json([
                    "msg" => "Uspešno poslan email vsem."
                ]);
            }
            else if(in_array('students', $sendTo)){
                $employees = Employee::where('status', '=', '1')
                    ->where('working_status', '=', 'študent')
                        ->whereNotNull('email')
                        ->where('email', '!=', '')
                        ->pluck('email')
                        ->toArray();

                foreach($employees as $email){
                    Mail::send([],[],function($message) use($msgInfo, $email,$msg){
                        $message->to($email)
                                ->subject($msgInfo)
                                ->setBody(
                                    $msg."<br><br> " . Auth::user()->name.".",
                                    'text/html'
                                );
                    });
                };
                return response()->json([
                    'msg'=> "Uspešno poslan email študentom."
                ]);
            }
            else if(in_array('employees', $sendTo)){
                $employees = Employee::where('status', '=', '1')
                        ->where('working_status', '=', 'zaposlen/a')
                        ->whereNotNull('email')
                        ->where('email', '!=', '')
                        ->pluck('email')
                        ->toArray();

                foreach($employees as $email){
                    Mail::send([],[],function($message) use($msgInfo, $email,$msg){
                        $message->to($email)
                                ->subject($msgInfo)
                                ->setBody(
                                    $msg."<br><br> " . Auth::user()->name.".",
                                    'text/html'
                                );
                    });
                };
                return response()->json([
                    "msg"=> "Uspešno poslan mail zaposlenim."
                ]);
            }
            else{
                $emails = Employee::whereIn('email', $sendTo)->pluck('email');
                foreach($emails as $email){
                    Mail::send([],[],function($message) use($msgInfo, $email,$msg){
                        $message->to($email)
                                ->subject($msgInfo)
                                ->setBody(
                                    $msg."<br><br> " . Auth::user()->name.".",
                                    'text/html'
                                );
                    });
                }
                return response()->json([
                    "msg"=> "Uspešno poslan email."
                ]);
            };

        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function allDbs(Request $request){
        $mongoClient = new MongoClient(env('MONGODB_HOST'));
        $database = $mongoClient->selectDatabase('test');
        var_dump($database);
    }

    private function checkRole(){
        return Auth::user()->role == 'admin' || Auth::user()->name == 'Alma';
    }

}