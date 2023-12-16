<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ImageURL;

class ProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'description' => ['required'],
            'image' => ['required','url', new ImageURL],
            'brand' => ['required'],
            'price' => ['required','numeric','regex:/^\d+(\.\d{1,2})?$/'],
            'price_sale' => ['required','numeric','regex:/^\d+(\.\d{1,2})?$/'],
            'category' => ['required'],
            'stock' => ['required','integer']
        ];
    }
}
