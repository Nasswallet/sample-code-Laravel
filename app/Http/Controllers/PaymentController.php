<?php

namespace App\Http\Controllers;

use App\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Redirect;


class PaymentController extends Controller
{

    Private $merchantId = "";
    Private $password = "";
    Private $grantType = "password";
    Private $orderId = "123";
    Private $transactionPin = "";
    Private $amount = "10";
    Private $languageCode = "en";
    Private $basicToken = "";

    
    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => 'https://uatgw1.nasswallet.com/payment/transaction/',      
            'timeout' => '1000'
        ]);
    }

    public function  login() {
         $body = [
            'data' => [
                'username' => $this->merchantId,
                'password' => $this->password,
                'grantType' => $this->grantType
            ]
        ];

 
        $response = json_decode($this->client->request('POST', 'login', [
            "headers" => ['authorization' => "$this->basicToken"],
            'json' => $body
            
        ])->getBody());
        
        if ($response->responseCode == 0 && $response->data->access_token) {
            $token =  $response->data->access_token;

            $this->initiateTransaction($token);

        } 
        else
        {
             return var_dump($response->message);
        }

    }

    public function initiateTransaction($token) {
        
        $body = [
            'data' => [
            'userIdentifier' => $this->merchantId,
            'transactionPin' => $this->transactionPin,
            'orderId' => $this->orderId,
            'amount' => $this->amount,
            'languageCode' => $this->languageCode
            ]
        ];

        $response = json_decode($this->client->request('POST', 'initTransaction', [
            "headers" => ['authorization' => "Bearer $token"],
            "json" => $body
        ])->getBody());

        if ($response->responseCode == 0 && $response->data->transactionId) {
            
            $this->redirectUserToPaymentGateway($response->data->transactionId,$response->data->token);
           
                
         } 
         else 
         {
            dd('Oops, something went wrong!',"Error Code: {$response->errCode}");
         }        

    }

    public function redirectUserToPaymentGateway($initaitedTransactionId, $token) {

        return  \redirect()->to("https://uatcheckout1.nasswallet.com/payment-gateway?={$initaitedTransactionId}&token={$token}&userIdentifier={$this->merchantId}");
    
    }

   
}

