<?php

namespace Simplepay\SimplepayApi;

use Illuminate\Support\Facades\Log;
use Simplepay\SimplepayApi\Models\Payment;

class SimplepayApi
{
    // Build wonderful things

    public function start(Payment $payment){

        Log::info('Start_Service');

        $trx = new \SimplePayStart();
        $trx->addData('currency', $payment->currency);

        $trx->addConfig(config('simplepay-api'));

        //ORDER PRICE/TOTAL
        //-----------------------------------------------------------------------------------------
        $trx->addData('total', $payment->total);

        //ORDER ITEMS
        //-----------------------------------------------------------------------------------------


        foreach ($payment->products as $product) {
            $trx->addItems([
                'ref' => $product->ref,
                'title' => $product->title,
                'description' => $product->description,
                'amount' => $product->amount,
                'price' => $product->price,
                'tax' => $product->tax
            ]);
        }

        // OPTIONAL DATA INPUT ON PAYMENT PAGE
        //-----------------------------------------------------------------------------------------
        //$trx->addData('maySelectEmail', true);
        //$trx->addData('maySelectInvoice', true);
        //$trx->addData('maySelectDelivery', ['HU']);


        // SHIPPING COST
        //-----------------------------------------------------------------------------------------
        //$trx->addData('shippingCost', 20);


        // DISCOUNT
        //-----------------------------------------------------------------------------------------
        //$trx->addData('discount', 10);


        // ORDER REFERENCE NUMBER
        // uniq oreder reference number in the merchant system
        //-----------------------------------------------------------------------------------------
        $trx->addData('orderRef',$payment->id);

        // CUSTOMER
        // customer's name
        //-----------------------------------------------------------------------------------------
        //$trx->addData('customer', 'v2 SimplePay Teszt');

        // customer's registration mehod
        // 01: guest
        // 02: registered
        // 05: third party
        //-----------------------------------------------------------------------------------------
        $trx->addData('threeDSReqAuthMethod', '02');


        // EMAIL
        // customer's email
        //-----------------------------------------------------------------------------------------
        $trx->addData('customerEmail', $payment->customer_email);


        // LANGUAGE
        // HU, EN, DE, etc.
        //-----------------------------------------------------------------------------------------
        $trx->addData('language', $payment->language);


        // TWO STEP
        // true, or false
        // If this field does not exist is equal false value
        // Possibility of two step needs IT support setting
        //-----------------------------------------------------------------------------------------
        /*
        if (isset($_REQUEST['twoStep'])) {
            $trx->addData('twoStep', true);
        }
        */

        // TIMEOUT
        // 2018-09-15T11:25:37+02:00
        //-----------------------------------------------------------------------------------------
        $timeoutInSec = 600;
        $timeout = @date("c", time() + $timeoutInSec);
        $trx->addData('timeout', $timeout);


        // METHODS
        // CARD or WIRE
        //-----------------------------------------------------------------------------------------
        $trx->addData('methods', array('CARD'));


        // REDIRECT URLs
        //-----------------------------------------------------------------------------------------

        // common URL for all result
        $trx->addData('url', config('simplepay-api.URL'));

        // uniq URL for every result type
        /*
            $trx->addGroupData('urls', 'success', $config['URLS_SUCCESS']);
            $trx->addGroupData('urls', 'fail', $config['URLS_FAIL']);
            $trx->addGroupData('urls', 'cancel', $config['URLS_CANCEL']);
            $trx->addGroupData('urls', 'timeout', $config['URLS_TIMEOUT']);
        */


        // Redirect from Simple app to merchant app
        //-----------------------------------------------------------------------------------------
        //$trx->addGroupData('mobilApp', 'simpleAppBackUrl', 'myAppS01234://payment/123456789');

        // INVOICE DATA
        //-----------------------------------------------------------------------------------------
        $trx->addGroupData('invoice', 'name', $payment->invoice->name);
        //$trx->addGroupData('invoice', 'company', '');
        $trx->addGroupData('invoice', 'country', $payment->invoice->country);
        $trx->addGroupData('invoice', 'state', $payment->invoice->state);
        $trx->addGroupData('invoice', 'city', $payment->invoice->city);
        $trx->addGroupData('invoice', 'zip', $payment->invoice->zip);
        $trx->addGroupData('invoice', 'address', $payment->invoice->address);
        //$trx->addGroupData('invoice', 'address2', 'Address 2');
        //$trx->addGroupData('invoice', 'phone', '06201234567');


        // DELIVERY DATA
        //-----------------------------------------------------------------------------------------
        /*
        $trx->addGroupData('delivery', 'name', 'SimplePay V2 Tester');
        $trx->addGroupData('delivery', 'company', '');
        $trx->addGroupData('delivery', 'country', 'hu');
        $trx->addGroupData('delivery', 'state', 'Budapest');
        $trx->addGroupData('delivery', 'city', 'Budapest');
        $trx->addGroupData('delivery', 'zip', '1111');
        $trx->addGroupData('delivery', 'address', 'Address 1');
        $trx->addGroupData('delivery', 'address2', '');
        $trx->addGroupData('delivery', 'phone', '06203164978');
        */


        //payment starter element
        // auto: (immediate redirect)
        // button: (default setting)
        // link: link to payment page
        //-----------------------------------------------------------------------------------------
        //$trx->formDetails['element'] = 'button';


        //create transaction in SimplePay system
        //-----------------------------------------------------------------------------------------
        $trx->runStart();

        return $trx;
    }

    public function back($raw, $signature){

        $trx = new \SimplePayBack();

        $trx->addConfig(config('simplepay-api'));

        $trx->transactionBase['currency'] = 'HUF';

        //result
        //-----------------------------------------------------------------------------------------
        $result = array();
        if (isset($raw) && isset($signature)) {
            if ($trx->isBackSignatureCheck($raw, $signature)) {
                $result = $trx->getRawNotification();
            }
        }

        return $result;
    }

    public function query($orderRef,$transactionId,$merchant){

        $trx = new \SimplePayQuery();

        $trx->addConfig(config('simplepay-api'));


        //add merchant transaction ID
        //-----------------------------------------------------------------------------------------
        if (isset($orderRef)) {
            $trx->addMerchantOrderId($orderRef);
        }


        //add SimplePay transaction ID
        //-----------------------------------------------------------------------------------------
        if (isset($transactionId)) {
            $trx->addSimplePayId($transactionId);
        }


        //add merchant account ID
        //-----------------------------------------------------------------------------------------
        if (isset($merchant)) {
            $trx->addConfigData('merchantAccount', $merchant);
        }


        //get detailed result
        //-----------------------------------------------------------------------------------------
        //$trx->addData('detailed', true);


        //get refunds
        //-----------------------------------------------------------------------------------------
        //$trx->addData('refunds', true);


        //Time interval
        //-----------------------------------------------------------------------------------------
        /*
        $fromInSec = -86400;
        $from = @date("c", time() + $fromInSec);
        $trx->addData('from', $from);

        $untilInSec = 0;
        $until = @date("c", time() + $untilInSec);
        $trx->addData('until', $until);
        */


        //start query
        //-----------------------------------------------------------------------------------------
        $res = $trx->runQuery();

        return $trx->getReturnData();

        //test data
        //-----------------------------------------------------------------------------------------
        print "API REQUEST";
        print "<pre>";
        print_r($trx->getTransactionBase());
        print "</pre>";

        print "API RESULT";
        print "<pre>";
        print_r($trx->getReturnData());
        print "</pre>";

    }

    public function ipn(){


        $json = file_get_contents('php://input');


        $input = (array)json_decode($json);
        /*
                $payment = Payment::with(['invoice','products'])->findOrFail($input['orderRef']);
                $payment->ipn_status = $input['status'];
                $payment->ipn_finish_date = $input['finishDate'];
                $payment->ipn_payment_date = $input['paymentDate'];
                $payment->save();
        */
        $trx = new \SimplePayIpn();

        $trx->addConfig(config('simplepay'));

        //check signature and confirm IPN
        //-----------------------------------------------------------------------------------------
        if ($trx->isIpnSignatureCheck($json)) {
            /**
             * Generates all response
             * Puts signature into header
             * Print response body
             *
             * Use this OR getIpnConfirmContent
             */
            $trx->runIpnConfirm();

            /**
             * Generates all response
             * Gets signature and response body
             *
             * You must set signeature in header and you must print response body!
             *
             * Use this OR runIpnConfirm()
             */
            $confirm = $trx->getIpnConfirmContent();
            return $confirm;

            //Log::info($confirm['confirmContent']['status']);
        }

        //no need to print further information
        exit;

    }

    public function refund(){
        $trx = new \SimplePayRefund();

        $trx->addConfig(config('simplepay-api'));


        //add merchant transaction ID
        //-----------------------------------------------------------------------------------------
        if (isset($_REQUEST['orderRef'])) {
            $trx->addData('orderRef', $_REQUEST['orderRef']);
        }


        //add SimplePay transaction ID
        //-----------------------------------------------------------------------------------------
        if (isset($_REQUEST['transactionId'])) {
            $trx->addData('transactionId', $_REQUEST['transactionId']);
        }


        //add merchant account ID
        //-----------------------------------------------------------------------------------------
        if (isset($_REQUEST['merchant'])) {
            $trx->addConfigData('merchantAccount', $_REQUEST['merchant']);
        }


        // amount to refund
        //-----------------------------------------------------------------------------------------
        $trx->addData('refundTotal', 5);


        // add currency
        //-----------------------------------------------------------------------------------------
        $trx->addData('currency', 'HUF');


        // start refund
        //-----------------------------------------------------------------------------------------
        $trx->runRefund();


        // test data
        //-----------------------------------------------------------------------------------------
        print "API REQUEST";
        print "<pre>";
        print_r($trx->getTransactionBase());
        print "</pre>";

        print "API RESULT";
        print "<pre>";
        print_r($trx->getReturnData());
        print "</pre>";

    }

    public function finish(){

        $trx = new \SimplePayFinish();

        $trx->addConfig(config('simplepay-api'));

        //add merchant transaction ID
        //-----------------------------------------------------------------------------------------
        if (isset($_REQUEST['orderRef'])) {
            $trx->addData('orderRef', $_REQUEST['orderRef']);
        }


        //add SimplePay transaction ID
        //-----------------------------------------------------------------------------------------
        if (isset($_REQUEST['transactionId'])) {
            $trx->addData('transactionId', $_REQUEST['transactionId']);
        }


        //add merchant account ID
        //-----------------------------------------------------------------------------------------
        if (isset($_REQUEST['merchant'])) {
            $trx->addConfigData('merchantAccount', $_REQUEST['merchant']);
        }


        //add original total amount
        //-----------------------------------------------------------------------------------------
        if (isset($_REQUEST['originalTotal'])) {
            $trx->addData('originalTotal', $_REQUEST['originalTotal']);
        }


        //add approved total amount
        //-----------------------------------------------------------------------------------------
        if (isset($_REQUEST['approveTotal'])) {
            $trx->addData('approveTotal', $_REQUEST['approveTotal']);
        }


        //add currency
        //-----------------------------------------------------------------------------------------
        $trx->transactionBase['currency'] = 'HUF';


        //start finish
        //-----------------------------------------------------------------------------------------
        $trx->runFinish();


        //test data
        //-----------------------------------------------------------------------------------------
        print "API REQUEST";
        print "<pre>";
        print_r($trx->getTransactionBase());
        print "</pre>";

        print "API RESULT";
        print "<pre>";
        print_r($trx->getReturnData());
        print "</pre>";

    }


}
