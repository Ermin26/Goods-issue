<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use MongoDB\Client as MongoClient;
use App\Models\Bills;
use App\Models\Products;
use App\Models\Holidays;
use Illuminate\Support\Facades\DB;




class AdminController extends Controller{

    public function dbs(){
        if(Auth::user()->name == 'Ermin'){
            $notifications = Holidays::where('status', 'Pending')->get();
            $tables = DB::select('SHOW TABLES');
            return view('admin', compact('tables','notifications'));
        }else{
            return redirect()->back()->with('error', 'No access');
        }
    }

    public function getDb(Request $request){
        $db = $request->input('db');

        $result = DB::select("SHOW COLUMNS FROM $db");
        $tableResult = DB::table($db)->paginate(30);
        return response()->json([
            "data" => $result,
            "tableResult" => $tableResult
        ]);
    }
}