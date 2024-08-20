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
                            'from'=>$changeDate,
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
        #dd($pending_holidays);

        return view('holidays.holidays', compact('vacations','holidays', 'employees', 'pending_holidays','notifications'));
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
                $user = Vacation::where('user', $request->input('user'))->first();

                $user->last_year = $request->input('last_year');
                $user->holidays = $request->input('holidays');
                $user->used_holidays = $request->input('used_holidays');
                $user->overtime = $request->input('overtime');
                $user->save();

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
                                ->subject("Obvestilo o dopustu");
                                #->cc("rataj.tvprodaja@gmail.com");
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
                    'status' => 'Approved',
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
                                ->subject("Obvestilo o dopustu");
                                #->cc("rataj.tvprodaja@gmail.com");
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
                $holidays = Holidays::whereYear('from', $year)->where('employee_id', $userId)->get();

            }else if($user && !$year){
                $userId = Vacation::where('user', 'LIKE', "%{$user}%")->pluck('employee_id')->first();
                $holidays = Holidays::where('employee_id', $userId)->get();
            }else if(!$user && $year){
                $holidays = Holidays::whereYear('holidays.from', $year)
                    ->join('vacation', 'holidays.employee_id', '=', 'vacation.employee_id')
                    ->select('holidays.*', 'vacation.user')
                    ->get();
            }else{
                $holidays = Holidays::join('vacation', 'holidays.employee_id', '=', 'vacation.employee_id')
                    ->select('holidays.*', 'vacation.user')
                    ->get();
            }

            return response()->json([
                'user' => $user,
                'holidays' => $holidays
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}