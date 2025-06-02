<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreProductRequest;
use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ApiResponse::success(Product::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $fullBaseUrl = request()->getSchemeAndHttpHost();

        $name = $request->input('name');
        $price = $request->input('price');
        $path = null;

        if ($request->hasFile('photo')) {
            $path = $fullBaseUrl . "/storage/" . $request->file('photo')->store('images', 'public');
        }

        $product = Product::create([
            'name' => $name,
            'price' => $price,
            'photo_src' => $path
        ]);

        return ApiResponse::success($product, 'Produto criado com sucesso');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Product::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreProductRequest $request, $id)
    {
        $fullBaseUrl = request()->getSchemeAndHttpHost();
        $product = Product::findOrFail($id);

        $name = $request->input('name');
        $price = $request->input('price');
        $path = null;

        if ($request->hasFile('photo')) {
            $path = $fullBaseUrl . "/" . $request->file('photo')->store('images', 'public');
        }

        // Update product
        $product->update([
            'name' => $name,
            'price' => $price,
            'photo_src' => $path
        ]);


        return ApiResponse::success($product, 'Dados atualizados com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $fullBaseUrl = request()->getSchemeAndHttpHost();

        $image_filename = str_replace($fullBaseUrl, '', $product->photo_src);

        //Storage::disk('public')->delete($image_filename);
        $product->delete();

        return ApiResponse::success([], 'Produto removido');
    }
}
