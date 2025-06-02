<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'email' => 'required|string',
            'call_number' => 'required|string',
            'birth_date' => 'required|string',
            'address' => 'required|string',
            'address_complement' => 'required|string',
            'neighborhood' => 'required|string',
            'postal_address_code' => 'required|numeric'
        ];
    }
}
