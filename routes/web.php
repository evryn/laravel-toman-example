<?php

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

Route::get('/new-payment', 'PaymentController@new')->name('payment.new');

Route::post('/new-payment', 'PaymentController@create')->name('payment.create');

Route::get('/payment-callback', 'PaymentController@callback')->name('payment.callback');
