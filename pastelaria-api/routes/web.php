<?php

use App\Models\Order;
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

Route::get("/test-email", function() {
    $client_id = 1;
    $order = Order::with('products')->where('client_id', $client_id)->get();

    return view('emails.order', ['order' => $order[0]]);
});
