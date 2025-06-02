<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'price' => 'numeric|min:0.00|max:9999.99',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O título do produto é obrigatório.',
            'name.string' => 'O título do produto deve ser um texto.',
            'name.max' => 'O título do produto não pode ter mais que 255 caracteres.',

            'price.required' => 'O preço é obrigatório.',
            'price.numeric' => 'O preço deve ser um número.',
            'price.min' => 'O preço não pode ser negativo.',
            'price.max' => 'O preço não pode ser maior que 999999.99.',

            'photo.*.image' => 'Cada arquivo deve ser uma imagem válida.',
            'photo.*.mimes' => 'Só são permitidas imagens em jpg e png.',
            'photo.*.max' => 'Cada imagem não pode ultrapassar 2MB.',
        ];
    }
}
