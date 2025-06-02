<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Models\Client;
use Exception;
use Illuminate\Http\Request;
use PDOException;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ApiResponse::success(Client::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->json()->all();

        try {
            $has_client = Client::where('email', $data['email'])->first();

            if ($has_client)
                throw new Exception("Não pode existir dois clientes com o mesmo email");

            Client::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'call_number' => $data['call_number'],
                'birth_date' => $data['birth_date'],
                'address' => $data['address'],
                'address_complement' => $data['address_complement'],
                'neighborhood' => $data['neighborhood'],
                'postal_address_code' => $data['postal_address_code'],
            ]);
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage());
        }

        return ApiResponse::success([], 'Cliente cadastrado com sucesso');
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
            $client = Client::findOrFail($id);

            $data = $request->input('client_data');
            $data = json_decode($data, true);

            $client->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'call_number' => $data['call_number'],
                'birth_date' => $data['birth_date'],
                'address' => $data['address'],
                'address_complement' => $data['address_complement'],
                'neighborhood' => $data['neighborhood'],
                'postal_address_code' => $data['postal_address_code']
            ]);

            return ApiResponse::success([], "Cliente atualizado");
        } catch (Exception $e) {
            return ApiResponse::error($e);
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
            $client = Client::findOrFail($id);

            foreach ($client->orders as $order) {
                $order->delete();
            }

            $client->delete();

            return ApiResponse::success([], "Cliente removido");
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
