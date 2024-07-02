<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use MongoDB\Client as MongoClient;
use App\Models\Bills;
use App\Models\Products;


class BillsController extends Controller{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function getNumbersPerMonthAndPerYear(){
        $month = ltrim(date('m'), '0');
        $year = date("Y");
        $numYear = Bills::where('year', $year)->orderBy('num_per_year', 'DESC')->pluck('num_per_year')->first();
        $numMonth = Bills::where('year', $year)->where('month', $month)->orderBy('num_per_month', 'DESC')->pluck('num_per_month')->first();
       
        return view('index',['numYear'=>$numYear + 1,'numMonth'=>$numMonth + 1]);
    }

    public function newBill(Request $request){
        if(Auth::user()->role !== 'visitor'){
            try{
                $request->validate([
                    'published'=> 'required|string',
                    'buyer'=> 'required|string',
                    'sold_date'=>  'required|string',
                    'kt'=> 'required|integer',
                    'year'=>  'required|integer',
                    'month'=> 'required|integer',
                    'num_per_year'=> 'required|integer',
                    'num_per_month'=> 'required|integer',
                    'payed'=> 'required|string',
                ]);
                return redirect()->route('home')->with('success', "Uspešno dodan novi račun.");
                }catch(ValidationException $e){
                    $errors = $e->validator->errors()->all();
                    return redirect()->back()->with('error', implode(', ', $errors));
                };
        }else{
            return redirect()->back()->with('error',"Uporabniki ki imajo role 'visitor', ne morejo izvajati CRUD operacij!");
        }
    }

    public function ImportBills(){
        #Bills::truncate();
        #Products::truncate();
        $mongoClient = new MongoClient(env('MONGODB_HOST'));
        $database = $mongoClient->selectDatabase('test');
        $collection = $database->selectCollection('users');

        $documents = $collection->find([],[]);

        $bills = iterator_to_array($documents);
        #dd($bills);
        function convertDate($dateString) {
            // Funkcija za odstranitev vodilnih ničel iz datuma
            $normalizeDate = function($date) {
                $parts = explode('/', $date);
                return implode('/', array_map('intval', $parts));
            };
        
            $normalizedDateString = $normalizeDate($dateString);
        
            // Poskusi pretvorbo iz formata d/m/Y
            $date1 = \DateTime::createFromFormat('d/m/Y', $dateString);
            if ($date1 && $normalizeDate($date1->format('d/m/Y')) === $normalizedDateString) {
                return $date1->format('Y-m-d');
            }
            
            // Poskusi pretvorbo iz formata m/d/Y
            $date2 = \DateTime::createFromFormat('m/d/Y', $dateString);
            if ($date2 && $normalizeDate($date2->format('m/d/Y')) === $normalizedDateString) {
                return $date2->format('Y-m-d');
            }
            
            // Če datum ne ustreza nobenemu od formatov
            return null;
        }
        try{
            foreach ($bills as $bill){

                $trimSold = preg_replace('/\s+/', '',$bill->soldDate);
                $trimPay = preg_replace('/\s+/', '',$bill->payDate);
                $solded = str_replace('.', '/', $trimSold);
                $payed = str_replace('.', '/', $trimPay);
                $soldDate = convertDate($solded);
                $payDate = convertDate($payed);
                $sold = $soldDate;
                $pay = $payDate;

                $importBill = Bills::create([
                    'published' => $bill->izdal,
                    'buyer' => $bill->buyer,
                    'sold_date' => $sold,
                    'kt' => $bill->kt,
                    'year' => $bill->year,
                    'month' => $bill->month,
                    'num_per_year' => $bill->numPerYear,
                    'num_per_month' => $bill->numPerMonth,
                    'pay_date' => $pay,
                    'payed' => $bill->pay === 'true' ? 1 : 0,
                ]);
                foreach ($bill['products'] as $document){
                    Products::create([
                        'name' => $document['name'][0],
                        'qty' => $document['qty'][0],
                        'price' => preg_replace('/[^\d.]/' ,'',$document['price'][0]),
                        'firstOfWeek'=> $document['firstOfWeek'][0] === 'true' ? 1 : 0,
                        'total' => $document['total'][0],
                        'bills_id' => $importBill->id,
                        'bills_buyer' => $importBill->buyer,
                    ]);
                }
            }
            return view('bills.selled', ['bills' => $bills]);
        }catch(ValidationException $e){
            return redirect()->back()->with('error','Error inserting data: ', $e->errors());
        }
        return redirect(('home'))->with('success', "All data deleted successfully");
    }

    public function testAll(Request $request){
        $month = ltrim(date('m'), '0');
        $year = date('Y');
        $totalBills = Bills::count();
        $thisMonth = Bills::where('month', $month)
                        ->where('year', $year)
                        ->count();
        $bills= Bills::orderBy('year', 'DESC')
        ->orderBy('num_per_year', 'DESC')
        ->paginate(10); //Eloquent ORM

        $products = Products::all();
        return view('bills.selled', compact('bills', 'products', 'thisMonth', 'totalBills'));
    }

    public function findBill($id){
        $bill = Bills::findOrFail($id);
        $products = Products::where('bills_id', $id)->get();
        
        if($bill){
            return view('bills.viewBill', compact('bill','products'));
        };

        return redirect()->back()->with('error', "Napaka. Račun ne obstaja!");
    }
    public function editBill($id){
        $bill = Bills::findOrFail($id);
        $products = Products::where('bills_id', $id)->get();
        
        if($bill){
            return view('bills.edit', compact('bill','products'));
        };

        return redirect()->back()->with('error', "Napaka. Račun ne obstaja!");
    }

    public function searchUser(Request $request){
        if(Auth::user()->role !== 'visitor'){
            try{
                $request->validate([
                    'username' => 'nullable|string',
                    'product' => 'nullable|string',
                ]);
                $username = $request->input('username');
                $product = $request->input('product');
                $bills=null;
                $products=null;
                $allProducts = null;
                $productsSummary = null;
                if($username && $product){
                    $bills = Bills::where('bills.buyer', 'LIKE', "%{$username}%")->get();
                    $products = Products::where('bills_buyer', 'LIKE', "%{$username}%")->where('products.name', 'LIKE', "%{$product}%")->get();
                    $allProducts = Products::where('bills_buyer', 'LIKE', "%{$username}%")->get();
                }else if($username && !$product){
                    $bills = Bills::where('buyer','LIKE', "%{$username}%")->get();
                    $allProducts = Products::where('bills_buyer', 'LIKE', "%{$username}%")->get();
                    $products = null;
                }else if(!$username && $product){
                        $products = Products::selectRaw('name, COUNT(*) as buyed_times')
                            ->groupBy('name')
                            ->where('products.name', 'LIKE', "%{$product}%")
                            ->get();
                }else{
                    $productsSummary = Products::selectRaw('name, COUNT(*) as buyed_times')
                        ->groupBy('name')
                        ->orderBy('buyed_times', 'DESC')
                        ->get();
                }
                return response()->json([
                    'bills' => $bills,
                    'products' => $products,
                    'allProducts' => $allProducts,
                    'productsSummary' => $productsSummary
                ]);
            } catch (ValidationException $e) {
                return response()->json(['error' => $e->getMessage()], 422);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }else{
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    }
}