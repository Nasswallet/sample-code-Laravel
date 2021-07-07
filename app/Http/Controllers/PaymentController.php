<?php

namespace App\Http\Controllers;

use App\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Redirect;


class PaymentController extends Controller
{

    private  $baseURl = 'https://uatgw1.nasswallet.com/payment/transaction';

    Private $username = "";
    Private $password = "";
    Private $grantType = "password";
    Private $orderId = "";
    Private $transactionPin = "";
    Private $amount = "10";
    Private $languageCode = "en";
    Private $basicToken = "";



    public function  getMerchantToken() {
        $payload = [
            'data' => [
                'username' => $this->username,
                'password' => $this->password,
                'grantType' => $this->grantType
            ]
        ];

       return \GuzzleHttp\json_decode(Http::withHeaders([
            'authorization' => "{$this->basicToken}"

        ])->post("{$this->baseURl}/login",
            $payload
        )->body());
    }

    public function makePayment() {
        $response = $this->getMerchantToken();
        $payload = [
            'data' => [
            'userIdentifier' => $this->username,
             'transactionPin' => $this->transactionPin,
            'orderId' => $this->orderId,
            'amount' => $this->amount,
            'languageCode' => $this->languageCode
            ]
        ];

        if($response->responseCode == 0 && $response->data->access_token) {

           return $this->payWithNasswallet($response->data->access_token, $payload);

        } else {
             return dd("$response->message , $response->errCode");
        }
    }


    public  function  payWithNasswallet($access_token, $payload) {

        $response = \GuzzleHttp\json_decode(Http::withHeaders([
            'authorization' => "Bearer  {$access_token}"
        ])->post("{$this->baseURl}/initTransaction",
            $payload
        )->body());

       if($response->responseCode == 0 && $response->data->transactionId) {
          return \redirect()->to("https://uatcheckout1.nasswallet.com/?id={$response->data->transactionId}&token={$response->data->token}&userIdentifier={$payload['data']['userIdentifier']}");
       } else {
           dd('Oops, something went wrong!',"Error Code: {$response->errCode}" );
       }
     }




   
}

