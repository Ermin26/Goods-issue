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
use App\Models\Bills;
use App\Models\Products;

use function Symfony\Component\String\b;

class BillsController extends Controller{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    private function updateBillsTotal(){
        $bills = Bills::all();
        foreach($bills as $bill){
            $product = Products::where('bills_id', $bill->id)->sum('total');
            $bill->total = $product;
            $bill->save();
        };
    }
    public function getNumbersPerMonthAndPerYear(){
        $month = ltrim(date('m'), '0');
        $year = date("Y");
        $numYear = Bills::where('year', $year)->orderBy('num_per_year', 'DESC')->pluck('num_per_year')->first();
        $numMonth = Bills::where('year', $year)->where('month', $month)->orderBy('num_per_month', 'DESC')->pluck('num_per_month')->first();
        #$this->updateBillsTotal();
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
                    'pay'=> 'required',
                    'product'=> 'required',
                    'qty'=>'required',
                    'price'=>'required',
                    'firstOfWeek'=> 'required',
                    'total'=> 'required',
                    'payment'=> 'required',
                ]);
                $sold = strtotime($request->input('sold_date'));
                $solded = date('Y.m.d', $sold);
                $payDate = $request->input('payedDate') !== null ? strtotime($request->input('payedDate')) : null;
                $payedDate = $payDate !== null ? date('Y.m.d',$payDate) : null;
                #dd($payDate);
                $newBill = Bills::create([
                    'published' => $request->input('published'),
                    'buyer' => $request->input('buyer'),
                    'sold_date' => $solded,
                    'kt' => $request->input('kt'),
                    'year' => $request->input('year'),
                    'month' => $request->input('month'),
                    'num_per_year'=> $request->input('num_per_year'),
                    'num_per_month'=> $request->input('num_per_month'),
                    'pay_date'=> $payedDate,
                    'payed'=> $request->input('pay') === 'true' ? 1 : 0,
                    'total'=> $request->input('payment'),
                ]);

                $products= $request->input('product');
                $qty= $request->input('qty');
                $price= $request->input('price');
                $total= $request->input('total');
                $firstOfWeek= $request->input('firstOfWeek');
                foreach($products as $index => $product){
                    #dd(floatval(str_replace(',', '.', $total[$index])));
                    Products::create([
                        'name'=> $product,
                        'qty'=> $qty[$index],
                        'price'=>$price[$index],
                        'total'=>floatval(str_replace(',', '.', $total[$index])),
                        'firstOfWeek'=>$firstOfWeek[$index] === 'true' ? 1 : 0,
                        'bills_id'=>$newBill->id,
                        'bills_buyer' => $newBill->buyer
                    ]);
                };
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
                    $billFind = Bills::find($importBill->id);
                    $billFind->total += $document['total'][0];
                }
            }
            return view('bills.selled', ['bills' => $bills]);
        }catch(ValidationException $e){
            return redirect()->back()->with('error','Error inserting data: ', $e->errors());
        }
    }

    private function deleteJulyBills(){
        $bills = Bills::where('month', 7)->where('year', 2024)->get();
        foreach($bills as $bill){
            Products::where('bills_id', $bill->id)->delete();
            $bill->delete();
        }
    }
    

    public function testAll(Request $request){
        $bills = null;
        $payed = null;
        $notPayed = null;
        $netoPayed = 0;
        $netoNotPayed = 0;
        $path = $request->path();
        #dd($path);
        $month = ltrim(date('m'), '0');
        $year = date('Y');
        $totalBills = Bills::count();
        $totalPayed = Bills::where('payed', '1')->count();
        $totalNotPayed = Bills::where('payed', '0')->count();
        $thisMonth = Bills::where('month', $month)
                        ->where('year', $year)
                        ->count();
        $allPayed = Bills::select('id')->where('payed', '1')->get();
        $allNotPayed = Bills::select('id')->where('payed', '0')->get();
        foreach($allPayed as $bill){
            $price = Products::where('bills_id', $bill->id)->sum('total');
            $netoPayed += $price;
        };
        foreach($allNotPayed as $bill){
            $dept = Products::where('bills_id', $bill->id)->sum('total');
            $netoNotPayed += $dept;
        };

        if($path == 'all'){
            $bills = Bills::orderBy('year', 'DESC')
            ->orderBy('num_per_year', 'DESC')
            ->paginate(10); //Eloquent ORM
        }
        else if($path == 'all/payed'){
            $payed = Bills::where('payed', '1')->orderBy('year', 'DESC')->orderBy('num_per_year', 'DESC')->paginate(10);
        }else if($path == 'all/notpayed'){
            #dd($netoNotPayed);
            $notPayed = Bills::where('payed', '0')->orderBy('year', 'DESC')->orderBy('num_per_year', 'DESC')->paginate(10);
            
        }

        $products = Products::all();
        
        Log::info('Neto Not Payed (Controller): ' . $netoNotPayed);
        return view('bills.selled', compact('bills','payed','notPayed', 'products', 'thisMonth', 'totalBills', 'totalPayed','totalNotPayed', 'netoPayed', 'netoNotPayed'));
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

    public function updateBill(Request $request,$id){
        if(Auth::user()->role != 'visitor'){
            try{
                #dd($request->input('pay'));
                $request->validate([
                    'published'=> 'required|string',
                    'sold_date'=> 'required',
                    'kt'=> 'required|integer',
                    'year'=>  'required|integer',
                    'month'=> 'required|integer',
                    'num_per_year'=> 'required|integer',
                    'num_per_month'=> 'required|integer',
                    'pay'=> 'required',
                ]);

                $payed = $request->input('pay') === null ? null : $request->input('pay');
                $payedDate = $request->input('pay_date') !== null ? strtotime($request->input('pay_date')) : null;
                $soldDate = strtotime($request->input('sold_date'));
                $bill = Bills::find($id);

                if($bill){
                    $bill->published = $request->input('published');
                    #$bill->buyer = $request->input('buyer');
                    $bill->sold_date = date('Y.m.d',$soldDate);
                    $bill->pay_date = $payedDate !== null ? date('Y.m.d',$payedDate) : null;
                    $bill->kt = $request->input('kt');
                    $bill->year = $request->input('year');
                    $bill->month = $request->input('month');
                    $bill->num_per_year = $request->input('num_per_year');
                    $bill->num_per_month = $request->input('num_per_month');
                    $bill->payed = $payed === 'true' ? 1 : 0;

                    $bill->save();
                }
                return redirect()->route('viewBill', ['id'=>$id])->with('success', "Uspešno posodobljeni podatki!");
            }catch(ValidationException $e){
                $errors = $e->validator->errors()->all();
                return redirect()->back()->with('error', implode(', ', $errors));
            }
        }
        else{
            return redirect()->back()->with('error', "Nimate pravic za spreminjane podatkov!");
        }
    }

    public function updateProducts(Request $request, $id){
        if(Auth::user()->role !== 'visitor'){
            try{
                $request->validate([
                    'name' => 'required',
                    'qty' => 'required',
                    'price' => 'required',
                    'total' => 'required',
                    'payment' => 'required',
                ]);

                $productNames = $request->input('name');
                $productQty = $request->input('qty');
                $productPrice = $request->input('price');
                $productTotal = $request->input('total');
                $billTotal = $request->input('sum');

                $bill = Bills::findOrFail($id);
                $bill->total = $billTotal;
                $bill->save();
                
                $products = Products::where('bills_id',$id)->get();
                
                foreach($products as $index => $product){
                    $product->name = $productNames[$index];
                    $product->qty = $productQty[$index];
                    $product->price = $productPrice[$index];
                    $product->total = $productTotal[$index];

                    $product->save();
                }
                return redirect()->route('viewBill',['id'=>$id])->with('success', "Uspešno posodobljeni podatki o produktu!");

            }catch(ValidationException $e){
                $errors = $e->validator->errors()->all();
                return redirect()->back()->with('error', implode(', ', $errors));
            }
        }else{
        return redirect()->back()->with('error', "Nimate pravic za spreminjane podatkov!");
        }
    }

    public function deleteUserBill($id){
        if(Auth::user()->name !== 'Ermin'){
            return redirect()->back()->with('error', 'Prepovedano! Nimate pooblastila, da izbrišete račun!');
        }
        else{
            try{
                $products = Products::where('bills_id', $id);
                foreach($products as $product){
                    $product->delete();
                }
                Bills::destroy($id);
                return redirect('all')->with('success', "Uspešno izbrisan račun.");
            }
            catch(ValidationException $e){
                return redirect()->back()->with('error', $e->errors());
            }
        }
    }

    public function searchUser(Request $request){
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
                $numberOfBills = null;
                if($username && $product){
                    $bills = Bills::where('bills.buyer', 'LIKE', "%{$username}%")->get();
                    $products = Products::where('bills_buyer', 'LIKE', "%{$username}%")->where('products.name', 'LIKE', "%{$product}%")->get();
                    $allProducts = Products::where('bills_buyer', 'LIKE', "%{$username}%")->get();
                    $numberOfBills = Bills::where('bills.buyer', 'LIKE', "%{$username}%")->count();
                }else if($username && !$product){
                    $bills = Bills::where('buyer','LIKE', "%{$username}%")->get();
                    $allProducts = Products::where('bills_buyer', 'LIKE', "%{$username}%")->get();
                    $products = null;
                    $numberOfBills = Bills::where('bills.buyer', 'LIKE', "%{$username}%")->count();
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
                    'productsSummary' => $productsSummary,
                    'allBills'=>$numberOfBills
                ]);
            } catch (ValidationException $e) {
                return response()->json(['error' => $e->getMessage()], 422);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
}