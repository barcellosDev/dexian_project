<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Mail\OrderShipping;
use App\Models\Order;
use Exception;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $client_id = auth()->user()->id;
        $orders = Order::with('products')->where('client_id', $client_id)->get();
        return ApiResponse::success($orders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $authUser = $request->user();
        $client = Client::find($authUser['id']);

        try {
            $order = $client->orders()->create();
            $product_ids = $request->json()->all();
            $order->products()->attach($product_ids);

            Mail::to('alan.w.barcellos@hotmail.com')->send(new OrderShipping($order));

            return ApiResponse::success([], 'Pedido criado');
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);

            $products = $request->input('products');
            $products = json_decode($products);

            // $order->products()->sync($products);
            OrderProduct::where('order_id', $order->id)
                ->whereNotIn('product_id', $products)
                ->forceDelete();

            $order->products()->syncWithoutDetaching($products);

            return ApiResponse::success([], 'Pedido alterado');
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->delete();

            $maxId = Order::max('id') ?? 0;
            DB::statement("ALTER TABLE orders AUTO_INCREMENT = " . $maxId + 1);

            return ApiResponse::success([], 'Pedido removido');
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
