<?php

namespace Simplepay\SimplepayApi\HTTP\Controllers;

use App\Http\Controllers\Controller;
use Faker\UniqueGenerator;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Simplepay\SimplepayApi\Models\Invoice;
use Simplepay\SimplepayApi\Models\Payment;
use Simplepay\SimplepayApi\Models\Product;
use Simplepay\SimplepayApi\SimplepayApi;
use Faker\Factory;

class SandboxController extends Controller
{

    public function show(){
        return view('simplepay::home');
    }

    public function getTestPayment($product_number = 2){

        $invoice = new Invoice();
        $invoice->name = 'Teszt Felhasznalo';
        $invoice->country = 'HU';
        $invoice->state = 'Tesztfalva';
        $invoice->city = 'Tesztfalva';
        $invoice->zip = '9999';
        $invoice->address = 'Próba sétány 33.';

        $products = array();
        for ($i = 1; $i<=$product_number ; $i++){
            $product = new Product();
            $product->ref = rand(1,1000);
            $product->title = 'Termék '.$i;
            $product->description = 'Termékleírás '.$i;
            $product->amount = rand(1,10);
            $product->price = rand(500,10000);
            $product->tax = 27;
            array_push($products,$product);
        }

        $payment = new Payment($products, $invoice);
        $payment->id = 'test'.time();
        $payment->total = 3000;
        $payment->customer_email = 'test@test.hu';
        $payment->language = 'HU';
        $payment->currency = 'HUF';

        return $payment;
    }

    public function start_test(){

        $payment = $this->getTestPayment();

        $simplepay =  new SimplepayApi();
        $trx = $simplepay->start($payment);

        $returnData = $trx->getReturnData();
        $baseData = $trx->getTransactionBase();

        return view('simplepay::start')->with(compact('payment','returnData','baseData'));

    }

    public function back(Request $request){
        $simplepay =  new SimplepayApi();
        $payment_back = $simplepay->back(
            $request::input('r'),
            $request::input('s')
        );
        return view('simplepay::back')->with(compact('payment_back'));
    }

    public function query(Request $request){
        $simplepay =  new SimplepayApi();


        $query = $simplepay->query(
            $request::input('orderRef'),
            $request::input('transactionId'),
            $request::input('merchant')
        );
        //return view('payment.back')->with(compact('payment_back'));
        dd($query);
    }

    public function ipn(){
        $simplepay =  new SimplepayApi();

        return $simplepay->ipn();
    }

    public function refund(){
        $simplepay =  new SimplepayApi();
        $simplepay->refund();
    }

    public function finish(){
        $simplepay =  new SimplepayApi();
        $simplepay->finish();
    }

}
