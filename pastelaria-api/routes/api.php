<?php

use App\Helpers\ApiResponse;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('guest')->post('/login', function (Request $request) {
//     $credentials = $request->only('email', 'password');

//     if (!Auth::attempt($credentials)) {
//         return response()->json(['message' => 'Invalid credentials'], 401);
//     }

//     $request->session()->regenerate();

//     return response()->json(Auth::user());
// });

Route::post('/login', function (Request $request) {
    $data = $request->json()->all();
    $client = Client::where('email', $data['email'])->first();

    if ($client) {
        $token = $client->createToken('client-token')->plainTextToken;
        return ApiResponse::success($token, 'Logado com sucesso');
    }

    return ApiResponse::error("Email incorreto");
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return response()->json(['message' => 'Logged out']);
});

Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
    return ApiResponse::success($request->user());
});

Route::apiResource('products', ProductController::class)->middleware('auth:sanctum');
Route::apiResource('clients', ClientController::class)->middleware('auth:sanctum');
Route::apiResource('orders', OrderController::class)->middleware('auth:sanctum');