<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Costs;
use App\Models\Holidays;
use \Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use MongoDB\Client as MongoClient;


class CostsController extends Controller{

    public function importCosts(){
        $mongoClient = new MongoClient(env('MONGODB_HOST'));
        $database = $mongoClient->selectDatabase('test');
        $collection = $database->selectCollection('costs');
        $documents = $collection->find([]);
        $bills = iterator_to_array($documents);

        foreach ($bills as $document){
            $date = strtotime($document['date']);
            $dateBooked = strtotime($document['bookedDate']);
            $billDate = date('Y-m-d', $date);
            $booked = date('Y-m-d', $dateBooked);
            #dd($booked);
            $allBills = Costs::create([
                'date' => $billDate,
                'products' => $document['buyedProducts'][0],
                'price' => $document['totalPrice'],
                'booked_date' => $booked,
                'users_name' => $document['bookedUser']
            ]);
        }
        return view('index')->with('success', 'Uspešno importani produkti!');
    }

    public function allCosts(){
        $bills = Costs::all();
        #$payedBills = DB::table('bills')->join('products', 'bills_id', '=', 'bills.id' )->select('products.total')->where('bills.payed', '=', '1')->get();
        $payedBills = DB::table('bills')->join('products', 'bills_id', '=', 'bills.id' )->where('bills.payed', '=', '1')->sum('products.total');
        $notifications = Holidays::where('status', 'Pending')->get();
        return view('bills.costs', ['bills'=>$bills, 'payedBills'=>$payedBills, 'notifications'=>$notifications]);
    }

    public function addCosts(Request $request){
        if(Auth::user()->role == 'admin'){
            #dd("Request data: ", $request->all());
            try{
                $request->validate([
                    'date' => 'required',
                    'buyedProducts' => 'required|string|',
                    'totalPrice'=>'required',
                ]);
                $billDate = strtotime($request->input('date'));
                $date = date('Y-m-d', $billDate);
                Costs::create([
                    'date' => $date,
                    'products' => $request->input('buyedProducts'),
                    'price' => $request->input('totalPrice'),
                    'booked_date' => date('Y-m-d'),
                    'users_name' => Auth::user()->name,
                ]);
                return redirect()->route('costs')->with('success', 'Novi račun dodan!');
            }catch(ValidationException $e){
                return redirect()->back()->with('error', "Error: ", $e->errors());
            }
        }else{
            return redirect()->back()->with('error', "Nimate pravic dodavanje novih računov!");
        }
    }

    public function deleteBill($id){
        if(Auth::user()->role == 'admin'){
            $bill = Costs::findOrFail($id);
            $bill->delete();
            return redirect()->route('costs')->with('success', 'Uspešno izbrisan račun!');
        }else{
            return redirect()->back()->with('error', "Nimate pravice za brisanje, spreminjanje ali dodavanje računov!");
        };
    }
}