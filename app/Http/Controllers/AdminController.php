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
        $tables = DB::select('SHOW TABLES');
        #var_dump($tables);
        return view('admin', compact('tables'));
    }

    public function getDb(Request $request){
        $db = $request->input('db');

        $result = $db::all();
        var_dump($db);
        #return response()->json([
        #    "data" => $result
        #]);
    }
}