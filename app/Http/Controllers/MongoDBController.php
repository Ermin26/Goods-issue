<?php

namespace App\Http\Controllers;

use App\Models\Bills;
use App\Models\Products;
use Illuminate\Http\Request;
use MongoDB\Client as MongoClient;
use Exception;


class MongoDBController extends Controller{
    public function index() {
        $mongoClient = new MongoClient(env('MONGODB_HOST'));
        $database = $mongoClient->selectDatabase('test');
        $newCollectionName = 'phpTest';
        $database->createCollection($newCollectionName);
        $collection = $database->selectCollection($newCollectionName);
        if ($collection) {
            return view('users.register');
        } else {
            return redirect()->route('users')->with('error', "Napaka pri ustvarjanju nove kolekcije.");
        }
    }

    public function selectUsers() {
        $mongoClient = new MongoClient(env('MONGODB_HOST'));
        $database = $mongoClient->selectDatabase('test');
        $collection = $database->selectCollection('users');
        $documents = $collection->find([], ['limit' => 2]);

        if($documents){
            foreach($documents as $document){
                $payedBill = NULL;
                if($document['pay'] == true){
                    $payedBill = 1;
                }else{
                    $payedBill = 0;
                };
                $soldDateTimestamp = strtotime($document['soldDate']);
                $sold = date('Y-m-d H:i:s', $soldDateTimestamp);
                $payDateTimestamp = strtotime($document['payDate']);
                $payedDate = date('Y-m-d H:i:s', $payDateTimestamp);
                $bill = Bills::create([
                    'published' => $document['izdal'],
                    'buyer' => $document['buyer'],
                    'soldDate' => $sold,
                    'kt' => $document['kt'],
                    'year' => $document['year'],
                    'month' => $document['month'],
                    'numPerYear' => $document['numPerYear'],
                    'numPerMonth' => $document['numPerMonth'],
                    'payDate' => $payedDate,
                    'payed' => $payedBill,
                ]);
                foreach($document['products'] as $product){
                    Products::create([
                        'name' => $product['name'][0],
                        'qty' => $product['qty'][0],
                        'price' => $product['price'][0],
                        'firstOfWeek' => intval($product['firstOfWeek'][0]),
                        'total' => $product['total'][0],
                        'bills_id' => $bill->id,
                    ]);
                }
            }

            return view('users.register');
        }else{
            return redirect()->route('users')->with('error', "Data not founded!");
        }
    }
}
