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
    public function newBill(Request $request){
        if(Auth::user()->role != 'visitor'){
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

        echo $request->all();
        }
    }

    public function ImportBills(){
        Bills::truncate();
        Products::truncate();
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
                        'price' => $document['price'][0],
                        'firstOfWeek'=> $document['firstOfWeek'][0] === 'true' ? 1 : 0,
                        'total' => $document['total'][0],
                        'bills_id' => $importBill->id,
                    ]);
                }
            }
            return view('bills.selled', ['bills' => $bills]);
        }catch(ValidationException $e){
            return redirect()->back()->with('error','Error inserting data: ', $e->errors());
        }
        return redirect(('home'))->with('success', "All data deleted successfully");
    }

    public function allBills() {
        $totalBils = Bills::count();
        $month = ltrim(date('m'), '0');
        $year = date('Y');
        $thisMonth = Bills::where('month',$month,)->where( 'year', $year)->count();
        $bills = Bills::orderBy('year', 'DESC')
              ->orderBy('num_per_year', 'DESC')
              ->paginate(10);
        $products = Products::all();
        if(count($bills)>0){
            return view('bills.selled', ['bills' => $bills, 'products' => $products, 'totalBills' => $totalBils,'thisMonth' => $thisMonth]);
        }
        return redirect()->back()->with('error','Baza podatkov je prazna. ');
    }
}