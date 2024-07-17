<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use MongoDB\Client as MongoClient;
use App\Models\Vacation;
use App\Models\Employee;

class VacationController extends Controller{
    public function importVacations(){
        $mongoClient = new MongoClient(env('MONGODB_HOST'));
        $database = $mongoClient->selectDatabase('test');
        $collection = $database->selectCollection('vacations');
        $data = $collection->find([],[]);

        $vacations = iterator_to_array($data);
        dd($vacations);
        /*
        try{
            foreach ($vacations as $vacation){
                Vacation::create([
                    'user'=>$vacation->user,
                    'last_year'=>$vacation->lastYearHolidays,
                    'holidays'=>$vacation->holidays,
                    'used_holidays'=>$vacation->usedHolidays,
                    'overtime'=>$vacation->overtime
                ]);
            }
            return redirect()->route('home')->with('success', "Uspešno uveženi podatki za dopuste.");
        }catch(ValidationException $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
            */
    }


}