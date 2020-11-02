<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Route::post('/payment', 'PaymentController@makePayment');



Route::get('/transactions', function() {
    return view('transaction');
} );

Route::post('/transactions', 'PaymentController@getTransaction');
